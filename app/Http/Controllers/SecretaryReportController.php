<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class SecretaryReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->get();
        return view('secretary.reports.reports', compact('reports'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'report_file' => 'required|file|max:10240',
        ]);

        $file = $request->file('report_file');
        $path = $file->store('reports', 'public');

        $report = Report::create([
            'filename' => $file->getClientOriginalName(),
            'filepath' => $path,
        ]);

        return redirect()->route('secretary.reports.index')->with('success', 'Report uploaded successfully!');
    }
    

}
