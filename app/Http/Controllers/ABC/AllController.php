<?php

namespace App\Http\Controllers\ABC;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use Illuminate\Http\Request;

class AllController extends Controller
{
     public function index(Request $request)
    {
        $yearNow = now()->year;
    
        // Filters from query parameters
        $positionFilter = $request->query('position');
        $selectedBarangay = $request->query('barangay');
    
        // Get all barangays for dropdown
        $allBarangays = Barangay::orderBy('name')->get();
    
        // Base query for barangays with relations
        $barangaysQuery = Barangay::with([
            'barangayOfficials' => function ($query) use ($yearNow, $positionFilter) {
                // Only current officials with selected positions
                $query->whereIn('position', ['Kagawad', 'SK Chairman'])
                      ->where('start_year', '<=', $yearNow)
                      ->where('end_year', '>=', $yearNow);
    
                // Apply position filter if selected
                if ($positionFilter) {
                    $query->where('position', $positionFilter);
                }
    
                $query->orderBy('position');
            },
            'users' => function ($query) {
                // Only active Captain and Secretary
                $query->whereIn('role', ['brgy_captain', 'secretary'])
                      ->where('status', 'active');
            }
        ])->orderBy('name');
    
        // Apply Barangay filter if selected
        if ($selectedBarangay) {
            $barangaysQuery->where('id', $selectedBarangay);
        }
    
        // Get the results
        $barangays = $barangaysQuery->get();
    
        // Pass all necessary variables to the view
        return view('abc.accounts.all', compact(
            'barangays',
            'allBarangays',
            'positionFilter',
            'selectedBarangay'
        ));
    }
 public function print(Request $request)
    {
        $yearNow = now()->year;
        $positionFilter = $request->query('position');
        $selectedBarangay = $request->query('barangay');
    
        $barangaysQuery = Barangay::with([
            'barangayOfficials' => function ($query) use ($yearNow, $positionFilter) {
                $query->whereIn('position', ['Kagawad', 'SK Chairman'])
                      ->where('start_year', '<=', $yearNow)
                      ->where('end_year', '>=', $yearNow);
    
                if ($positionFilter) {
                    $query->where('position', $positionFilter);
                }
    
                $query->orderBy('position');
            },
            'users' => function ($query) {
                $query->whereIn('role', ['brgy_captain', 'secretary'])
                      ->where('status', 'active');
            }
        ])->orderBy('name');
    
        if ($selectedBarangay) {
            $barangaysQuery->where('id', $selectedBarangay);
        }
    
        $barangays = $barangaysQuery->get();
    
        // Get dynamic barangay name for heading
        $barangayName = null;
        if ($selectedBarangay) {
            $barangayName = Barangay::find($selectedBarangay)?->name;
        }
    
        return view('abc.accounts.print', compact('barangays', 'barangayName', 'positionFilter'));
    }
}
