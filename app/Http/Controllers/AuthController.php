<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

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


    public static function login(Request $request)
    {   
         if (!Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')])){

        return response()->json("Invalid user",400);

    }
        $user = Auth::user();
        $token = $user->createToken("auth");
       
        return response()->json(["token"=>$token],200);

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
