<?php



namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;


class EventController extends Controller
{
   public function index(Request $request)
{
// --- Get query parameters ---
$status   = $request->query('status', 'pending');
$search   = $request->query('search');
$postedBy = $request->query('posted_by');
$month    = $request->query('month');
$year     = $request->query('year');


// --- Base query for events in the user's barangay ---
$eventsQuery = Event::with('images')
    ->where('barangay_id', auth()->user()->barangay_id);

// Filter by status (if not 'all')
if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'])) {
    $eventsQuery->where('status', $status);
}

// Filter by posted_by
if ($postedBy && in_array($postedBy, ['barangay', 'sk'])) {
    $eventsQuery->where('posted_by', $postedBy);
}

// Filter by month
if ($month) {
    $eventsQuery->whereMonth('date', $month);
}

// Filter by year
if ($year) {
    $eventsQuery->whereYear('date', $year);
}

// Search filter
if ($search) {
    $eventsQuery->where(function ($query) use ($search) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('venue', 'like', "%{$search}%")
              ->orWhere('details', 'like', "%{$search}%");
    });
}

// Paginate results
$events = $eventsQuery->latest()->paginate(10)->withQueryString();

// --- Dynamic counts for status cards ---
$countQuery = Event::where('barangay_id', auth()->user()->barangay_id);

if ($postedBy && in_array($postedBy, ['barangay', 'sk'])) {
    $countQuery->where('posted_by', $postedBy);
}

if ($month) {
    $countQuery->whereMonth('date', $month);
}

if ($year) {
    $countQuery->whereYear('date', $year);
}

if ($search) {
    $countQuery->where(function ($query) use ($search) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhere('venue', 'like', "%{$search}%")
              ->orWhere('details', 'like', "%{$search}%");
    });
}

$counts = [
    'all'      => (clone $countQuery)->count(),
    'pending'  => (clone $countQuery)->where('status', 'pending')->count(),
    'approved' => (clone $countQuery)->where('status', 'approved')->count(),
    'rejected' => (clone $countQuery)->where('status', 'rejected')->count(),
];

return view('secretary.events.index', compact(
    'events', 'status', 'counts', 'search', 'postedBy', 'month', 'year'
));


}

    
    

    public function create()
    {
        return view('secretary.events.create')->with('success', 'Event added successfully!');
    }

 

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'details' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
             // <-- idagdag ito
        ]);
    
        $data['barangay_id'] = auth()->user()->barangay_id;
        $data['status'] = 'pending';
    
        $event = Event::create($data);
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('events', 'public');
                    $event->images()->create(['path' => $path]);
                }
            }
        }
    
        return redirect()->back()->with('success', 'Event Saved.');
    }
    
    // Show edit form
public function edit($id)
{
    $event = Event::findOrFail($id);
    return view('secretary.events.edit', compact('event'));
}

// Update event
public function update(Request $request, Event $event)
{
    // Validate input
    $request->validate([
        'title' => 'required|string|max:555',
        'date' => 'required|date',
        'time' => 'required',
        'venue' => 'required|string|max:255',
       'details' => 'required|string|max:500',

        'images.*' => 'nullable|image|max:2048', // 2MB per image
    ]);

    // Update event fields including posted_by
    $event->update([
        'title' => $request->title,
        'date' => $request->date,
        'time' => $request->time,
        'venue' => $request->venue,
        'details' => $request->details,
        
    ]);

    // Delete selected images
    if ($request->has('delete_images')) {
        foreach ($request->delete_images as $imageId) {
            $image = $event->images()->find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->path); // remove file
                $image->delete(); // remove DB record
            }
        }
    }

    // Upload new images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $uploadedImage) {
            $path = $uploadedImage->store('events', 'public');
            $event->images()->create([
                'path' => $path,
            ]);
        }
    }

    return redirect()->route('secretary.events.index')
                     ->with('success', 'Event updated successfully!');
}


// Delete event
public function destroy($id)
{
    $event = Event::findOrFail($id);

    // Optionally delete images too
    foreach ($event->images as $image) {
        \Storage::delete('public/' . $image->path);
        $image->delete();
    }

    $event->delete();

    return redirect()->route('secretary.events.index')->with('success', 'Event deleted successfully.');
}
public function destroyMultiple(Request $request)
{
    $ids = $request->input('selected_events', []);
    
    if (!empty($ids)) {
        Event::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Selected events deleted.');
    }

    return redirect()->back()->with('error', 'No events selected.');
}

public function pending()
{
    $events = Event::with('images')
                ->where('status', 'pending') // or whatever your pending status is
                ->where('barangay_id', auth()->user()->barangay_id)
                ->latest()
                ->get();

    return view('secretary.events.pending', compact('events'));
}
public function rejected()
{
    $events = Event::with('images')
                ->where('status', 'rejected')
                ->where('barangay_id', auth()->user()->barangay_id)
                ->latest()
                ->get();

    return view('secretary.events.rejected', compact('events'));
}
public function resubmit(Event $event)
{
    // Optional: check if the current user owns the event
    if ($event->barangay_id !== auth()->user()->barangay_id) {
        abort(403);
    }

    $event->update(['status' => 'pending']);

    return redirect()->route('secretary.events.index')->with('success', 'Event resubmitted for approval.');
}
public function apiIndex(Request $request)
{
    $barangayId = $request->query('barangay_id');

    $query = Event::with('images')
        ->where('status', 'approved')
        ->whereDate('date', '>=', now()->toDateString()); // ✅ include today

    if ($barangayId) {
        $query->where('barangay_id', $barangayId);
    }

    $events = $query->latest()->get();

    return response()->json($events->map(function ($event) {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'date' => $event->date,
            'time' => $event->time,
            'venue' => $event->venue,
            'details' => $event->details,
            'status' => $event->status,
            'barangay_id' => $event->barangay_id,
            'posted_by' => $event->posted_by,
            'updated_date' => $event->updated_at,
            'images' => $event->images->map(fn ($img) => ['path' => asset('storage/' . $img->path)]),
        ];
    }));
}




}

