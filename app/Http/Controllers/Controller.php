<?php

namespace App\Http\Controllers;


/** 
 * 
 * @OA\Info(
 *   title= "First Laravel API", 
 *    description= "Simple Laravel Api who manage users and associated profiles",
 *    version="0.0.1",
 *        )
 * 
 * @OA\Servers(
 *   url="http://localhost:8000/api
 *  )
 *  
 *  @OA\SecurityScheme(
 *    securityScheme= "bearerAuth",
 *     description="Token to authenticate users"
 *     type="http",
 *    scheme="bearer"
 *  
 *    
 *  )
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
