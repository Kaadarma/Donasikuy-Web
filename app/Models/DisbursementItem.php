<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisbursementItem extends Model
{
    protected $fillable = [
        'disbursement_request_id',
        'title',
        'amount',
        'note',
    ];

    public function disbursementRequest()
    {
        return $this->belongsTo(DisbursementRequest::class, 'disbursement_request_id');
    }
}

