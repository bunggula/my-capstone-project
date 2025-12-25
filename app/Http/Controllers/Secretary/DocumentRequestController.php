<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use App\Models\User;
use App\Models\BarangayOfficial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentApproved;
use App\Mail\DocumentRejected;
use App\Models\BarangayDocumentPurpose; 
class DocumentRequestController extends Controller
{
    /**
     * Display a listing of document requests for the secretary's barangay.
     */
public function index(Request $request)
{
    $user = Auth::user();
    $search = $request->input('search');

    // Base query para sa table
    $query = DocumentRequest::with('resident')
        ->where('barangay_id', $user->barangay_id);

    // Base query para sa counts
    $countQuery = DocumentRequest::with('resident')
        ->where('barangay_id', $user->barangay_id);

    // Apply search filter kung may input
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('resident', function ($sub) use ($search) {
                $sub->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            })
            ->orWhere('reference_code', 'LIKE', "%{$search}%")
            ->orWhere('document_type', 'LIKE', "%{$search}%");
        });

        $countQuery->where(function ($q) use ($search) {
            $q->whereHas('resident', function ($sub) use ($search) {
                $sub->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            })
            ->orWhere('reference_code', 'LIKE', "%{$search}%")
            ->orWhere('document_type', 'LIKE', "%{$search}%");
        });
    }

    // Determine current status
    $currentStatus = $request->status ?? null;

    if (!$currentStatus && $search) {
        $firstMatch = $query->first();
        $currentStatus = $firstMatch ? $firstMatch->status : 'pending';
    }

    $currentStatus = $currentStatus ?? 'pending';

    // Apply status filter sa table query
    $query->where('status', $currentStatus);

    // Apply document type filter kung may napili
    if ($request->filled('document_type')) {
        $query->where('document_type', $request->input('document_type'));
        $countQuery->where('document_type', $request->input('document_type'));
    }

    // Kunin ang filtered counts para sa summary cards
    $counts = [
        'pending' => (clone $countQuery)->where('status', 'pending')->count(),
        'approved' => (clone $countQuery)->where('status', 'approved')->count(),
        'rejected' => (clone $countQuery)->where('status', 'rejected')->count(),
        'ready_for_pickup' => (clone $countQuery)->where('status', 'ready_for_pickup')->count(),
        'completed' => (clone $countQuery)->where('status', 'completed')->count(),
    ];

    // Pag-paginate ng results
    // Pending -> order by created_at
    // Ibang status -> order by updated_at
    if ($currentStatus === 'pending') {
        $requests = $query->orderBy('created_at', 'desc')
                          ->paginate(10)
                          ->withQueryString();
    } else {
        $requests = $query->orderBy('updated_at', 'desc')
                          ->paginate(10)
                          ->withQueryString();
    }

    // Kunin ang distinct document types para sa dropdown filter
    $documentTypes = DocumentRequest::where('barangay_id', $user->barangay_id)
        ->distinct()
        ->pluck('document_type');

    return view('secretary.document_requests.index', compact(
        'requests',
        'documentTypes',
        'counts',
        'currentStatus',
        'search'
    ));
}


    
    /**
     * Show a single document request details.
     */
    public function show($id)
    {
        $user = Auth::user();

        $request = DocumentRequest::with('resident')
            ->where('id', $id)
            ->where('barangay_id', $user->barangay_id)
            ->firstOrFail();

        return view('secretary.document_requests.show', compact('request'));
    }

    /**
     * Generate a printable view of the document request.
     */
    public function print($id)
    {
        $request = DocumentRequest::with('resident.barangay')->findOrFail($id);
    
        if (!in_array($request->status, ['approved', 'ready_for_pickup'])) {
            return redirect()->back()->with('error', 'Hindi pa pwedeng i-print ang dokumentong ito.');
        }
    
        $resident = $request->resident; 
        $barangayId = $resident->barangay_id;
        $barangay = $resident->barangay; 
        $yearNow = now()->year;
    
        // Barangay Captain (active only)
        $captain = User::where('role', 'brgy_captain')
                       ->where('barangay_id', $barangayId)
                       ->where('status', 'active')
                       ->first();
    
        // Barangay Secretary (active only)
        $secretary = User::where('role', 'secretary')
                         ->where('barangay_id', $barangayId)
                         ->where('status', 'active')
                         ->first();
    
        // Kagawads (valid term only)
        $kagawads = BarangayOfficial::where('barangay_id', $barangayId)
                                    ->where('position', 'Kagawad')
                                    ->where('start_year', '<=', $yearNow)
                                    ->where('end_year', '>=', $yearNow)
                                    ->orderBy('id')
                                    ->get();
    
        // SK Chairman (valid term only)
        $skChairman = BarangayOfficial::where('barangay_id', $barangayId)
                                      ->where('position', 'SK Chairman')
                                      ->where('start_year', '<=', $yearNow)
                                      ->where('end_year', '>=', $yearNow)
                                      ->first();
    
        // Clean up document_type to match your blade file name
        $viewName = strtolower(str_replace(['.blade', '.php', ' '], ['', '', '_'], $request->document_type));
        $bladeView = "secretary.documents.$viewName";
    
        if (!view()->exists($bladeView)) {
            return back()->with('error', "Template [$request->document_type] not found.");
        }
    
        return view($bladeView, compact(
            'request',
            'resident',
            'barangay',
            'captain',
            'secretary',
            'kagawads',
            'skChairman'
        ));
    }
    
    
    
    /**
     * Update status of a document request.
     */
    public function updateStatus(Request $request, $id, $status)
    {
        $validStatuses = ['approved', 'rejected', 'ready_for_pickup'];
    
        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Invalid status.');
        }
    
        $documentRequest = DocumentRequest::with('resident')->findOrFail($id);
        $documentRequest->status = $status;
    // ✅ Update price if sent
   if ($request->filled('price')) {
        $documentRequest->price = $request->input('price');
    }
        
        if ($status === 'rejected') {
            $reason = $request->input('rejection_reason');
            if (!$reason) {
                return back()->with('error', 'Rejection reason is required.');
            }
            $documentRequest->rejection_reason = $reason;
    
            // Optional: send rejection email
            if ($documentRequest->resident && $documentRequest->resident->email) {
                $secretary = User::where('role', 'Secretary')
                    ->where('barangay_id', $documentRequest->barangay_id)
                    ->first();
    
                Mail::to($documentRequest->resident->email)->send(
                    new DocumentRejected($documentRequest, $secretary)
                );
            }
        }
    
        if ($status === 'approved' && $documentRequest->resident && $documentRequest->resident->email) {
            $secretary = User::where('role', 'Secretary')
                ->where('barangay_id', $documentRequest->barangay_id)
                ->first();
    
            Mail::to($documentRequest->resident->email)->send(
                new DocumentApproved($documentRequest, $secretary)
            );
        }
    
        $documentRequest->save();
    
        return back()->with('success', 'Document Request ' . ucfirst($status));
    }
    
  


    public function edit($id)
{
    $user = Auth::user();

    $request = DocumentRequest::where('id', $id)
        ->where('barangay_id', $user->barangay_id)
        ->firstOrFail();

    return view('secretary.document_requests.edit', compact('request'));
}
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'ctc_number' => 'nullable|string|max:255',
        'receipt_number' => 'nullable|string|max:255',
        'price' => 'nullable|numeric|min:0',
        'control_number' => 'nullable|string|max:255',
    ]);

    $documentRequest = DocumentRequest::where('id', $id)
        ->where('barangay_id', Auth::user()->barangay_id)
        ->firstOrFail();

    $documentRequest->update($validated);

    return redirect()->route('secretary.document_requests.print', $documentRequest->id)
        ->with('success', 'Document details updated successfully.');
}
public function approved()
{
    $barangayId = auth()->user()->barangay_id;

    $approvedDocuments = DocumentRequest::with('resident')
        ->where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->latest()
        ->get();

    return view('secretary.services.approved', compact('approvedDocuments'));
}
public function rejected()
{
    $rejectedDocuments = DocumentRequest::with('resident')
        ->where('status', 'rejected')
        ->where('barangay_id', auth()->user()->barangay_id)
        ->latest()
        ->get();

    return view('secretary.services.rejected', compact('rejectedDocuments'));
}
public function markAsCompleted($id)
{
    $request = DocumentRequest::findOrFail($id);

    if ($request->status === 'approved' || $request->status === 'ready_for_pickup') {
        $request->status = 'completed';
        $request->completed_at = now(); // optional
        $request->is_paid = true;       // mark as paid
        $request->save();

        return redirect()->back()->with('success', 'Document request marked as completed and paid.');
    }

    return redirect()->back()->with('error', 'Only approved requests can be marked as completed.');
}

public function completed(Request $request)
{
    $barangayId = auth()->user()->barangay_id;

    $requests = DocumentRequest::where('status', 'completed')
        ->whereHas('resident', function ($query) use ($barangayId) {
            $query->where('barangay_id', $barangayId);
        })
        ->orderByDesc('updated_at')
        ->get();

    return view('secretary.document_requests.completed', compact('requests'));
}
public function store(Request $request)
{
    // Validate required fields
    $request->validate([
        'resident_id' => 'required|exists:residents,id',
        'document_id' => 'required|exists:barangay_documents,id',
        // purpose_id is optional if "other" is selected
    ]);

    $document = \App\Models\BarangayDocument::findOrFail($request->document_id);

    // Determine if it's a custom purpose
    $isCustom = $request->purpose_id === 'other';

    // Get purpose name
    $purposeName = $isCustom 
        ? $request->other_purpose // value from input field
        : \App\Models\BarangayDocumentPurpose::findOrFail($request->purpose_id)->purpose;

    // Get price (0 for custom)
    $price = $isCustom ? 0 : \App\Models\BarangayDocumentPurpose::findOrFail($request->purpose_id)->price;

    // Collect additional form fields
    $formData = $request->input('form_data', []);
    $formData['purpose'] = $purposeName;
    $formData['price'] = $price;
// Determine status based on custom purpose
$status = $isCustom ? 'pending' : 'approved';

$documentRequest = \App\Models\DocumentRequest::create([
    'resident_id'      => $request->resident_id,
    'document_id'      => $request->document_id,
    'document_type'    => $document->name,
    'purpose'          => $purposeName,
    'purpose_id'       => $isCustom ? null : $request->purpose_id,
    'is_custom_purpose'=> $isCustom,
    'status'           => $status,  // ✅ dito
    'barangay_id'      => auth()->user()->barangay_id,
    'price'            => $price,
    'form_data'        => $formData,
    'reference_code'   => 'BRGY-' . strtoupper(substr(sha1(now()), 0, 6)),
]);

    return back()->with('success', 'Document request successfully created.');
}




public function getPurposes($documentId)
{
    $purposes = BarangayDocumentPurpose::where('barangay_document_id', $documentId)
        ->select('id', 'purpose', 'price') // select only the fields you need
        ->get();

    return response()->json($purposes);
}
public function getFields($id)
{
    $document = \App\Models\BarangayDocument::findOrFail($id);

    $fields = []; // same switch/case as your mobile API

    switch ($document->name) {
        case 'Certificate of Residency':
            $fields = [
                ['key' => 'years_of_residency', 'label' => 'Years of Residency', 'type' => 'number'],
                ['key' => 'months_of_residency', 'label' => 'Months of Residency', 'type' => 'number'],
            ];
            break;

        case 'Business Clearance':
            $fields = [
                ['key' => 'business_name', 'label' => 'Business Name', 'type' => 'text'],
                ['key' => 'start_date', 'label' => 'Business Start Date', 'type' => 'date'],
                ['key' => 'end_date', 'label' => 'Business End/Renewal Date', 'type' => 'date'],
            ];
            break;

        case 'Proof of Income':
            $fields = [
                ['key' => 'occupation', 'label' => 'Occupation', 'type' => 'text'],
                ['key' => 'monthly_income', 'label' => 'Monthly Income', 'type' => 'number'],
            ];
            break;

        case 'First time Job Seeker':
            $fields = [
                ['key' => 'years_of_living', 'label' => 'Years of Living', 'type' => 'number'],
            ];
            break;

        case 'Electrical Clearance': // ⚡ new case
            $fields = [
                ['key' => 'start_date', 'label' => 'Start Date of Installation', 'type' => 'date'],
                ['key' => 'end_date', 'label' => 'End Date of Installation', 'type' => 'date'],
            ];
            break;
case 'Certification of Late Registration':
$fields = [
    ['key' => 'birth_place', 'label' => 'Place of Birth (Municipality/Province)', 'type' => 'text'],
    ['key' => 'father_name', 'label' => "Father's Full Name", 'type' => 'text'],
    ['key' => 'mother_name', 'label' => "Mother's Full Name (Maiden Name)", 'type' => 'text'],
];
break;

case 'Solo Parent Certification':
$fields = [
    ['key' => 'child_name', 'label' =>'Full Name(s) of Child/Children', 'type' => 'text'],
    ['key' => 'since_date', 'label' => 'Solo Parent Since (Date)', 'type' => 'date', 'max' => now()->format('Y-m-d')],
];
break;

        default:
            $fields = [];
    }

    return response()->json(['fields' => $fields]);
}

}