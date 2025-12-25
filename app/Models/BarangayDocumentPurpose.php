<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayDocumentPurpose extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay_document_id',
        'purpose',
        'price',
    ];

    public function document()
    {
        return $this->belongsTo(BarangayDocument::class, 'barangay_document_id');
    }
}
