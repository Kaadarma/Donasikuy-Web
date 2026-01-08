<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementRequest extends Model
{

    const STATUS_REQUESTED = 'requested';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_PAID      = 'paid';

    protected $fillable = [
        'program_id', 'user_id', 'amount', 'note', 'status',
        'bank_name', 'account_name', 'account_number', 'paid_at', 'payment_proof', 'admin_note',

    ];

    public function program() { return $this->belongsTo(Program::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function items()
    {
    return $this->hasMany(\App\Models\DisbursementItem::class, 'disbursement_request_id');
    }

    

}


