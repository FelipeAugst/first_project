<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\User;
use NunoMaduro\Collision\Writer;
use Illuminate\Validation\Rule;

class  UserController extends Controller
{    
    public static function index(Request $request)
    { 
        $validator = Validator::make($request->all(), [
			'limit' => 'required|int', 
             'orderby'=>'required|string|exists:users',
             'direction'=>['required','string',Rule::in('asc','desc','ASC','DESC')]
        ]);
      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $limit= $request->input('limit');
		$orderby  = $request->input('orderby');
		$direction = $request->input('direction');

        $users= User::select('id','name','email')
                              ->orderBy($orderby,$direction)
                             ->paginate($limit);
       

     return response()->json($users, 200);

    }

   public static function store(Request $request){
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

   public static function update(Request $request,$id){

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


   public static function show(Request $request,$id){

    $user = User::find($id);
    echo $user;

    return response()->json($user,200);

     

   }

   public static function destroy(Request $request, $id){

    

   
     $profiles = User::find($id)->profiles;
     if($profiles->isNotEmpty()){
        return response()->json(["error"=> "can not delete a user with profiles","profiles"=>$profiles],500);
     }
      $user = User::find($id);
      $user->delete();
    return response()->json(["deleted"=> $user],200);

     

   }

}

