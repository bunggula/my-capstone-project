<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\DocumentRequest;
use App\Models\BarangayDocument;

class DocumentController extends Controller
{
    use AuthorizesRequests;

   public function index(Request $request)
{
$barangayId = auth()->user()->barangay_id;
$search = $request->query('search');
$docTypeFilter = $request->query('document_type');
$month = $request->query('month');
$year = $request->query('year');


// ✅ Base query for completed requests
$query = DocumentRequest::with('resident')
    ->where('barangay_id', $barangayId)
    ->where('status', 'completed');

// 🔍 Search filter
if ($search) {
    $query->where(function ($q) use ($search) {
        $q->where('reference_code', 'like', "%{$search}%")
            ->orWhere('document_type', 'like', "%{$search}%")
            ->orWhereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
    });
}

// 📄 Document type filter
if ($docTypeFilter) {
    $query->where('document_type', $docTypeFilter);
}

// 🗓 Month filter (based on created_at)
if ($month) {
    $query->whereMonth('created_at', $month);
}

// 📅 Year filter (based on created_at)
if ($year) {
    $query->whereYear('created_at', $year);
}

// 📑 Paginate results
$requests = $query->orderBy('updated_at', 'desc')
                 ->paginate(10)
                 ->withQueryString();


// 📋 Get document types from barangay_documents table
$documentTypes = BarangayDocument::where('barangay_id', $barangayId)->pluck('name');

// 📊 Counts (completed only, filtered)
$counts = [
    'completed' => (clone $query)->count(),
];

// ✅ Return the view
return view('captain.documents.index', [
    'requests' => $requests,
    'counts' => $counts,
    'documentTypes' => $documentTypes,
    'docTypeFilter' => $docTypeFilter,
    'search' => $search,
    'month' => $month,
    'year' => $year,
]);
}

}

