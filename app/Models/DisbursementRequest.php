<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementRequest extends Model
{
    protected $fillable = ['program_id', 'user_id', 'amount', 'note', 'status'];

    public function program() { return $this->belongsTo(Program::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function items()
    {
    return $this->hasMany(\App\Models\DisbursementItem::class, 'disbursement_request_id');
    }

    

}


