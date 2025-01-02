<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class AuthController extends Controller
{
    public static function login(Request $request)
    {   
         if (!Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')])){

        return response()->json("Invalid user",400);

    }
        $user = Auth::user();
        $token = $user->createToken("auth");
       
        return response()->json(["token"=>$token],200);

    }

 


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
