<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    // pakai tabel & primary key default: users, id (tak perlu didefinisikan)

    public $timestamps = true;

    protected $fillable = [
        'name', 'level', 'email', 'password', 'google_id', 'email_verified_at', 'phone', 'foto_profil'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // otomatis hash saat set password
    public function setPasswordAttribute($value)
    {
        // jika sudah hash, biarkan
        $this->attributes['password'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }

    public function kycSubmission()
    {
    return $this->hasOne(\App\Models\KycSubmission::class);
    }
}

