<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Report;

class CaptainReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->get();
        return view('captain.reports.index', compact('reports'));
    }
}