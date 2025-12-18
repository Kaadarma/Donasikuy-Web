<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id','title','slug','short_description','description',
        'image','banner','target','raised','deadline','status','approved_at','approved_by'
    ];
    
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
