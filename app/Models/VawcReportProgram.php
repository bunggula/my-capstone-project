<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VawcReportProgram extends Model
{
    protected $fillable = [
        'vawc_report_id','ppa_type','title','remarks'
    ];

    public function report()
    {
        return $this->belongsTo(VawcReport::class, 'vawc_report_id');
    }
}
