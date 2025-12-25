<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangayDocument;
use Illuminate\Support\Facades\Auth;
use App\Models\BarangayDocumentPurpose;


class BarangayDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
    
        // Get documents for the secretary's barangay with purposes
        $documents = BarangayDocument::where('barangay_id', $user->barangay_id)
            ->with('purposes')
            ->paginate(5);
    
        // Total documents for the card
        $totalDocuments = $documents->total();
    
        return view('captain.manage.index', compact('documents', 'totalDocuments'));
    }
    
    
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'purposes.*.name' => 'required|string|max:255',
        'purposes.*.price' => 'required|numeric|min:0',
    ]);

    $barangayId = Auth::user()->barangay_id;

    // 🔒 Check if a document with the same name already exists in the barangay
    $existing = BarangayDocument::where('barangay_id', $barangayId)
                                ->where('name', $request->name)
                                ->first();

    if ($existing) {
        return redirect()
            ->back()
            ->with('error', 'A document with this name already exists in your barangay.')
            ->withInput();
    }

    // Create a new document under the secretary's barangay
    $document = BarangayDocument::create([
        'name' => $request->name,
        'barangay_id' => $barangayId,
    ]);

    // Save each purpose
    foreach ($request->purposes as $purposeData) {
        BarangayDocumentPurpose::create([
            'barangay_document_id' => $document->id,
            'purpose' => $purposeData['name'],
            'price' => $purposeData['price'],
        ]);
    }

    return redirect()
        ->route('captain.manage.index')
        ->with('success', 'Document added successfully!');
}

    public function edit($id)
    {
        $document = BarangayDocument::with('purposes')->findOrFail($id);
    
        if ($document->barangay_id != auth()->user()->barangay_id) {
            abort(403);
        }
    
        return response()->json($document); // <-- dito, hindi na view
    }
    
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'purposes.*.name' => 'required|string|max:255',
            'purposes.*.price' => 'required|numeric|min:0',
        ]);
    
        $document = BarangayDocument::findOrFail($id);
        $document->update(['name' => $request->name]);
    
        // Update existing purposes or add new
        foreach ($request->purposes as $purposeData) {
            if (isset($purposeData['id'])) {
                // Update existing purpose
                $purpose = BarangayDocumentPurpose::find($purposeData['id']);
                if ($purpose) {
                    $purpose->update([
                        'purpose' => $purposeData['name'],
                        'price' => $purposeData['price'],
                    ]);
                }
            } else {
                // New purpose
                BarangayDocumentPurpose::create([
                    'barangay_document_id' => $document->id,
                    'purpose' => $purposeData['name'],
                    'price' => $purposeData['price'],
                ]);
            }
        }
    
        return redirect()->route('captain.manage.index')
                         ->with('success', 'Document updated successfully!');
    }
    public function destroy($id)
{
    $document = BarangayDocument::findOrFail($id);

    // Check if the document belongs to the secretary's barangay
    if ($document->barangay_id != auth()->user()->barangay_id) {
        abort(403);
    }

    // Delete associated purposes first (if you have cascading deletes in DB, this may be optional)
    $document->purposes()->delete();

    // Delete the document
    $document->delete();

    return redirect()->route('captain.manage.index')
                     ->with('success', 'Document deleted successfully!');
}

}

    
