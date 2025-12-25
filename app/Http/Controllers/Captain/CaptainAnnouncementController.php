<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class CaptainAnnouncementController extends Controller
{
   public function index(Request $request)
{
    $user = Auth::user();
    $barangayId = $user->barangay_id;
    $role = 'brgy_captain';

    // Base query para sa mga announcements na makikita ni captain
    $query = Announcement::with('images')
        ->where(function ($q) use ($barangayId, $role) {
            $q->where(function ($q2) use ($role) {
                $q2->where('target', 'all')
                    ->where(function ($q3) use ($role) {
                        $q3->whereNull('target_role')
                            ->orWhere('target_role', $role);
                    });
            })
            ->orWhere(function ($q2) use ($barangayId, $role) {
                $q2->where('target', 'specific')
                    ->where(function ($q3) use ($barangayId) {
                        $q3->whereNull('barangay_id')
                            ->orWhere('barangay_id', $barangayId);
                    })
                    ->where(function ($q4) use ($role) {
                        $q4->whereNull('target_role')
                            ->orWhere('target_role', $role);
                    });
            });
        });

    // 🔍 Search filter
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // 👤 Filter by posted_by
    if ($request->filled('posted_by')) {
        $query->where('posted_by', $request->posted_by);
    }

    // 🗓 Filter by Month
    if ($request->filled('month')) {
        $query->whereMonth('created_at', $request->month);
    }

    // 📅 Filter by Year
    if ($request->filled('year')) {
        $query->whereYear('created_at', $request->year);
    }

    // 🔹 Count para sa card (kasama ang filters)
    $allAnnouncementsCount = (clone $query)->count();

    // 🔹 Paginate at order
    $announcements = $query->orderByDesc('created_at')->paginate(5)->withQueryString();

    // Ibalik lahat ng filters sa view para hindi mawala sa paglipat ng tab
    return view('captain.announcements.index', compact(
        'announcements',
        'allAnnouncementsCount'
    ));
}
}