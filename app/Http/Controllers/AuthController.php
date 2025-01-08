<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

use function Laravel\Prompts\password;

class AuthController extends Controller
{   /**
*
* @OA\Schema(
*  schema="token",
* description="token",
* title= "token",
*required={
*  "token"
*  },
*   @OA\Property(   
*   property="token",   
*   type="string",   
*   format="string",   
*   description="Token for authentication"   
*    )
*)
* @OA\Post(
*   tags={"Auth"},
*   path="/api/login",
*   summary="Authenticate user and generate a token",
*   @OA\RequestBody(
*     required=true,
*          @OA\JsonContent(
*                 @OA\Property(property="password", type="string"),
*     
*                @OA\Property(property="email", type="string")
*                         )
*                 ),
*   @OA\Response(
*            response=200,
*            description="OK",
*            @OA\JsonContent(
*                        allOf= {
*                        @OA\Schema(ref="#components/schemas/token")      
*                               }
*                         )              
*
*            ),
*   @OA\Response(response=400, description="Invalid user"),
*   
* )
*/


    public static function loginSanctum(Request $request)
    {   
        //Validar campos de entrada email e senha
        // Validar se o usuário existe

         if (!Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')])){

        return response()->json("Usuario invalido",400);

    }
        $user = Auth::user();
        $token = $user->createToken("auth");
       
        return response()->json(["token"=>$token],200);

    }

    public static function login(Request $request)
    {
         $request->validate([
            'email' => 'string|max:255|email',
            'password' => 'string|max:255'

         ]);
        $app_url= env('APP_URL');
	    $email= $request->input('email');
        $password= $request->input('password');

        $user= User::where('email',$email)->first();
        if($user == null){
            return response()->json(['erro'=> 'usuario nao encontrado'],400);
        }

        else if (!Auth::attempt([
                        'email'=>$email,
                        'password'=>$password,
                                ])){

            return response()->json("senha incorreta",400);
    
        }
        
		
        $response  = Http::asForm()->post($app_url . '/oauth/token',[
											           'grant_type' => 'password',
														'client_id' => env('PASSPORT_CLIENT_ID'),
														'client_secret' => env('PASSPORT_CLIENT_SECRET'),
														'username' => $email,
														'password' => $password,
														'scopes'   =>       [  'users.index',
                                                                                'users.show',
                                                                                'users.update',
                                                                                'users.store',
                                                                                'users.delete',
                                                                                
                                                                              
                                                                            ],
												 ]);
            
         $token= $response->json();

		

         return $token;

    }

     /**

* @OA\Post(
*   tags={"Auth"},
*   path="/api/logout",
*   summary="Log out and delete user token",
*   @OA\RequestBody(
*     required=true,
*          @OA\JsonContent(
*                 @OA\Property(property="password", type="string"),
*     
*
*                @OA\Property(property="email", type="string")
*                         )
*                 ),
*   @OA\Response(response=200, description="OK"),
*  @OA\Response(response=400, description="Invalid user"),
*   
* )
*/

public static function logout(Request $request){

    if (!Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')])){

        return response()->json("Invalid user",400);

    }
    $user = Auth::user();
    $user->tokens()->delete();
    Cookie::forget('token');
    return response()->json("logged out",200);


    
}

}
