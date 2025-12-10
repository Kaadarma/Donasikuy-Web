<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',

        // step 1
        'account_type',
        'entity_name',
        'entity_email',
        'entity_address',

        // identitas
        'full_name',
        'nik',
        'phone',
        'address',
        'id_card_path',
        'selfie_path',
        'profile_photo_path',

        // rekening
        'bank_name',
        'account_number',
        'account_name',
        'book_photo_path',

        // status
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
