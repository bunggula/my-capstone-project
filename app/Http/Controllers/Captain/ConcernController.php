<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Concern;
class ConcernController extends Controller
{
public function index(Request $request)
{
    $barangayId = auth()->user()->barangay_id;
    $status = $request->query('status', 'pending');
    $search = $request->query('search');
    $month = $request->query('month');
    $year = $request->query('year');

    // 🔹 Count ng bawat status na nagre-reflect sa current filters
    $pendingCount = Concern::where('barangay_id', $barangayId)
        ->where('status', 'pending')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('middle_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        })
        ->when($month, fn($query) => $query->whereMonth('created_at', $month))
        ->when($year, fn($query) => $query->whereYear('created_at', $year))
        ->count();

    $onGoingCount = Concern::where('barangay_id', $barangayId)
        ->where('status', 'on going')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('middle_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        })
        ->when($month, fn($query) => $query->whereMonth('updated_at', $month))
        ->when($year, fn($query) => $query->whereYear('updated_at', $year))
        ->count();

    $resolvedCount = Concern::where('barangay_id', $barangayId)
        ->where('status', 'resolved')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('middle_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        })
        ->when($month, fn($query) => $query->whereMonth('updated_at', $month))
        ->when($year, fn($query) => $query->whereYear('updated_at', $year))
        ->count();

    $totalCount = $pendingCount + $onGoingCount + $resolvedCount;

    // 🔄 Auto-switch tab kapag may search at walang status param (unang search lang)
    if ($search && !$request->has('status')) {
        $firstMatch = Concern::where('barangay_id', $barangayId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('resident', function ($q2) use ($search) {
                          $q2->where('first_name', 'like', "%{$search}%")
                             ->orWhere('middle_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%");
                      });
                });
            })
            ->first();

        if ($firstMatch && $status !== $firstMatch->status) {
            return redirect()->route('captain.concerns.index', [
                'status' => $firstMatch->status,
                'search' => $search,
                'month' => $month,
                'year' => $year,
            ]);
        }
    }

    // 🔹 Main query with pagination
    $concerns = Concern::with('resident')
        ->where('barangay_id', $barangayId)
        ->when($status && $status !== 'all', fn($query) => $query->where('status', $status))
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('middle_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        })
        ->when($month, function ($query) use ($month) {
            $query->where(function ($q) use ($month) {
                $q->where(function ($q2) use ($month) {
                    $q2->where('status', 'pending')->whereMonth('created_at', $month);
                })->orWhere(function ($q2) use ($month) {
                    $q2->whereIn('status', ['on going', 'resolved'])->whereMonth('updated_at', $month);
                });
            });
        })
        ->when($year, function ($query) use ($year) {
            $query->where(function ($q) use ($year) {
                $q->where(function ($q2) use ($year) {
                    $q2->where('status', 'pending')->whereYear('created_at', $year);
                })->orWhere(function ($q2) use ($year) {
                    $q2->whereIn('status', ['on going', 'resolved'])->whereYear('updated_at', $year);
                });
            });
        })
        ->orderByRaw("CASE WHEN status = 'pending' THEN created_at ELSE updated_at END DESC")
        ->paginate(10)
        ->appends([
            'search' => $search, 
            'status' => $status,
            'month' => $month,
            'year' => $year
        ]);

    return view('captain.concerns.index', [
        'concerns' => $concerns,
        'pendingCount' => $pendingCount,
        'onGoingCount' => $onGoingCount,
        'resolvedCount' => $resolvedCount,
        'totalCount' => $totalCount,
        'currentStatus' => $status,
    ]);
}


    
    public function show($id)
    {
        $concern = Concern::with('resident')->findOrFail($id);
    
        // Ensure captain can only view concerns in their barangay
        if ($concern->barangay_id !== auth()->user()->barangay_id) {
            abort(403);
        }
    
        return view('captain.concerns.show', compact('concern'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,on going,resolved',
            'meeting_date' => 'nullable|date', // ✅ validate meeting_date
        ]);
    
        $concern = Concern::findOrFail($id);
    
        // Security check
        if ($concern->barangay_id !== auth()->user()->barangay_id) {
            abort(403);
        }
    
        // Update concern status
        $concern->status = $request->status;
    
        // ✅ Save meeting_date if provided
        if ($request->filled('meeting_date')) {
            $concern->meeting_date = $request->meeting_date;
        }
    
        $concern->save();
    
        return redirect()->back()->with('success', 'Status updated to ' . ucfirst($request->status) . '.');

    }
    


}