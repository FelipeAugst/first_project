<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
     /**
      * @OA\Schema(
      *  schema="profile",
      * description="profile schema",
      * title= "profile",
      *required={
      *  "user_id",
      *   "profile"
      *     },
      *   @OA\Property(   
      *   property="user_id",   
      *   type="integer",   
      *   format="int32",   
      *   description="Id of the user who own the profile"   
      *    ),
      *  @OA\Property(   
      *   property="profile",   
      *   type="string",   
      *   format="string",   
      *   description="Type of profile"   
      *    ) 
    *)
        
    */

    protected $fillable= ['user_id','profile'];


    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
