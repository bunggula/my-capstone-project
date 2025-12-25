<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    protected $fillable = [
        'date_collected',
        'biodegradable',
        'recyclable',
        'residual_recyclable',
        'residual_disposal',
        'special',
        'remarks',
        'barangay_id',
    ];
}

