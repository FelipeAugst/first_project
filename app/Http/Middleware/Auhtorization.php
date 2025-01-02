<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class Auhtorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {    
         $token = explode(" ",$request->header('Authorization'))[1];

         $sql="select tokenable_id,token from personal_access_tokens where token= '$token' ";

        $results= Db::select($sql);
        if(true){
            return response()->json($results);
        }


        return $next($request);
    }
}
