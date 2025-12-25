<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VawcReportCase extends Model
{
    protected $fillable = [
        'vawc_report_id',
        'nature_of_case',
        'subcategory',
        'num_victims',
        'num_cases',
        'ref_cmswdo',
        'ref_pnp',
        'ref_court',
        'ref_hospital',
        'ref_others',
        'num_applied_bpo',
        'num_bpo_issued',
    ];

    protected $casts = [
        'num_victims' => 'integer',
        'num_cases' => 'integer',
        'ref_cmswdo' => 'integer',
        'ref_pnp' => 'integer',
        'ref_court' => 'integer',
        'ref_hospital' => 'integer',
        'ref_others' => 'integer',
        'num_applied_bpo' => 'integer',
        'num_bpo_issued' => 'integer',
    ];

    public function report()
    {
        return $this->belongsTo(VawcReport::class);
    }
}
