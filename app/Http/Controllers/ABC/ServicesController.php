<?php

namespace App\Http\Controllers\ABC;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use App\Models\Barangay;

class ServicesController extends Controller
{
    public function index(Request $request)
{
    $query = DocumentRequest::with(['resident', 'barangay'])
        ->where('status', 'completed'); // ✅ fixed to completed only

    // 🔹 Filter by document type
    if ($request->filled('document_type')) {
        $query->where('document_type', $request->document_type);
    }

    // 🔹 Filter by barangay
    if ($request->filled('barangay')) {
        $query->where('barangay_id', $request->barangay);
    }

    // 🔹 Search by reference code or resident full name
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('reference_code', 'like', "%{$search}%")
              ->orWhereHas('resident', function($qr) use ($search) {
                  $qr->whereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name, ' ', COALESCE(suffix, '')) LIKE ?", ["%{$search}%"]);
              });
        });
    }

    $requests = $query->latest()->paginate(5);

  // ✅ Completed count with filters applied
$completedCount = DocumentRequest::where('status', 'completed')
->when($request->filled('barangay'), fn($q) => $q->where('barangay_id', $request->barangay))
->when($request->filled('document_type'), fn($q) => $q->where('document_type', $request->document_type))
->count();


    // 🔹 For the filter dropdowns
    $documentTypes = DocumentRequest::where('status', 'completed')
        ->select('document_type')
        ->distinct()
        ->pluck('document_type');

    $barangays = Barangay::all(); // ✅ para sa dropdown

    return view('abc.services.index', compact(
        'requests',
        'documentTypes',
        'completedCount',
        'barangays' // ✅ para ma-pass sa view
    ));
}

    

    public function show($id)
{
    $request = DocumentRequest::with(['resident', 'barangay'])->findOrFail($id);
    return view('abc.services.view', compact('request'));
}

}    
