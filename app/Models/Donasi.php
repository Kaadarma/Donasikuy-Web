<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi_tables';
    protected $primaryKey = 'id_donasi';

    protected $fillable = [
        'id_user',
        'id_kampanye',
        'jumlah_donasi',
        'metode_pembayaran',
        'status_donasi',
        'catatan',
    ];
}
