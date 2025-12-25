<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'description',
        'barangay_id',    // for filtering per captain/barangay
        'signed_file',    // path of uploaded signed blotter
        'is_signed',      // boolean flag if signed
        'signed_at',      // timestamp when uploaded
    ];

    public function complainants()
    {
        return $this->hasMany(BlotterComplainant::class);
    }

    public function respondents()
    {
        return $this->hasMany(BlotterRespondent::class);
    }
}
