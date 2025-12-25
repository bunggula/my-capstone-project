<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    use HasFactory;

    protected $fillable = ['barangay_id', 'title', 'content','logo_path','price',];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
