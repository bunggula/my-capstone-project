<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Resident;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Report;
use App\Models\WasteReport;
use App\Models\Concern;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Proposal;
use App\Models\Blotter;


class DashboardController extends Controller
{
public function secretaryDashboard()
{
    $user = Auth::user();
    $barangayId = $user->barangay_id;

    // 🔹 Notifications
    $notifications = $this->getSecretaryNotifications($barangayId);

    // 🔹 ALL Events
    $events = Event::with(['barangay', 'images'])
                   ->where('barangay_id', $barangayId)
                   ->latest()
                   ->get();

    // 🔹 Upcoming Events
    $upcomingEvents = Event::with('barangay')
        ->whereDate('date', '>=', now())
        ->where('barangay_id', $barangayId)
        ->orderBy('date', 'asc')
        ->take(2)
        ->get();

    // 🔹 Announcements (filtered)
    $announcementQuery = Announcement::with(['barangay', 'images'])
        ->where(function ($query) use ($barangayId) {
            $query->where(function($q) {
                $q->where('target', 'all')
                  ->where(function($q2) {
                      $q2->whereNull('target_role')
                         ->orWhere('target_role', 'secretary');
                  });
            })
            ->orWhere(function ($q) use ($barangayId) {
                $q->where('target', 'specific')
                  ->where(function($q2) use ($barangayId) {
                      $q2->whereNull('barangay_id')
                         ->orWhere('barangay_id', $barangayId);
                  })
                  ->where(function($q3) {
                      $q3->whereNull('target_role')
                         ->orWhere('target_role', 'secretary');
                  });
            });
        });

    $announcements = (clone $announcementQuery)->latest()->get();
    $totalAnnouncements = (clone $announcementQuery)->count();
    $upcomingAnnouncements = (clone $announcementQuery)
        ->whereDate('date', '>=', now())
        ->orderBy('date', 'asc')
        ->take(2)
        ->get();
    $totalUpcomingAnnouncements = $upcomingAnnouncements->count();

    // 🔹 Residents, Requests, Events
    $totalResidents = Resident::where('barangay_id', $barangayId)
                              ->where('status', 'approved')
                              ->count();

    $totalRequests = DocumentRequest::where('barangay_id', $barangayId)->count();
    $totalNewRequests = DocumentRequest::where('barangay_id', $barangayId)
                                       ->where('status', 'pending')->count();
    $totalEvents = Event::where('barangay_id', $barangayId)->count();
    $totalRejectedEvents = Event::where('barangay_id', $barangayId)
                                ->where('status', 'rejected')->count();
    $totalApprovedAccounts = Resident::where('barangay_id', $barangayId)
                                     ->where('status', 'pending')->count();
     $blotter = Blotter::where('barangay_id', $barangayId)->count();

    // ---------------------------------------------------------
    // 🔹 DATA FOR CHARTS
    // ---------------------------------------------------------

    // 1️⃣ Residents breakdown (case-insensitive)
  $residentCategories = [
    // === Categories based on "category" field (multi-tag capable, like "PWD,Senior,Single Parent") ===
    'Person With Disability' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->where('category', 'LIKE', '%PWD%')
        ->count(),

    'Senior Citizen' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->where(function($q) {
            $q->where('category', 'LIKE', '%Senior%')
              ->orWhere('category', 'LIKE', '%Senior Citizen%');
        })
        ->count(),

    'Indigenous People' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->where('category', 'LIKE', '%Indigenous%')
        ->count(),

    'Single Parent' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->where('category', 'LIKE', '%Single Parent%')
        ->count(),

    // === Gender-based (case-insensitive) ===
    'Male' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->whereRaw('LOWER(gender) = ?', ['male'])
        ->count(),

    'Female' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->whereRaw('LOWER(gender) = ?', ['female'])
        ->count(),

    // === Age-based ===
    'Minor (Below 18)' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->where('age', '<', 18)
        ->count(),

    // === Voter registration ===
    'Registered Voter' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->where('voter', 'Yes')
        ->count(),

    'Non-Registered Voter' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->where(function ($query) {
            $query->where('voter', 'No')
                  ->orWhereNull('voter')
                  ->orWhere('voter', '=', '');
        })
        ->count(),
];



    // 2️⃣ Requests per month
$requestsByTypeMonthly = DocumentRequest::selectRaw('
        MONTH(updated_at) as month,
        document_type,
        COUNT(*) as total
    ')
    ->whereYear('updated_at', now()->year)
    ->where('barangay_id', $barangayId)
    ->where('status', 'Completed') // ✅ only completed requests
    ->groupBy('month', 'document_type')
    ->orderBy('month')
    ->get();
;

// Transform data into [type => [month => total]]
$requestsChartData = [];
foreach ($requestsByTypeMonthly as $row) {
    $requestsChartData[$row->document_type][$row->month] = $row->total;
}

// Make sure all months have values (fill missing months with 0)
foreach ($requestsChartData as $type => $data) {
    foreach (range(1, 12) as $m) {
        if (!isset($requestsChartData[$type][$m])) {
            $requestsChartData[$type][$m] = 0;
        }
    }
    ksort($requestsChartData[$type]);
}


  // 3️⃣ Events per month (approved vs rejected vs pending)
$eventsApproved = Event::selectRaw('MONTH(date) as month, COUNT(*) as total')
    ->where('barangay_id', $barangayId)
    ->where('status', 'approved')
    ->whereYear('date', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray();

$eventsRejected = Event::selectRaw('MONTH(date) as month, COUNT(*) as total')
    ->where('barangay_id', $barangayId)
    ->where('status', 'rejected')
    ->whereYear('date', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray();

$eventsPending = Event::selectRaw('MONTH(date) as month, COUNT(*) as total')
    ->where('barangay_id', $barangayId)
    ->where('status', 'pending')
    ->whereYear('date', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray();



$blotter = Blotter::selectRaw('MONTH(date) as month, COUNT(*) as total')
    ->where('barangay_id', $barangayId)
    ->whereYear('date', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray();

    // 4️⃣ Announcements per month
    $announcementsPerMonth = Announcement::selectRaw('MONTH(date) as month, COUNT(*) as total')
        ->whereYear('date', now()->year)
        ->where(function ($query) use ($barangayId) {
            $query->where(function($q) {
                $q->where('target', 'all')
                  ->where(function($q2) {
                      $q2->whereNull('target_role')
                         ->orWhere('target_role', 'secretary');
                  });
            })
            ->orWhere(function ($q) use ($barangayId) {
                $q->where('target', 'specific')
                  ->where(function($q2) use ($barangayId) {
                      $q2->whereNull('barangay_id')
                         ->orWhere('barangay_id', $barangayId);
                  })
                  ->where(function($q3) {
                      $q3->whereNull('target_role')
                         ->orWhere('target_role', 'secretary');
                  });
            });
        })
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    // 🔹 Month labels
    $months = collect(range(1, 12))->map(fn($m) => date('M', mktime(0, 0, 0, $m, 1)));

    return view('dashboard.secretary', compact(
        'notifications',
        'events',
        'upcomingEvents',
        'announcements',
        'upcomingAnnouncements',
        'totalUpcomingAnnouncements',
        'totalResidents',
        'totalRequests',
        'totalNewRequests',
        'totalEvents',
        'totalRejectedEvents',
        'totalAnnouncements',
        'totalApprovedAccounts',
        'residentCategories',
         'requestsChartData',
        'eventsApproved',
        'eventsRejected',
        'eventsPending', 
        'blotter', 
        'announcementsPerMonth',
        'months'
    ));
}


/**
 * Hiwalay na function para sa Secretary notifications
 */
private function getSecretaryNotifications($barangayId, $limit = 5)
{
    // 🔹 Pending Document Requests
    $notifDocs = DocumentRequest::with('resident')
        ->where('barangay_id', $barangayId)
        ->where('status', 'pending')
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'document_'.$item->id,
            'message' => "New document request from {$item->resident->full_name} ({$item->document_type})",
            'created_at_raw' => $item->created_at,
            'read' => false,
            'type' => 'document_request',
        ]);

    // 🔹 Pending Resident Approvals
    $notifResidents = Resident::where('barangay_id', $barangayId)
        ->where('status', 'pending')
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'resident_'.$item->id,
            'message' => "New Register resident awaiting approval: {$item->full_name}",
            'created_at_raw' => $item->created_at,
            'read' => false,
            'type' => 'resident_approval',
        ]);

    // 🔹 Newly Approved Events (last 24 hours)
    $notifApprovedEvents = Event::where('barangay_id', $barangayId)
        ->where('status', 'approved')
        ->whereDate('updated_at', '>=', now()->subDay())
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'event_approved_'.$item->id,
            'message' => "Event approved: {$item->title}",
            'created_at_raw' => $item->updated_at,
            'read' => false,
            'type' => 'event_approved',
        ]);

    // 🔹 Newly Rejected Events (last 24 hours)
    $notifRejectedEvents = Event::where('barangay_id', $barangayId)
        ->where('status', 'rejected')
        ->whereDate('updated_at', '>=', now()->subDay())
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'event_rejected_'.$item->id,
            'message' => "Event rejected: {$item->title} (Reason: {$item->rejection_reason})",
            'created_at_raw' => $item->updated_at,
            'read' => false,
            'type' => 'event_rejected',
        ]);

    // 🔹 Upcoming Announcements
    $notifAnnouncements = Announcement::where(function ($query) use ($barangayId) {
            $query->where(function($q) {
                $q->where('target', 'all')
                  ->where(fn($q2) => $q2->whereNull('target_role')->orWhere('target_role', 'secretary'));
            })
            ->orWhere(function($q) use ($barangayId) {
                $q->where('target', 'specific')
                  ->where(fn($q2) => $q2->whereNull('barangay_id')->orWhere('barangay_id', $barangayId))
                  ->where(fn($q3) => $q3->whereNull('target_role')->orWhere('target_role', 'secretary'));
            });
        })
        ->whereDate('date', '>=', now())
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'announcement_'.$item->id,
            'message' => "New announcement: {$item->title}",
            'created_at_raw' => $item->created_at,
            'read' => false,
            'type' => 'announcement',
        ]);

    // 🔹 Merge all notifications and sort
    return collect()
        ->merge($notifDocs)
        ->merge($notifResidents)
        ->merge($notifApprovedEvents)
        ->merge($notifRejectedEvents)
        ->merge($notifAnnouncements)
        ->sortByDesc('created_at_raw')
        ->map(fn($n) => [
            ...$n,
            'created_at' => $n['created_at_raw']->diffForHumans(),
        ])
        ->values();
}

    
    
public function viewABCAnnouncements(Request $request)
{
    $user = Auth::user();
    $barangayId = $user->barangay_id;

    // 🔹 Base query (visibility rules for secretary)
    $query = Announcement::with(['barangay', 'images'])
        ->where(function ($query) use ($barangayId) {
            $query->where(function($q) {
                $q->where('target', 'all')
                  ->where(function($q2) {
                      $q2->whereNull('target_role')
                         ->orWhere('target_role', 'secretary');
                  });
            })
            ->orWhere(function ($q) use ($barangayId) {
                $q->where('target', 'specific')
                  ->where(function($q2) use ($barangayId) {
                      $q2->whereNull('barangay_id')
                         ->orWhere('barangay_id', $barangayId);
                  })
                  ->where(function($q3) {
                      $q3->whereNull('target_role')
                         ->orWhere('target_role', 'secretary');
                  });
            });
        });

    // 🔍 Search filter (optional)
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('title', 'like', "%{$request->search}%")
              ->orWhere('details', 'like', "%{$request->search}%");
        });
    }

    // 🧭 Filter by "posted_by"
    if ($request->filled('posted_by')) {
        $query->where('posted_by', $request->posted_by);
    }

    // 📅 Month filter
    if ($request->filled('month')) {
        $query->whereMonth('created_at', $request->month);
    }

    // 📅 Year filter
    if ($request->filled('year')) {
        $query->whereYear('created_at', $request->year);
    }

    // 🔹 Count for All Announcements (reflects filters)
    $allAnnouncementsCount = (clone $query)->count();

    // 🔹 Paginated list
    $announcements = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

    return view('secretary.announcements.index', compact(
        'announcements',
        'allAnnouncementsCount'
    ));
}

public function captainDashboard()
{
    $user = Auth::user();
    $barangayId = $user->barangay_id;

    // ✅ Counts
    $approvedResidentsCount = Resident::where('status', 'approved')
        ->where('barangay_id', $barangayId)->count();

    $approvedEventsCount = Event::where('barangay_id', $barangayId)->count();

    $totalProposals = Proposal::where('barangay', $barangayId)->count();

    $concernsCount = Concern::where('barangay_id', $barangayId)->count();
    $pendingConcernsCount = Concern::where('barangay_id', $barangayId)
        ->where('status', 'pending')->count();

    $documentRequestCount = DocumentRequest::where('barangay_id', $barangayId)
        ->where('status', 'completed')->count();

    $announcementsCount = Announcement::where(function ($query) use ($barangayId) {
        $query->where('target', 'all')
              ->orWhere(fn($q) => $q->where('target', 'specific')->where('barangay_id', $barangayId));
    })->where(fn($q) => $q->whereNull('target_role')
                        ->orWhere('target_role', 'brgy_captain'))->count();

    // 🔹 Notifications (hiwalay na arrays per type)
    $notifications = $this->getCaptainNotifications($barangayId);

    // 🔹 Upcoming Announcements
    $upcomingAnnouncements = Announcement::whereDate('date', '>=', now())
        ->where(function ($query) use ($barangayId) {
            $query->where('target', 'all')
                  ->orWhere(fn($q) => $q->where('target', 'specific')->where('barangay_id', $barangayId));
        })
        ->where(fn($q) => $q->whereNull('target_role')
                            ->orWhere('target_role', 'brgy_captain'))
        ->orderBy('date', 'asc')
        ->orderBy('time', 'asc')
        ->take(2)
        ->get();

    // 🔹 Upcoming Events
    $upcomingEvents = Event::where('status', 'approved')
        ->whereDate('date', '>=', now())
        ->where('barangay_id', $barangayId)
        ->orderBy('date')
        ->take(2)
        ->get();

    // ---------------------------------------------------------
    // 🔹 DATA FOR CHARTS
    // ---------------------------------------------------------

$residentCategories = [
    // === Categories based on "category" field ===
    'Person With Disability' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved') // Exact case
        ->where('category', 'LIKE', '%PWD%')
        ->count(),

    'Senior Citizen' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->where(function($q) {
            $q->where('category', 'LIKE', '%Senior%')
              ->orWhere('category', 'LIKE', '%Senior Citizen%');
        })
        ->count(),

    'Indigenous People' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->where('category', 'LIKE', '%Indigenous%')
        ->count(),

    'Single Parent' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->where('category', 'LIKE', '%Single Parent%')
        ->count(),

    // === Gender-based (case-insensitive) ===
    'Male' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->whereRaw('LOWER(gender) = ?', ['male'])
        ->count(),

    'Female' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->whereRaw('LOWER(gender) = ?', ['female'])
        ->count(),

    // === Age-based ===
    'Minor (Below 18)' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->where('age', '<', 18)
        ->count(),

    // === Voter registration ===
    'Registered Voter' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->where('voter', 'Yes')
        ->count(),

    'Non-Registered Voter' => Resident::where('barangay_id', $barangayId)
        ->where('status', 'Approved')
        ->where(function ($query) {
            $query->where('voter', 'No')
                  ->orWhereNull('voter')
                  ->orWhere('voter', '=', '');
        })
        ->count(),
];

   $requestsByTypeMonthly = DocumentRequest::selectRaw('
MONTH(created_at) as month,
document_type,
COUNT(*) as total
')
->whereYear('created_at', now()->year)
->where('barangay_id', $barangayId)
->where('status', 'completed')
->groupBy('month', 'document_type')
->orderBy('month')
->get();

// Initialize chart data with all months = 0 for each document type
$requestsChartData = [];
$allDocumentTypes = $requestsByTypeMonthly->pluck('document_type')->unique();

foreach ($allDocumentTypes as $docType) {
$requestsChartData[$docType] = array_fill(1, 12, 0); // months 1-12
}

// Fill in actual counts
foreach ($requestsByTypeMonthly as $row) {
$requestsChartData[$row->document_type][$row->month] = $row->total;
}

// Sort months for each document type
foreach ($requestsChartData as $docType => $monthData) {
ksort($requestsChartData[$docType]);
}



   // 3️⃣ Concerns (Pending vs Resolved vs Ongoing)
// 🔹 Concerns per Month (Pending / Ongoing / Resolved)
$statuses = ['pending', 'on going', 'resolved'];
$concernsPerMonth = [];

foreach ($statuses as $status) {
    // Initialize all 12 months with 0
    $concernsPerMonth[$status] = array_fill(1, 12, 0);

    $monthlyData = Concern::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('barangay_id', $barangayId)
        ->where('status', $status)
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    // Fill actual values
    foreach ($monthlyData as $month => $total) {
        $concernsPerMonth[$status][$month] = $total;
    }

    // Sort by month just in case
    ksort($concernsPerMonth[$status]);
}


  // 4️⃣ Events (Approved vs Rejected vs Pending per month)
$eventsApproved = Event::selectRaw('MONTH(date) as month, COUNT(*) as total')
    ->where('barangay_id', $barangayId)
    ->where('status', 'approved')
    ->whereYear('date', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray();

$eventsRejected = Event::selectRaw('MONTH(date) as month, COUNT(*) as total')
    ->where('barangay_id', $barangayId)
    ->where('status', 'rejected')
    ->whereYear('date', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray();

$eventsPending = Event::selectRaw('MONTH(date) as month, COUNT(*) as total')
    ->where('barangay_id', $barangayId)
    ->where('status', 'pending')
    ->whereYear('date', now()->year)
    ->groupBy('month')
    ->pluck('total', 'month')
    ->toArray();

  // 5️⃣ Proposals (Pending, Approved, Rejected per month)
    $proposalsApproved = Proposal::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('barangay', $barangayId)
        ->where('status', 'approved')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $proposalsPending = Proposal::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('barangay', $barangayId)
        ->where('status', 'pending')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $proposalsRejected = Proposal::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('barangay', $barangayId)
        ->where('status', 'rejected')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();
    // 5️⃣ Announcements per Month (Captain Filter)
    $announcementsPerMonth = Announcement::selectRaw('MONTH(date) as month, COUNT(*) as total')
        ->whereYear('date', now()->year)
        ->where(function ($query) use ($barangayId) {
            $query->where(function ($q) {
                $q->where('target', 'all')
                  ->where(function ($r) {
                      $r->whereNull('target_role')
                        ->orWhere('target_role', 'brgy_captain');
                  });
            })
            ->orWhere(function ($q) use ($barangayId) {
                $q->where('target', 'specific')
                  ->where('barangay_id', $barangayId)
                  ->where(function ($r) {
                      $r->whereNull('target_role')
                        ->orWhere('target_role', 'brgy_captain');
                  });
            });
        })
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

    // 🔹 Month labels
   // 🔹 Month labels for charts
$months = collect(range(1, 12))->map(fn($m) => date('M', mktime(0, 0, 0, $m, 1)))->toArray();


    // ---------------------------------------------------------
    // 🔹 RETURN TO VIEW
    // ---------------------------------------------------------
    return view('dashboard.captain', compact(
        'approvedResidentsCount',
        'approvedEventsCount',
        'totalProposals',
        'concernsCount',
        'pendingConcernsCount',
        'documentRequestCount',
        'announcementsCount',
        'notifications',
        'upcomingAnnouncements',
        'upcomingEvents',
        // charts
        'residentCategories',
    'proposalsApproved',
        'proposalsPending',
        'proposalsRejected',
        'requestsChartData',
       'concernsPerMonth',
        'eventsApproved',
        'eventsRejected',
         'eventsPending',
        'announcementsPerMonth',
        'months'
    ));
}


/**
 * Hiwalay na function para sa Captain notifications
 */
private function getCaptainNotifications($barangayId, $limit = 5)
{
    $notifEvents = Event::where('status', 'pending')
        ->where('barangay_id', $barangayId)
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'event_'.$item->id,
            'message' => "New event awaiting approval: {$item->title}",
            'created_at_raw' => $item->created_at,
            'read' => false,
            'type' => 'pending_event',
        ]);

    $notifResidents = Resident::where('status', 'approved')
        ->where('barangay_id', $barangayId)
         ->orderByDesc('updated_at')
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'resident_'.$item->id,
            'message' => "Resident approved: {$item->full_name}",
            'created_at_raw' => $item->updated_at,
            'read' => false,
            'type' => 'approved_resident',
        ]);

    $notifServices = DocumentRequest::where('status', 'completed')
        ->where('barangay_id', $barangayId)
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'service_'.$item->id,
            'message' => "Document request completed: {$item->document_type} for {$item->resident->full_name}",
            'created_at_raw' => $item->updated_at,
            'read' => false,
            'type' => 'completed_service',
        ]);

    $notifConcerns = Concern::with('resident')
        ->where('status', 'pending')
        ->where('barangay_id', $barangayId)
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'concern_'.$item->id,
            'message' => "New concern from {$item->resident->full_name} awaiting review: {$item->title}",
            'created_at_raw' => $item->created_at,
            'read' => false,
            'type' => 'pending_concern',
        ]);

    $notifAnnouncements = Announcement::whereDate('date', '>=', now())
        ->where(function ($query) use ($barangayId) {
            $query->where('target', 'all')
                  ->orWhere(fn($q) => $q->where('target', 'specific')->where('barangay_id', $barangayId));
        })
        ->where(fn($q) => $q->whereNull('target_role')
                            ->orWhere('target_role', 'brgy_captain'))
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'announcement_'.$item->id,
            'message' => "New announcement: {$item->title}",
            'created_at_raw' => $item->created_at,
            'read' => false,
            'type' => 'announcement',
        ]);

    $notifProposals = Proposal::where('barangay', $barangayId)
        ->where('status', 'approved')
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'proposal_'.$item->id,
            'message' => "Proposal approved: {$item->title}",
            'created_at_raw' => $item->updated_at,
            'read' => false,
            'type' => 'proposal_approved',
        ]);
          // Rejected proposals
    $notifRejectedProposals = Proposal::where('barangay', $barangayId)
        ->where('status', 'rejected')
        ->latest()
        ->take($limit)
        ->get()
        ->map(fn($item) => [
            'key' => 'proposal_rejected_'.$item->id,
            'message' => "Proposal rejected: {$item->title}. Reason: {$item->rejection_reason}",
            'created_at_raw' => $item->updated_at,
            'read' => false,
            'type' => 'proposal_rejected',
        ]);

  return collect()
        ->merge($notifEvents)
        ->merge($notifResidents)
        ->merge($notifServices)
        ->merge($notifConcerns)
        ->merge($notifAnnouncements)
        ->merge($notifProposals)
        ->merge($notifRejectedProposals)
        ->sortByDesc('created_at_raw')
        ->map(fn($n) => [
            ...$n,
            'created_at' => $n['created_at_raw']->diffForHumans()
        ])
        ->values();
}


    
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }

    public function index(Request $request)
    {
        $query = Event::with(['barangay', 'images'])->where('status', 'approved');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhereHas('barangay', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $approvedEvents = $query->latest()->get();

        return view('your-dashboard', compact('approvedEvents'));
    }
}
