<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangayDocument;

class BarangayDocumentsController extends Controller
{
    public function index(Request $request)
    {
        $barangayId = $request->query('barangay_id');

        \Log::info('Fetching documents for barangay_id: ' . $barangayId);

        try {
            $documents = BarangayDocument::with('purposes')
                ->where('barangay_id', $barangayId)
                ->get();

            \Log::info('Documents fetched: ' . $documents->count());

            return response()->json($documents);
        } catch (\Exception $e) {
            \Log::error('Error fetching documents: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
    public function fields($id)
    {
        $document = BarangayDocument::find($id);
    
        if (!$document) {
            return response()->json([
                'message' => 'Document not found'
            ], 404);
        }
    
        $fields = [];
    
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
    
    
    public function purposes($id)
    {
        $document = BarangayDocument::with('purposes')->find($id);
    
        if (!$document) {
            return response()->json([
                'message' => 'Document not found'
            ], 404);
        }
    
        return response()->json([
            'purposes' => $document->purposes->map(function ($p) {
                return [
                    'purpose' => $p->purpose,
                    'price' => $p->price,
                ];
            })
        ]);
    }
    
}
