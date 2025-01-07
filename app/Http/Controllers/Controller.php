<?php

namespace App\Http\Controllers;


/** 
 * 
 * @OA\Info(
 *   title= "First Laravel API", 
 *    description= "Simple Laravel Api who manage users and associated profiles",
 *    version="0.0.1",
 *   
 *        )
 *  @OA\SecurityScheme(
 *    securityScheme= "bearerAuth",
 *    in="header",
 *     description="Token to authenticate users",
 *     type="http",
 *    scheme="Bearer",
 *  
 *    
 *  )
 * @OA\Servers(
 *   url="http://localhost:8000/api
 *  )
 *  
 * 
 *  
 *  
 * 
 *   
 *   
 * 
 */

abstract class Controller
{
    //
}
