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
        'category',
        'slug',
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
}
