<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class DocumentRequestController extends Controller
{
    public function index()
    {
        return DocumentRequest::with('resident')->get();
    }


    public function store(Request $request)
    {
        $user = Auth::user();
    
        if (!$user || !$user->barangay_id) {
            return response()->json(['message' => 'Unauthorized or invalid user.'], 403);
        }
    
        if ((int) $request->barangay_id !== (int) $user->barangay_id) {
            return response()->json(['message' => 'You can only request documents from your own barangay.'], 403);
        }
    
        // Clean document title
        $request->merge([
            'document_type' => $request->input('title')
        ]);
    
        // Validate request
        $request->validate([
            'document_type' => 'required|string',
            'purpose' => 'nullable|string',
            'price' => 'nullable|numeric',
            'barangay_id' => 'required|integer',
            'form_data' => 'nullable|array',
            'force' => 'nullable|boolean',
        ]);
    
        // 🔍 Check for duplicate request only if NOT forced
        $existingRequest = DocumentRequest::where('resident_id', $user->id)
            ->where('document_type', $request->document_type)
            ->whereIn('status', ['pending', 'approved', 'ready_for_pickup'])
            ->latest()
            ->first();
    
        if ($existingRequest && !$request->boolean('force')) {
            return response()->json([
                'message' => 'Duplicate request exists',
                'duplicate' => true,
                'last_request' => [
                    'reference_code' => $existingRequest->reference_code,
                    'status' => $existingRequest->status,
                    'created_at' => $existingRequest->created_at,
                ]
            ], 409); // 409 Conflict
        }
    
        // Generate unique reference code
        do {
            $referenceCode = 'BRGY-' . strtoupper(Str::random(6));
        } while (DocumentRequest::where('reference_code', $referenceCode)->exists());
    
        // Save request
        $document = DocumentRequest::create([
            'resident_id' => $user->id,
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
            'price' => $request->price,
            'barangay_id' => $user->barangay_id,
            'status' => 'pending',
            'form_data' => $request->form_data,
            'reference_code' => $referenceCode,
          'is_custom_purpose' => (bool) $request->input('is_custom_purpose', false),



        ]);
    
        return response()->json([
            'message' => 'Request created successfully',
            'reference_code' => $referenceCode,
            'data' => $document,
        ], 201);
    }
    
    

    public function show($id)
    {
        return DocumentRequest::with('resident')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $document = DocumentRequest::findOrFail($id);
        $document->update($request->only(['status', 'pickup_date']));
        return response()->json($document);
    }

    public function destroy($id)
    {
        DocumentRequest::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
    private function generateReferenceCode()
{
    do {
        $code = 'BRGY-' . strtoupper(uniqid());
    } while (DocumentRequest::where('reference_code', $code)->exists());

    return $code;
}
// Add this if not yet added
public function userHistory()
{
    Log::info('🔥 userHistory() endpoint was hit');

    $user = Auth::user();
    if (!$user) {
        Log::warning('⚠️ Unauthorized access to userHistory');
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $history = DocumentRequest::with('resident')
        ->where('resident_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    Log::info('✅ Returning history', ['count' => $history->count()]);

    return response()->json($history);
}

}
