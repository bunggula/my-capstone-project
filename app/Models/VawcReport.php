<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VawcReport extends Model
{
    protected $fillable = [
        'barangay_id',
        'period_start',
        'period_end',
        'total_clients_served',
        'total_cases_received',
        'total_cases_acted',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    // Relationships
    public function cases()
    {
        return $this->hasMany(VawcReportCase::class);
    }

    public function programs()
    {
        return $this->hasMany(VawcReportProgram::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
    
}
