<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use function Laravel\Prompts\password;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable

/**
      * @OA\Schema(
      *  schema="user",
      * description="user",
      * title= "user",
      *required={
      *  "name",
      *   "email",
      *   "password"
      *     },
      *   @OA\Property(   
      *   property="name",   
      *   type="string",   
      *   format="string",   
      *   description="Name of user"   
      *    ),
      *  @OA\Property(   
      *   property="email",   
      *   type="string",   
      *   format="string",   
      *   description="Email of user"   
      *    ),
      * @OA\Property(   
      *   property="password",   
      *   type="string",   
      *   format="string",   
      *   description="Password of user"   
      *    )
    *)
        
    */

{   
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
       
        
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profiles(): HasMany{
        return $this->hasMany(Profile::class);
    }

    
}
