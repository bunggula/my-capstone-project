<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['filename', 'filepath', 'barangay_id']; // ✅ Add barangay_id

    // Optional: If you want to define the relationship to Barangay
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
