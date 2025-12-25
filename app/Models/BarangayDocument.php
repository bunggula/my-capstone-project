<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay_id',
        'name',
        'description',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    public function purposes()
    {
        return $this->hasMany(BarangayDocumentPurpose::class);
    }
}
