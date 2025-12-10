<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'image',
        'target',
        'raised',
        'category',
        'slug',
    ];
}
