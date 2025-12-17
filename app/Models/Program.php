<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    const STATUS_DRAFT     = 'draft';
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_RUNNING   = 'running';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED   = 'expired';
    const STATUS_SUSPENDED = 'suspended';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'short_description',
        'image',
        'target',
        'category',
        'slug',
        'deadline',
        'status',
        'is_active',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function getRaisedAttribute()
    {

        if (! method_exists($this, 'donations')) {
            return 0;
        }

        return (int) $this->donations()
            ->whereIn('status', ['settlement', 'capture'])
            ->sum('amount');

    }

    public function getIsUnlimitedAttribute(): bool
    {
        return (int) ($this->target ?? 0) <= 0;
    }

    public function getProgressAttribute(): int
    {
        if ($this->is_unlimited) {
            return 0;
        }

        if ((int) $this->target <= 0) {
            return 0;
        }

        return min(
            100,
            (int) round(($this->raised / max(1, $this->target)) * 100)
        );
    }

    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_RUNNING,
        ]);
    }

    public function isActive()
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_RUNNING,
        ]);
    }

    public function updates()
    {
        return $this->hasMany(\App\Models\CampaignUpdate::class, 'program_id');
    }

    public function disbursements()
    {
        return $this->hasMany(\App\Models\DisbursementRequest::class, 'program_id');
    }


}
