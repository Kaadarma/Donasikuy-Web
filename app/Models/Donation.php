<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';

    protected $fillable = [
        'user_id',
        'program_id',
        'program_slug',
        'donor_name',
        'amount',
        'is_anonymous',
        'message',
        'status', // contoh: pending, success, failed, expired
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'amount' => 'integer',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Program
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Scope: donasi yang dianggap masuk ke "raised"
     * Sesuaikan kalau status kamu beda (mis. 'settlement')
     */
    public function scopePaid($query)
    {
        return $query->whereIn('status', ['success', 'settlement', 'capture']);
    }

    /**
     * Helper: cek apakah donasi sukses
     */
    public function getIsPaidAttribute(): bool
    {
        return in_array($this->status, ['success', 'settlement', 'capture'], true);
    }

    //relasi ke event
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
