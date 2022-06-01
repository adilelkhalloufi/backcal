<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCal extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [
        'cal_id', 'Nom', 'description', 'date_debut', 'date_fin', 'user_id'
    ];
}
