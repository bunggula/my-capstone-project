<?php

namespace App\Http\Controllers\Secretary;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;  // Don't forget to import Carbon at the top
use App\Models\BarangayDocument;
class ServicesReportController extends Controller
{
    public function index(Request $request)
{
    $documentType = $request->input('document_type');
    $dateFilter   = $request->input('date_filter');
    $barangayId   = Auth::user()->barangay_id;

    $query = DocumentRequest::where('status', 'completed')
        ->whereNotNull('completed_at') // ✅ siguraduhin may completed_at
        ->where('barangay_id', $barangayId)
        ->with('resident');

    if ($documentType) {
        $query->where('document_type', $documentType);
    }

    // 🔎 Daily filter
    if ($dateFilter === 'daily' && $request->filled('daily_date')) {
        $query->whereDate('completed_at', $request->daily_date);
    }

    // 🔎 Weekly filter
    if ($dateFilter === 'weekly' && $request->filled(['start_date', 'end_date'])) {
        $query->whereBetween('completed_at', [
            $request->start_date,
            $request->end_date,
        ]);
    }

    // 🔎 Monthly filter
    if ($dateFilter === 'monthly' && $request->filled('month')) {
        $month = \Carbon\Carbon::parse($request->month);
        $query->whereMonth('completed_at', $month->month)
              ->whereYear('completed_at', $month->year);
    }

    $completedRequests = $query->latest()->get();

    return view('secretary.reports.services', compact('completedRequests', 'documentType'));
}

    
    
public function services(Request $request)
{
    $barangayId  = Auth::user()->barangay_id;
    $inputType   = $request->input('document_type');
    $dateFilter  = $request->input('date_filter');

    // Base query: completed requests
    $query = DocumentRequest::where('status', 'completed')
        ->whereNotNull('completed_at')
        ->where('barangay_id', $barangayId)
        ->with('resident');

    // 🔎 Filter by document type
    if (!empty($inputType)) {
        $query->where('document_type', $inputType);
    }

    // 🔎 Apply date filter
    if ($dateFilter === 'daily' && $request->filled('daily_date')) {
        $query->whereDate('completed_at', $request->daily_date);
    } elseif ($dateFilter === 'weekly' && $request->filled(['start_date', 'end_date'])) {
        $query->whereBetween('completed_at', [
            $request->start_date,
            $request->end_date,
        ]);
    } elseif ($dateFilter === 'monthly' && $request->filled('month')) {
        $month = \Carbon\Carbon::parse($request->month);
        $query->whereYear('completed_at', $month->year)
              ->whereMonth('completed_at', $month->month);
    }

    // Paginate results
    $completedRequests = $query->orderBy('completed_at', 'desc')->paginate(10)->withQueryString();

    // 🔹 Document types from master table
    $documentTypes = BarangayDocument::where('barangay_id', $barangayId)
                        ->pluck('name');

    return view('secretary.reports.services', [
        'completedRequests' => $completedRequests,
        'documentType'      => $inputType,
        'documentTypes'     => $documentTypes,
        'dateFilter'        => $dateFilter,
    ]);
}

    
public function print(Request $request)
{
    $barangayId  = Auth::user()->barangay_id;
    $inputType   = $request->input('document_type');
    $dateFilter  = $request->input('date_filter');

    $documentTypeMap = [
        'certificate_of_residency'  => ['Certificate Of Residency', 'Barangay Certificate Of Residency'],
        'barangay_clearance'        => ['Clearance', 'Barangay Clearance'],
        'certificate_of_indigency'  => ['Certificate Of Indigency', 'Barangaya Certificate Of Indigency'],
        'certificate_of_good_moral' => ['Certificate Of Good Moral'],
        'first_time_jobseeker'      => ['First Time Jobseeker', 'First time Job Seeker'],
        'proof_of_income'           => ['Proof Of Income', 'Proof of Income'],
        'business_clearance'        => ['Business Clearance'],
        'certification'             => ['Certification'],
        'electrical_clearance'      => ['Electrical Clearance'],
    ];

    $query = DocumentRequest::where('status', 'completed')
        ->whereNotNull('completed_at')
        ->where('barangay_id', $barangayId)
        ->with('resident');

    // 🔎 Filter by document type
    if (!empty($inputType)) {
        $variants = $documentTypeMap[$inputType] ?? [$inputType];
        $query->whereIn('document_type', $variants);
    }

    // 🔎 Apply date filter
    if ($dateFilter === 'daily' && $request->filled('daily_date')) {
        $query->whereDate('completed_at', $request->daily_date);
    }

    if ($dateFilter === 'weekly' && $request->filled(['start_date', 'end_date'])) {
        $query->whereBetween('completed_at', [
            $request->start_date,
            $request->end_date,
        ]);
    }

    if ($dateFilter === 'monthly' && $request->filled('month')) {
        $month = \Carbon\Carbon::parse($request->month);
        $query->whereYear('completed_at', $month->year)
              ->whereMonth('completed_at', $month->month);
    }

    $completedRequests = $query->orderBy('completed_at', 'desc')->get();

    $secretary = User::where('barangay_id', $barangayId)->where('role', 'secretary')->first();
    $captain   = User::where('barangay_id', $barangayId)->where('role', 'brgy_captain')->first();

    return view('secretary.reports.services-print', [
        'completedRequests' => $completedRequests,
        'documentType'      => $inputType,
        'dateFilter'        => $dateFilter,
        'dailyDate'         => $request->daily_date,
        'startDate'         => $request->start_date,
        'endDate'           => $request->end_date,
        'month'             => $request->month,
        'secretaryName'     => optional($secretary)->full_name ?? '__________',
        'captainName'       => optional($captain)->full_name ?? '__________',
    ]);
}


}