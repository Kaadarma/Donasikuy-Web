<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegistration extends Model
{
    protected $fillable = [
        'name','email','password_hash','token_hash','expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
