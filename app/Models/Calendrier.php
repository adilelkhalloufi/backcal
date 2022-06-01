<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendrier extends Model
{
    use HasFactory;
    protected $table = 'calendries';
    protected $fillable = [
        'user_id', 'nbr_events', 'title', 'timezone',
    ];

    public function EventsCounts()
    {
        return $this->hasMany(EventCal::class, 'cal_id');
    }
}
