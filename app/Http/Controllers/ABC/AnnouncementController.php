<?php

namespace App\Http\Controllers\ABC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Barangay;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\AnnouncementImage;

class AnnouncementController extends Controller
{
public function index(Request $request)
{
    // ✅ Base query (with eager loading for performance)
    $query = Announcement::with(['barangay', 'images'])
        ->orderByDesc('created_at');

    /* ----------------------------------------------------------
     | 🔹 TARGET TYPE FILTER
     |---------------------------------------------------------- */
    if ($request->filled('target_type')) {
        match ($request->target_type) {
            'all' => $query->where('target', 'all'),
            'specific_barangay' => $query->where('target', 'specific')->whereNull('target_role'),
            'specific_role' => $query->where('target', 'specific')->whereNotNull('target_role'),
            default => null,
        };
    }

    /* ----------------------------------------------------------
     | 🔹 POSTED BY FILTER
     |---------------------------------------------------------- */
    if ($request->filled('posted_by')) {
        $query->where('posted_by', $request->posted_by);
    }

    /* ----------------------------------------------------------
     | 🔹 SEARCH FILTER
     |---------------------------------------------------------- */
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('details', 'like', "%{$search}%")
              ->orWhereHas('barangay', function ($sub) use ($search) {
                  $sub->where('name', 'like', "%{$search}%");
              });
        });
    }

    /* ----------------------------------------------------------
     | 🔹 DATE FILTERS (MONTH & YEAR)
     |---------------------------------------------------------- */
    if ($request->filled('month')) {
        $query->whereMonth('date', $request->month);
    }

    if ($request->filled('year')) {
        $query->whereYear('date', $request->year);
    }

    /* ----------------------------------------------------------
     | 🔄 AUTO-SWITCH TAB WHEN SEARCHING (ONLY IF target_type is missing)
     |---------------------------------------------------------- */
    if ($request->filled('search') && !$request->filled('target_type')) {
        $searchQuery = Announcement::query()
            ->when($request->filled('search'), fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->filled('month'), fn($q) => $q->whereMonth('date', $request->month))
            ->when($request->filled('year'), fn($q) => $q->whereYear('date', $request->year));

        $firstMatch = $searchQuery->first();
        if ($firstMatch) {
            $computedTarget = match (true) {
                $firstMatch->target === 'all' => 'all',
                $firstMatch->target === 'specific' && is_null($firstMatch->target_role) => 'specific_barangay',
                $firstMatch->target === 'specific' && !is_null($firstMatch->target_role) => 'specific_role',
            };

            return redirect()->route('abc.announcements.index', [
                'search' => $request->search,
                'posted_by' => $request->posted_by,
                'month' => $request->month,
                'year' => $request->year,
                'target_type' => $computedTarget,
            ]);
        }
    }

    /* ----------------------------------------------------------
     | 🔹 PAGINATION
     |---------------------------------------------------------- */
    $announcements = $query->paginate(10)->withQueryString();

    /* ----------------------------------------------------------
     | 🔹 BARANGAY LIST (for dropdowns)
     |---------------------------------------------------------- */
    $barangays = Barangay::all();

    /* ----------------------------------------------------------
     | 🔹 COUNTS (respecting active filters)
     |---------------------------------------------------------- */
    $filteredQuery = Announcement::query();

    if ($request->filled('posted_by')) {
        $filteredQuery->where('posted_by', $request->posted_by);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $filteredQuery->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('details', 'like', "%{$search}%")
              ->orWhereHas('barangay', function ($sub) use ($search) {
                  $sub->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('month')) {
        $filteredQuery->whereMonth('date', $request->month);
    }

    if ($request->filled('year')) {
        $filteredQuery->whereYear('date', $request->year);
    }

    $allAnnouncementsCount = (clone $filteredQuery)->count();
    $allBarangaysCount     = (clone $filteredQuery)->where('target', 'all')->count();
    $specificBarangayCount = (clone $filteredQuery)->where('target', 'specific')->whereNull('target_role')->count();
    $specificRoleCount     = (clone $filteredQuery)->where('target', 'specific')->whereNotNull('target_role')->count();

    /* ----------------------------------------------------------
     | 🔹 RETURN VIEW
     |---------------------------------------------------------- */
    return view('abc.announcements.index', compact(
        'announcements',
        'barangays',
        'allAnnouncementsCount',
        'allBarangaysCount',
        'specificBarangayCount',
        'specificRoleCount'
    ));
}


    

    public function create()
    {
        $barangays = Barangay::all();
        return view('abc.announcements.create', compact('barangays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'target' => 'required|string|in:all,specific',
            'barangay_id' => 'nullable|required_if:target,specific|exists:barangays,id',
            'role' => 'nullable|in:brgy_captain,secretary',
            'role_all' => 'nullable|in:brgy_captain,secretary',
             // ✅ dito
            'images.*' => 'image|max:2048',
        ]);
        $announcement = Announcement::create([
            'title'        => $request->title,
            'details'      => $request->details,
            'date'         => $request->date,
            'time'         => $request->time,
            'target'       => $request->target,
            'barangay_id'  => $request->target === 'specific' ? $request->barangay_id : null,
            'target_role'  => $request->target === 'specific' ? $request->role : $request->role_all ?? null,
             // ✅ dito rin
        ]);
        
    
        // Handle image upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('announcements', 'public');
                $announcement->images()->create(['path' => $path]);
            }
        }
    
        return redirect()->route('abc.announcements.index')->with('success', 'Announcement posted successfully!');
    }
    
    public function edit(Announcement $announcement)
{
    $barangays = Barangay::all();
    
    return view('abc.announcements.edit', compact('announcement', 'barangays'));
}

public function update(Request $request, Announcement $announcement)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'details' => 'required|string',
        'date' => 'required|date',
        'time' => 'required',
        'target' => 'required|string|in:all,specific',
        'barangay_id' => 'nullable|exists:barangays,id',
        'role' => 'nullable|in:brgy_captain,secretary',
        'role_all' => 'nullable|in:brgy_captain,secretary',
       'images.*' => 'image|max:2048',
   

    ]);

    // Update basic fields
    $announcement->update([
        'title'        => $request->title,
        'details'      => $request->details,
        'date'         => $request->date,
        'time'         => $request->time,
        'target'       => $request->target,
        'barangay_id'  => $request->target === 'specific' ? $request->barangay_id : null,
        'target_role'  => $request->target === 'specific' ? $request->role : $request->role_all ?? null,
       
    ]);

    // Delete selected images
    if ($request->has('delete_images')) {
        foreach ($request->delete_images as $imageId) {
            $image = $announcement->images()->find($imageId);
            if ($image) {
                \Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }
    }

    // Upload new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $img) {
            $path = $img->store('announcements', 'public');
            $announcement->images()->create(['path' => $path]);
        }
    }

    return redirect()->route('abc.announcements.index')->with('success', 'Announcement updated successfully!');
}


public function destroy(Announcement $announcement)
{
    // optionally delete related images
    foreach ($announcement->images as $image) {
        \Storage::disk('public')->delete($image->path);
        $image->delete();
    }

    $announcement->delete();

    return redirect()->route('abc.announcements.index')->with('success', 'Announcement deleted!');
}
public function deleteImage($id)
{
    $image = AnnouncementImage::findOrFail($id);

    // Optional: Delete image from storage
    Storage::disk('public')->delete($image->path);

    $image->delete();

    return back()->with('success', 'Image deleted successfully.');
}
public function apiIndex(Request $request)
{
    $barangayId = $request->query('barangay_id');
    $role = $request->query('role');

    $announcements = Announcement::with('images')
        ->where(function ($query) use ($barangayId, $role) {
            // Announcements for all users, no role restriction
            $query->where('target', 'all')
                  ->where(function ($q) {
                      $q->whereNull('target_role')
                        ->orWhere('target_role', '');
                  });

            // Announcements specific to user's barangay and role
            if ($barangayId) {
                $query->orWhere(function ($q) use ($barangayId, $role) {
                    $q->where('target', 'specific')
                      ->where('barangay_id', $barangayId)
                      ->where(function ($q2) use ($role) {
                          $q2->whereNull('target_role')
                             ->orWhere('target_role', $role);
                      });
                });
            }
        })
               // ✅ Hide announcements that are already finished
           ->where(function ($q) {
            $q->whereNull('date') // optional: if no date, still show
              ->orWhereRaw("CONCAT(date, ' ', time) >= NOW()");
        })
        
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($announcement) {
            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'details' => $announcement->details,
                'date' => $announcement->date,
                'time' => $announcement->time,
                'target' => $announcement->target,
                'barangay_id' => $announcement->barangay_id,
                'target_role' => $announcement->target_role,
                'created_at' => $announcement->created_at,
                'posted_by' => $announcement->posted_by,    
                'images' => $announcement->images->map(function ($img) {
                    return ['path' => asset('storage/' . $img->path)];
                })->toArray(),
            ];
        });

    return response()->json($announcements);
}

public function show(Announcement $announcement)
{
    // Return a partial view for modal display
    return view('abc.announcements._view', compact('announcement'));
}


}