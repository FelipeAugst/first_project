<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\User;
use NunoMaduro\Collision\Writer;
use Illuminate\Validation\Rule;

class  UserController extends Controller
{    /**
    * @OA\Get(
    *   tags={"User"},
    *   path="/api/user/index",
    *   summary="List all users.",
    *   security= {{"bearerAuth:{}"}},
    * 
    *   @OA\Parameter(
    *     name="limit",
    *     in="query",
    *     required=true,
    *     description= "limit of results returned",
    *     @OA\Schema(type="int")
    *   ),

    *  @OA\Parameter(
    *    name="orderby",
    *    in="query",
    *    required=true,
    *    description= "column to order data",
    *    @OA\Schema(type="string")
    *  ),
    * @OA\Parameter(
    *  name="direction",
    *  in= "query",
    *  required=true,
    *  description= "direction of data ordenation",
    *  @OA\Schema(type="string")
    *  ),
    * @OA\Response(response=200, 
     *       description="OK", 
     *        @OA\JsonContent(
     *               allOf= { 
     *                    @OA\Schema(type="array",
     *                     @OA\Items(ref="#/components/schemas/user"))
     *                    
     *                  }
     *                     )
     *                   ),
     * 
    *   @OA\Response(response=401, description="Unauthenticated"),
    *   @OA\Response(response=422, description="unprocessable content"),
    *   @OA\Response(response=403,description="Unauthorized")

    * )
    */


    public static function index(Request $request)
    { 
      if (!$request->user()->tokenCan('users.index')) 
		{
            return response()->json(['mensagem' => 'Não autorizado para visualizar usuarios.'], 403);
        }
        $validator = Validator::make($request->all(), [
			'limit' => 'required|int', 
             'orderby'=>'required|string',
             'direction'=>['required','string',Rule::in(['asc','desc','ASC','DESC'])]
        ]);
      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $limit= $request->input('limit');
		$orderby  = $request->input('orderby');
		$direction = $request->input('direction');

        $users= User::with("profiles")->select('id','name','email')
                              ->orderBy($orderby,$direction)
                             ->paginate($limit);
       

     return response()->json($users, 200);

    }


   /**
    * @OA\Post(
    *       tags={"User"},
    *       path="/api/user/store",
    *       summary="Create a new user",
    *       security= {{"bearerAuth:{}"}},
    *       @OA\RequestBody(
    *           required=true,
    *           @OA\JsonContent(
    *               allOf={
    *                       @OA\Schema(ref="#components/schemas/user")
    *                     }
    *                         ),
    *                     ),
    *   
    *   @OA\Response(response=201, description="Created",
    *              @OA\JsonContent(
    *                     allOf={
    *                          @OA\Schema(ref="#components/schemas/user")
    *                          }
    *                           )
    *              ),
    *   @OA\Response(response=401, description="Unauthenticated"),
    *   @OA\Response(response=422, description="Unprocessable Content"),
    *   @OA\Response(response=403,description="Unauthorized")
    *   )
    *      )
    */



   public static function store(Request $request)
   {

    if (!$request->user()->tokenCan('users.store')) 
		{
            return response()->json(['mensagem' => 'Não autorizado para criar usuarios.'], 403);
        }
    $validator = Validator::make($request->all(),[
               'name' => 'required|unique:users,name|max:255|string',
               'email' => 'required|unique:users,email|max:255|string|email',
               'password'=> 'required|max:255|string'
    ]);

    if($validator->fails()){

        return response()->json(['error' => $validator->errors()], 422);

    }


    $user = new User($request->all());
    $user->save();

    return response()->json(['created'=>$user],201);
    


   }


/**
 * @OA\Put(
 *   tags={"User"},
 *   path="/user/update",
 *   security= {{"bearerAuth:{}"}},
 *   summary="Update an existing user",
 *    @OA\Parameter(
 *         name= "id",
 *     in="path",
 *     required=true
 *     
 *   ),
 *   @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *                 allOf={
 *                    @OA\Schema(ref="#components/schemas/user")
 *                     }
 *         
 *                      )  
 *                  
 *     
 *        ),
 *   @OA\Response(response=200, description="Ok"),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   @OA\Response(response=403,description="Unauthorized")
 *   )
 */






   public static function update(Request $request,$id)
   {
    if (!$request->user()->tokenCan('users.update')) 
		{
            return response()->json(['mensagem' => 'Não autorizado para editar usuarios.'], 403);
        }
    $validator = Validator::make($request->all(),[
        'name' => 'required|max:255|string',
        'email' => 'required|max:255|string|email',
        'password'=> 'required|max:255|string'
]);

if($validator->fails()){

 return response()->json(['error' => $validator->errors()], 422);
}


User::where('id',$id)->update($request->all());



return response()->json(['updated'=>$request->all()],200);



   }


   /**
     * @OA\Get(
     *   tags={"User"},
     *   path="/api/user/{id}/show",
     *   security= {{"bearerAuth:{}"}},
     *   summary="Search and show a user by id.",
     *   @OA\Parameter(
     *     name= "id",
     *     in="path",
     *     required=true
     *     
     *   ),
     *   @OA\Response(
     *     response=200, 
     *     description="OK",
     *      @OA\JsonContent(
     *              allOf={ 
     *                    @OA\Schema(ref="#components/schemas/user") 
     *                   }
     *               )
     * 
     * ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=403,description="Unauthorized")
     *   
     * )
     */


   public static function show(Request $request,$id){

    if (!$request->user()->tokenCan('users.show')) 
		{
            return response()->json(['mensagem' => 'Não autorizado para listar usuarios.'], 403);
        }

    $user = User::find($id);
    echo $user;

    return response()->json($user,200);

     

   }


    /**
     * @OA\Delete(
     *   tags={"User"},
     *   path="/api/user/{id}/destroy",
     *   security= {{"bearerAuth:{}"}},
     *   summary="Delete a user by id.",
     *   @OA\Parameter(
     *     name= "id",
     *     in="path",
     *     required=true
     *     
     *   ),
     *   @OA\Response(
     *     response=200, 
     *     description="OK"
     *              ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=403,description="Unauthorized")
     *   
     * )
     */

   public static function destroy(Request $request, $id)
   {
    if (!$request->user()->tokenCan('users.delete')) 
		{
            return response()->json(['mensagem' => 'Não autorizado para delete usuarios.'], 403);
        }
    
     $profiles = User::find($id)->profiles;
     if($profiles->isNotEmpty()){
        return response()->json(["error"=> "can not delete a user with profiles","profiles"=>$profiles],500);
     }
      $user = User::find($id);
      $user->delete();
    return response()->json(["deleted"=> $user],200);

     

   }

}

