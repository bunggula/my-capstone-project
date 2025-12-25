<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlotterRespondent extends Model
{
    use HasFactory;

    protected $fillable = [
        'blotter_id',
        'first_name',
        'middle_name',
        'last_name',
        'resident_id'
    ];

    public function blotter()
    {
        return $this->belongsTo(Blotter::class);
    }
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->resident) {
            return trim($this->resident->first_name . ' ' . 
                        ($this->resident->middle_name ? $this->resident->middle_name . ' ' : '') . 
                        $this->resident->last_name);
        }

        return trim($this->first_name . ' ' . 
                    ($this->middle_name ? $this->middle_name . ' ' : '') . 
                    $this->last_name);
    }

}
