<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable= ['user_id','profile'];


    public function users(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}