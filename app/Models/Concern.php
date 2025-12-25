<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Concern extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'resident_id',
        'barangay_id',
        'title',
        'description',
        'zone',
        'street',
        'image_path',
        'status',
        'meeting_date',
    ];
    

    public function resident()
    {
        return $this->belongsTo(\App\Models\Resident::class, 'resident_id');

    }

public function barangay()
{
    return $this->belongsTo(Barangay::class, 'barangay_id');
}

}
