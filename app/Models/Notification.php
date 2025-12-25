<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'type',     // e.g., 'event_approved', 'event_rejected'
        'is_read',  // optional, kung gusto mong i-track kung nabasa na
    ];
}
