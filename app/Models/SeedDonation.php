<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeedDonation extends Model
{
    protected $fillable = [
        'user_id',
        'program_slug',
        'program_title',
        'program_image',
        'program_category',
        'amount',
        'status',
        'is_anonymous',
        'donor_name',
        'message',
    ];
}
