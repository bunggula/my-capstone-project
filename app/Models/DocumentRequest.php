<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'document_type',
        'purpose',
        'barangay_id',
        'status',
        'price',
        'form_data', // ✅ DITO
        'reference_code',
        'rejection_reason',
        'is_paid',
        'is_custom_purpose',
        'type'
    ];

    protected $casts = [
        'form_data' => 'array', // ✅ PARA MA-CONVERT AS JSON
        'pickup_date' => 'datetime',    // cast pickup_date to Carbon instance
        'created_at' => 'datetime',     // cast created_at to Carbon instance
        'updated_at' => 'datetime', 
        'is_paid' => 'boolean', 
        'is_custom_purpose' => 'boolean',// if you have updated_at too
    ];

    public function resident()
    {
        return $this->belongsTo(\App\Models\Resident::class, 'resident_id');
    }
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }

}
