<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;

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

        $users= \App\Models\User::select('id','name','email')
                              ->orderBy($orderby,$direction)
                             ->paginate($limite);

    // $sql = "select id, name,email from users order by $orderby";

    
// users = DB::select($sql);




     return response()->json($users, 200);

    }
}

$query= DB::connection('mysql')
			->table('parcelas')
			->select(
					 'parcelas.id',
                    'parcelas.contrato_id',
                    'parcelas.galaxPayId',
                    'parcelas.valor',
                    'parcelas.nparcela',
                    'parcelas.data_vencimento',
                    'parcelas.data_pagamento',
                    'parcelas.data_baixa',
                    'parcelas.valor_pago',
                    'parcelas.boletobankNumber',
                    'parcelas.statusDescription'
                                                                )
            ->where('parcelas.contrato_id','=',$contrato_id);
        $query->orderBy($orderby,$direction);
		
        $parcelas = $query->paginate($limite);