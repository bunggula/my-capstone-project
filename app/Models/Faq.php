<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay_id',
        'category',
        'question',
        'answer',
    ];

    // Optional: relation sa Barangay
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
