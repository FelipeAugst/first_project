<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\User

class UserController extends Controller
{
    public function index(Request $request)
    { 
        $validator = Validator::make($request->all(), [
			'limite' => 'required', 
             'orderby'=>'required',
             'direction'=>'required']);
      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $limite= $request->input('limite');
		$orderby  = $request->input('orderby');
		$direction = $request->input('direction');

        $users= User::select('id','name','email')
                              ->orderBy($orderby,$direction)
                             ->paginate($limite);

     return response()->json($users['data'], 200);

    }
}

