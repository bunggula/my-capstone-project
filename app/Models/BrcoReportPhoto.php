<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrcoReportPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'brco_report_id',
        'path', 'caption'
    ];

    public function report()
    {
        return $this->belongsTo(BrcoReport::class);
    }
}
