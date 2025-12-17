<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminDonasikuy extends Authenticatable
{
    protected $table = 'admins_donasikuy_tables';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama_admin',
        'email_admin',
        'password_admin',
    ];

    protected $hidden = [
        'password_admin',
    ];

    // Laravel default expects "password"
    public function getAuthPassword()
    {
        return $this->password_admin;
    }
}

