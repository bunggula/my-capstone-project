<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;

class EventApprovalController extends Controller
{
  public function index(Request $request)
{
    $user = auth()->user();
    $barangayId = $user->barangay_id;

    $status = $request->get('status', 'pending');
    $search = $request->get('search');
    $postedBy = $request->get('posted_by');
    $month = $request->get('month');
    $year = $request->get('year');

    // ✅ Base query for barangay events
    $eventsQuery = Event::where('barangay_id', $barangayId);

    // 🔍 Search filter
    if ($search) {
        $eventsQuery->where('title', 'like', '%' . $search . '%');
    }

    // 👤 Posted By filter
    if ($postedBy && in_array($postedBy, ['barangay', 'sk'])) {
        $eventsQuery->where('posted_by', $postedBy);
    }

    // 🗓 Month & Year filters
    if ($month) {
        $eventsQuery->whereMonth('date', $month);
    }

    if ($year) {
        $eventsQuery->whereYear('date', $year);
    }

    // 🔄 Auto-switch tab kapag may search at hindi “all”
    if ($search && !$request->has('status')) {
        $searchQuery = Event::where('barangay_id', $barangayId)
            ->when($search, fn($q) => $q->where('title', 'like', '%' . $search . '%'))
            ->when($postedBy && in_array($postedBy, ['barangay', 'sk']), fn($q) => $q->where('posted_by', $postedBy))
            ->when($month, fn($q) => $q->whereMonth('date', $month))
            ->when($year, fn($q) => $q->whereYear('date', $year));

        $firstMatch = $searchQuery->first();
        if ($firstMatch && $status !== $firstMatch->status) {
            return redirect()->route('captain.events.index', [
                'status' => $firstMatch->status,
                'search' => $search,
                'posted_by' => $postedBy,
                'month' => $month,
                'year' => $year,
            ]);
        }
    }

    // 📌 Status filter (skip if “all”)
    if ($status !== 'all') {
        $eventsQuery->where('status', $status);
    }

    // 🔹 Paginate results
    $events = $eventsQuery->latest()->paginate(10)->withQueryString();

    // 📊 Counts (with filters)
    $countQuery = Event::where('barangay_id', $barangayId);

    if ($search) {
        $countQuery->where('title', 'like', '%' . $search . '%');
    }

    if ($postedBy && in_array($postedBy, ['barangay', 'sk'])) {
        $countQuery->where('posted_by', $postedBy);
    }

    if ($month) {
        $countQuery->whereMonth('date', $month);
    }

    if ($year) {
        $countQuery->whereYear('date', $year);
    }

    $counts = [
        'all'      => (clone $countQuery)->count(),
        'pending'  => (clone $countQuery)->where('status', 'pending')->count(),
        'approved' => (clone $countQuery)->where('status', 'approved')->count(),
        'rejected' => (clone $countQuery)->where('status', 'rejected')->count(),
    ];

    return view('captain.events.index', [
        'events' => $events,
        'counts' => $counts,
        'currentStatus' => $status,
        'search' => $search,
        'postedBy' => $postedBy,
        'month' => $month,
        'year' => $year,
    ]);
}


    
    

    public function approve(Event $event)
    {
        $event->update(['status' => 'approved']);

        // Notify the secretary
        $secretary = User::where('barangay_id', $event->barangay_id)
                         ->where('role', 'secretary')
                         ->first();

        if ($secretary) {
            Notification::create([
                'user_id' => $secretary->id,
                'message' => 'Your event "' . $event->title . '" has been approved.',
                'type' => 'event_approved',
            ]);
        }

        return back()->with('success', 'Event approved successfully.');
    }

    // Reject a specific event
    public function reject(Request $request, Event $event)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'rejection_notes' => $request->notes,
        ]);

        // Notify the secretary
        $secretary = User::where('barangay_id', $event->barangay_id)
                         ->where('role', 'secretary')
                         ->first();

        if ($secretary) {
            $message = 'Your event "' . $event->title . '" was rejected. Reason: ' . $request->reason;
            if ($request->notes) {
                $message .= ' | Notes: ' . $request->notes;
            }

            Notification::create([
                'user_id' => $secretary->id,
                'message' => $message,
                'type' => 'event_rejected',
            ]);
        }

        return back()->with('success', 'Event rejected successfully.');
    }

    // Show approved events for the barangay
    public function approved()
    {
        $events = Event::where('barangay_id', auth()->user()->barangay_id)
                       ->where('status', 'approved')
                       ->latest()
                       ->paginate(10); // ✅ paginated

        return view('captain.events.approved', compact('events'));
    }

    // Show rejected events for the barangay
    public function rejected()
    {
        $events = Event::where('barangay_id', auth()->user()->barangay_id)
                       ->where('status', 'rejected')
                       ->latest()
                       ->paginate(10); // ✅ paginated

        return view('captain.events.rejected', compact('events'));
    }
}
