<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay',
        'title',
        'description',
        'image',
        'file',
        'status',
        'source_of_fund',
        'target_date',
        'rejection_reason',
        'captain_id',
        'submitted_by_type',
    ];

    // Get related barangay
    public function barangayInfo()
    {
        return $this->belongsTo(\App\Models\Barangay::class, 'barangay');
    }

    // Get barangay captain via barangay
    public function captain()
    {
        return $this->hasOne(\App\Models\User::class, 'barangay_id', 'barangay')
                    ->where('role', 'brgy_captain');
    }
   
public function getFullNameAttribute()
{
    return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
}

// Proposal.php
public function submittedBy()
{
    return $this->morphTo(__FUNCTION__, 'submitted_by_type',);
}
public function getSkChairmanNameAttribute()
{
    $sk = \App\Models\BarangayOfficial::where('barangay_id', $this->barangay)
        ->where('position', 'SK Chairman')
        ->first();

    return $sk 
        ? trim("{$sk->first_name} {$sk->middle_name} {$sk->last_name} {$sk->suffix}") 
        : 'N/A';
}
public function barangay()
{
    return $this->belongsTo(Barangay::class);
}

}
   
