<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';

    protected $fillable = [
        'program_id',
        'program_slug',
        'donor_name',
        'amount',
        'is_anonymous',
        'message',
        'status',
    ];
}
