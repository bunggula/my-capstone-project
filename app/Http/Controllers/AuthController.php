<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Barangay;
use App\Models\Announcement;
use App\Models\User;
use App\Models\DocumentRequest;
use App\Models\Resident;
use App\Models\Proposal;
use App\Models\Concern;
use App\Models\Municipality;

class AuthController extends Controller
{
    // Ipakita ang login form
   public function showLoginForm()
{
    // Get the first municipality (assuming only one exists)
    $municipality = Municipality::first();

    // Pass it to the login view
    return view('abc.login', compact('municipality'));
}

    // Iproseso ang login request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Block inactive users
            if ($user->status !== 'active') {
                Auth::logout();
                return back()
                    ->with('error', 'Your account is inactive.')
                    ->withInput($request->only('email'));
            }

            // Force password change if temp password
            if ($user->must_change_password) {
                return redirect()->route('settings.password')
                    ->with('info', 'You must change your temporary password before continuing.');
            }

            // Redirect based on role
            return match($user->role) {
                'abc_admin' => redirect()->intended('/abc/dashboard'),
                'secretary' => redirect()->intended('/secretary/dashboard'),
                'brgy_captain' => redirect()->intended('/captain/dashboard'),
                default => redirect()->intended('/'),
            };
        }

        return back()
            ->with('error', 'Invalid email or password.')
            ->withInput($request->only('email'));
    }

   // ABC Dashboard
public function abcDashboard(Request $request)
{
// ✅ Per-chart year selectors (default to current year)
$residentsYear = $request->input('residents_year', now()->year);
$eventsYear = $request->input('events_year', now()->year);
$announcementsYear = $request->input('announcements_year', now()->year);
$proposalsYear = $request->input('proposals_year', now()->year);
$servicesYear = $request->input('services_year', now()->year);
$resolvedYear = $request->input('resolved_year', now()->year);


// ✅ Filters
$search = $request->input('search');
$barangayFilter = $request->input('barangay');

// ✅ Approved Events (filtered)
$approvedEvents = Event::with('barangay')
    ->where('status', 'approved')
    ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
    ->when($barangayFilter, fn($q) => $q->where('barangay_id', $barangayFilter))
    ->orderBy('date')
    ->get();

// ✅ Upcoming Events (limit 2)
$upcomingEvents = Event::with('barangay')
    ->where('status', 'approved')
    ->where('date', '>=', now()->toDateString())
    ->orderBy('date')
    ->limit(2)
    ->get();

// ✅ Upcoming Announcements (limit 2)
$upcomingAnnouncements = Announcement::with('barangay')
    ->where('date', '>=', now()->toDateString())
    ->orderBy('date')
    ->limit(2)
    ->get();

// ✅ Barangay list
$barangays = Barangay::orderBy('name')->get();

// ✅ TOTAL COUNTS
$totalApprovedResidents = Resident::where('status', 'approved')->count();
$totalAnnouncements = Announcement::count();
$totalAccounts = User::count();
$totalBarangays = Barangay::count();
$totalApprovedEvents = Event::where('status', 'approved')->count();
$totalApprovedProposals = Proposal::count();
$totalCompletedServices = DocumentRequest::where('status', 'completed')->count();

// ✅ Notifications
$notifications = $this->getNotifications();

// ✅ Chart Data per Barangay
$residentsPerBarangay = $barangays->map(fn($b) => $b->residents()->where('status', 'approved')->count());
$barangayNames = $barangays->pluck('name');
$announcementsPerBarangay = $barangays->map(fn($b) => $b->announcements()->count());
$proposalsPerBarangay = $barangays->map(fn($b) =>
    Proposal::where('barangay', $b->id)
            ->whereIn('status', ['pending', 'resubmit'])
            ->count()
);

$eventsPerBarangay = [];
foreach ($barangays as $b) {
    $eventsPerBarangay[$b->name] = collect(range(1, 12))->map(function($month) use ($b, $eventsYear) {
        return Event::where('barangay_id', $b->id)
                    ->where('status', 'approved')
                    ->whereMonth('date', $month)
                    ->whereYear('date', $eventsYear)
                    ->count();
    })->toArray();
}

// ✅ Document types & months
$documentTypes = DocumentRequest::select('document_type')->distinct()->pluck('document_type');
$months = collect(range(1, 12))->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('M'));

// ✅ Services per document type detailed
$servicesPerDocumentTypeDetailed = [];
foreach ($documentTypes as $type) {
    foreach (range(1, 12) as $month) {
        $barangayData = [];
        foreach ($barangays as $b) {
            $barangayData[$b->name] = DocumentRequest::where('status', 'completed')
                ->where('document_type', $type)
                ->where('barangay_id', $b->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $servicesYear)
                ->count();
        }
        $servicesPerDocumentTypeDetailed[$type][$month] = $barangayData;
    }
}

// ✅ Chart Data Over Time per month
// ✅ Keep this for total announcements per month
$announcementsPerMonth = $months->map(fn($m, $i) =>
    Announcement::whereMonth('date', $i + 1)
                ->whereYear('date', $announcementsYear)
                ->count()
);

// ✅ Add this for legend breakdown (per category per month)
$announcementBreakdownPerMonth = [
    'All Barangays' => $months->map(fn($m, $i) =>
        Announcement::where('target', 'all')
                    ->whereMonth('date', $i + 1)
                    ->whereYear('date', $announcementsYear)
                    ->count()
    ),
    'Specific Barangay' => $months->map(fn($m, $i) =>
        Announcement::where('target', 'specific')
                    ->whereNull('target_role')
                    ->whereMonth('date', $i + 1)
                    ->whereYear('date', $announcementsYear)
                    ->count()
    ),
    'Specific Role' => $months->map(fn($m, $i) =>
        Announcement::where('target', 'specific')
                    ->whereNotNull('target_role')
                    ->whereMonth('date', $i + 1)
                    ->whereYear('date', $announcementsYear)
                    ->count()
    ),
];


$eventsPerMonth = $months->map(fn($m, $i) =>
    Event::where('status', 'approved')
         ->whereMonth('date', $i + 1)
         ->whereYear('date', $eventsYear)
         ->count()
);

$servicesPerMonth = $months->map(fn($m, $i) =>
    DocumentRequest::where('status', 'completed')
                   ->whereMonth('created_at', $i + 1)
                   ->whereYear('created_at', $servicesYear)
                   ->count()
);

// ✅ Resolved Cases per Month per Barangay
$resolvedDataPerMonth = [
    'months' => $months,
    'resolvedCounts' => []
];

foreach ($barangays as $b) {
    $resolvedDataPerMonth['resolvedCounts'][$b->name] = $months->map(function($month, $i) use ($b, $resolvedYear) {
        return Concern::where('barangay_id', $b->id)
                      ->where('status', 'resolved')
                      ->whereMonth('updated_at', $i + 1)
                      ->whereYear('updated_at', $resolvedYear)
                      ->count();
    });
}

// ✅ Proposal status counts per month
$proposalStatuses = ['pending', 'approved', 'rejected'];
$proposalsPerMonth = [];
foreach ($proposalStatuses as $status) {
    $proposalsPerMonth[$status] = $months->map(function($month, $i) use ($status, $proposalsYear) {
        return Proposal::where('status', $status)
                       ->whereMonth('created_at', $i + 1)
                       ->whereYear('created_at', $proposalsYear)
                       ->count();
    });
}

// ✅ Return to view with all variables, including per-chart year selections
return view('abc.dashboard', compact(
    'approvedEvents',
    'upcomingEvents',
    'upcomingAnnouncements',
    'barangays',
    'totalApprovedResidents',
    'totalAnnouncements',
    'totalAccounts',
    'totalBarangays',
    'totalApprovedEvents',
    'totalApprovedProposals',
    'totalCompletedServices',
    'notifications',
    'residentsPerBarangay',
    'barangayNames',
    'announcementsPerBarangay',
    'proposalsPerBarangay',
    'eventsPerBarangay',
    'servicesPerDocumentTypeDetailed',
    'documentTypes',
    'months',
    'announcementsPerMonth',
    'announcementBreakdownPerMonth',
    'eventsPerMonth',
    'servicesPerMonth',
    'proposalsPerMonth',
    'resolvedDataPerMonth',
    'residentsYear',
    'eventsYear',
    'announcementsYear',
    'proposalsYear',
    'servicesYear',
    'resolvedYear'
));

}


   
   
    // Private method para sa notifications
private function getNotifications($limit = 5)
{
    $notifResidents = Resident::with('barangay')
        ->where('status', 'approved')
        ->orderByDesc('updated_at')
        ->take($limit)
        ->get()
        ->map(fn($r) => [
            'key' => 'resident_'.$r->id,
            'message' => "Newly approved resident: {$r->full_name} ({$r->barangay->name})",
            'created_at_raw' => $r->updated_at, // raw timestamp for sorting
            'read' => false,
            'type' => 'resident_approved',
        ]);

    $notifEvents = Event::with('barangay')
        ->where('status', 'approved')
        ->orderByDesc('updated_at')
        ->take($limit)
        ->get()
        ->map(fn($e) => [
            'key' => 'event_'.$e->id,
            'message' => "Newly approved event: {$e->title} ({$e->barangay->name})",
            'created_at_raw' => $e->updated_at,
            'read' => false,
            'type' => 'event_approved',
        ]);

$notifProposals = Proposal::with('barangayInfo')
    ->whereIn('status', ['pending', 'resubmit']) // ✅ isama ang resubmit
    ->orderByDesc('created_at')
    ->take($limit)
    ->get()
    ->map(fn($p) => [
        'key' => 'proposal_'.$p->id,
        'message' => "Proposal awaiting approval: {$p->title} (" . ($p->barangayInfo->name ?? 'Unknown Barangay') . ")",
        'created_at_raw' => $p->created_at,
        'read' => false,
        'type' => 'proposal',
        'status' => $p->status, // optional: para makita kung resubmit
    ]);


    $notifServices = DocumentRequest::with('resident', 'barangay')
        ->where('status', 'completed')
        ->orderByDesc('updated_at')
        ->take($limit)
        ->get()
        ->map(fn($s) => [
            'key' => 'service_'.$s->id,
            'message' => "Completed service: {$s->document_type} for {$s->resident->full_name} ({$s->barangay->name})",
            'created_at_raw' => $s->updated_at,
            'read' => false,
            'type' => 'service_completed',
        ]);

    $notifConcerns = \App\Models\Concern::with('barangay')
        ->where('status', 'resolved')
        ->orderByDesc('updated_at')
        ->take($limit)
        ->get()
        ->map(fn($c) => [
            'key' => 'concern_'.$c->id,
            'message' => "Resolved concern: {$c->title} ({$c->barangay->name})",
            'created_at_raw' => $c->updated_at,
            'read' => false,
            'type' => 'concern_resolved',
        ]);

    // Merge lahat ng notifications, sort by newest first, at lagyan ng human-readable timestamp
    return $notifResidents
        ->merge($notifEvents)
        ->merge($notifProposals)
        ->merge($notifServices)
        ->merge($notifConcerns)
        ->sortByDesc(fn($n) => $n['created_at_raw'])
        ->map(fn($n) => [
            ...$n,
            'created_at' => $n['created_at_raw']->diffForHumans()
        ])
        ->values();
}


    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Barangays page
    public function showBarangays()
    {
        $barangays = Barangay::withCount([
            'residents as approved_residents_count' => fn($query) => $query->where('status', 'approved')
        ])->orderBy('name')->get();

        return view('abc.barangays.index', compact('barangays'));
    }

    // Events page
 public function events(Request $request)
{
$search    = $request->input('search');
$barangay  = $request->input('barangay');
$postedBy  = $request->input('posted_by');
$month     = $request->input('month');
$year      = $request->input('year');

// ✅ Base query for approved events
$approvedQuery = Event::where('status', 'approved')
    ->with(['barangay', 'eventImages'])
    ->when($search, function ($q) use ($search) {
        $q->where(function ($sub) use ($search) {
            $sub->where('title', 'like', "%{$search}%")
                ->orWhere('details', 'like', "%{$search}%");
        });
    })
    ->when($barangay, fn($q) => $q->where('barangay_id', $barangay))
    ->when($postedBy, fn($q) => $q->where('posted_by', $postedBy))
    ->when($month, fn($q) => $q->whereMonth('date', $month))
    ->when($year, fn($q) => $q->whereYear('date', $year));

// ✅ Get data and counts
$approvedEvents = (clone $approvedQuery)
    ->orderByDesc('created_at')
    ->paginate(10)
    ->withQueryString();

$approvedCount = (clone $approvedQuery)->count();

// ✅ Get barangays for dropdown
$barangays = Barangay::orderBy('name')->get();

return view('abc.events.index', compact(
    'approvedEvents',
    'barangays',
    'approvedCount',
    'search',
    'barangay',
    'postedBy',
    'month',
    'year'
));

}

    // Toggle user status
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->status === 'inactive') {
            $exists = User::where('barangay_id', $user->barangay_id)
                ->where('role', $user->role)
                ->where('status', 'active')
                ->where('id', '!=', $user->id)
                ->first();

            if ($exists) {
                $barangayName = $exists->barangay->name ?? 'Unknown';
                return back()->with('error',
                    ucfirst($user->role) . " is already active in Barangay {$barangayName}. Deactivate them first before reactivating this account."
                );
            }
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return back()->with('success', 'Account status updated successfully.');
    }
}
