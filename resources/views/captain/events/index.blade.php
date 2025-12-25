@extends('layouts.app')

@section('content')
<div class="flex font-sans min-h-screen overflow-hidden">
    {{-- Sidebar --}}
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        @include('partials.captain-sidebar')
    </div>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">
        {{-- Header --}}
        <div class="sticky top-0 z-10 bg-white shadow">
            @include('partials.captain-header', ['title' => '📄 Pending Document Requests'])
        </div>

        {{-- Page Content --}}
        <div class="p-6 overflow-y-auto flex-1">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Events and News</h2>

        @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>


@endif


{{-- 🔍 Filter Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    {{-- Pending --}}
 {{-- Pending --}}
<a href="{{ route('captain.events.index', array_merge(request()->all(), ['status' => 'pending'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ $currentStatus === 'pending' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-yellow-100 border-yellow-500 text-yellow-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Pending</h4>
            <p class="text-xl font-bold">{{ $counts['pending'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
        </svg>
    </div>
</a>

{{-- Approved --}}
<a href="{{ route('captain.events.index', array_merge(request()->all(), ['status' => 'approved'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ $currentStatus === 'approved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Approved</h4>
            <p class="text-xl font-bold">{{ $counts['approved'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
</a>

{{-- Rejected --}}
<a href="{{ route('captain.events.index', array_merge(request()->all(), ['status' => 'rejected'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ $currentStatus === 'rejected' ? 'bg-red-200 border-red-600 text-red-900' : 'bg-red-100 border-red-500 text-red-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Rejected</h4>
            <p class="text-xl font-bold">{{ $counts['rejected'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </div>
</a>

{{-- All Events --}}
<a href="{{ route('captain.events.index', array_merge(request()->all(), ['status' => 'all'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ $currentStatus === 'all' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">All Events</h4>
            <p class="text-xl font-bold">{{ $counts['all'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18" />
        </svg>
    </div>
</a>


</div>
<div class="mb-6">
    <form method="GET" action="{{ route('captain.events.index') }}" id="searchForm">
        <div class="flex flex-col sm:flex-row flex-wrap gap-2 items-center">


        <!-- 🔍 Search Input -->
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                name="search" 
                id="searchInput"
                value="{{ request('search') }}" 
                placeholder="Search by title..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 100000)">
            
        </div>

        <!-- 🗓 Month Filter -->
        <div>
            <select name="month" id="monthSelect" 
                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500"
                    onchange="toggleClearAllButton(); document.getElementById('searchForm').submit();">
                <option value="">All Months</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 📅 Year Filter -->
        <div>
            <select name="year" id="yearSelect" 
                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500"
                    onchange="toggleClearAllButton(); document.getElementById('searchForm').submit();">
                <option value="">All Years</option>
                @foreach(range(date('Y'), date('Y') - 5) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 🧹 Clear All Button -->
        <div>
            <button 
                type="button" 
                id="clearAllBtn" 
                onclick="clearAllFilters()" 
                class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm hidden">
                Clear
            </button>
        </div>
    </div>
</form>


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    toggleClearAllButton();
});

function toggleClearAllButton() {
    const search = document.getElementById('searchInput').value;
    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('yearSelect').value;
    const btn = document.getElementById('clearAllBtn');

    btn.style.display = (search || month || year) ? 'inline-block' : 'none';
}

function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('monthSelect').value = '';
    document.getElementById('yearSelect').value = '';
    document.getElementById('searchForm').submit();
}
</script>



                {{-- Table --}}
                
                <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
    <table class="w-full table-auto border text-sm text-left">
        <thead class="bg-blue-600 text-white uppercase text-xs">
        <tr>
        <th class="px-4 py-3 border-b">#</th> <!-- Added this line for numbering -->
        <th class="px-4 py-3 border-b">Title</th>
        <th class="px-4 py-3 border-b">Date</th>
        <th class="px-4 py-3 border-b">Time</th>
        <th class="px-4 py-3 border-b">Status</th>
        <th class="px-4 py-3 border-b text-center">Action</th>
    </tr>
</thead>
<tbody>
    @forelse ($events as $event)
        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }} border-t hover:bg-gray-100 transition">
            <td class="px-4 py-3 border-b text-gray-600">{{ $loop->iteration }}</td> <!-- Row number -->
            <td class="px-4 py-3 border-b text-gray-800">{{ $event->title }}</td>
            <td class="px-4 py-3 border-b text-gray-700">
                {{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}
            </td>
            <td class="px-4 py-3 border-b text-gray-700">
                {{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}
            </td>
            <td class="px-4 py-3 border-b">
                <span class="px-2 py-1 rounded text-xs font-medium
                    {{
                        $event->status === 'approved' ? 'bg-green-100 text-green-700' :
                        ($event->status === 'rejected' ? 'bg-red-100 text-red-700' :
                        'bg-yellow-100 text-yellow-700')
                    }}">
                    {{ ucfirst($event->status) }}
                </span>
            </td>
            <td class="px-4 py-3 border-b text-center">
    <div class="flex justify-center items-center space-x-2">

        {{-- 🔍 View Button --}}
        <button 
    onclick="showEventModal(this)"
    class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition"
    title="View Event Details"
    data-title="{{ $event->title }}"
    data-date="{{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}"
    data-time="{{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}"
    data-venue="{{ $event->venue }}"
    data-posted-by="{{ $event->posted_by }}"
    data-created-at="{{ $event->created_at }}"
    data-updated-at="{{ $event->updated_at }}"
    data-details="{{ $event->details }}"
    data-status="{{ ucfirst($event->status) }}"
    data-reason="{{ $event->rejection_reason ?? 'N/A' }}"
    data-images='@json($event->images->map(fn($img) => asset("storage/" . $img->path)))'
>
    <!-- Eye Icon -->
      <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 12s3.75-6.75 9.75-6.75
                     9.75 6.75 9.75 6.75-3.75 6.75-9.75
                     6.75S2.25 12 2.25 12z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
</button>


        @php
            $today = \Carbon\Carbon::today();
            $eventDate = \Carbon\Carbon::parse($event->date);
        @endphp

        @if (strtolower($event->status) === 'pending' && $eventDate->isFuture())
            {{-- ✅ Approve Button --}}
            <form action="{{ route('captain.events.approve', $event->id) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button 
                    type="button"
                    onclick="confirmApprove(this, '{{ $event->title }}')"
                    class="bg-green-600 text-white p-2 rounded hover:bg-green-700 transition"
                    title="Approve Event"
                >
                    <!-- Check Circle Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </form>

            {{-- ❌ Reject Button --}}
            <form action="{{ route('captain.events.reject', $event->id) }}" method="POST" class="inline reject-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="reason">
                <input type="hidden" name="notes">
                <button 
                    type="button"
                    onclick="openRejectPrompt(this, '{{ $event->title }}')"
                    class="bg-red-600 text-white p-2 rounded hover:bg-red-700 transition"
                    title="Reject Event"
                >
                    <!-- X Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" viewBox="0 0 24 24" 
                         stroke-width="1.5" stroke="currentColor" 
                         class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>

        @elseif (strtolower($event->status) === 'pending' && $eventDate->isPast())
            {{-- ❌ Reject Button Only --}}
            <form action="{{ route('captain.events.reject', $event->id) }}" method="POST" class="inline reject-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="reason">
                <input type="hidden" name="notes">
                <button 
                    type="button"
                    onclick="openRejectPrompt(this, '{{ $event->title }}')"
                    class="bg-red-600 text-white p-2 rounded hover:bg-red-700 transition"
                    title="Reject Event"
                >
                    <!-- X Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" viewBox="0 0 24 24" 
                         stroke-width="1.5" stroke="currentColor" 
                         class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        @endif

    </div>
</td>



                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500 py-6">
                                        No events found for your barangay.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                {{ $events->appends(request()->query())->links() }}

                </div>

            </div>
        </div>
    </main>
</div>

<!-- 🧾 EVENT MODAL -->
<div id="eventModal" 
     class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden p-4">
  
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl relative border border-gray-200 
              max-h-[90vh] flex flex-col">
    
    <!-- ❌ Close Button -->
    <button onclick="closeEventModal()" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>

    <!-- 📄 Modal Content -->
    <div class="p-6 sm:p-8 overflow-y-auto">
      
      <!-- Title -->
      <h2 id="modalTitle" class="text-2xl sm:text-3xl font-bold text-blue-800 mb-6 border-b pb-4">
        Event Title
      </h2>

      <!-- Event Info Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-4">

        <!-- 📅 Date & Time -->
        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm md:col-span-2">
          <p class="text-gray-500 font-semibold uppercase text-xs">Date & Time</p>
          <p class="mt-1 text-gray-900 text-sm font-medium" id="modalDateTime"></p>
        </div>

        <!-- 🧑‍💼 Posted / Approved / Rejected -->
        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm md:col-span-2">
          <p id="modalPostedLabel" class="text-gray-500 font-semibold uppercase text-xs">Posted On</p>
          <p class="mt-1 text-gray-900 text-sm font-medium" id="modalPostedInfo"></p>
        </div>

        <!-- 🏛️ Venue -->
        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm md:col-span-2">
          <p class="text-gray-500 font-semibold uppercase text-xs">Venue</p>
          <p class="mt-0.5 text-gray-900 text-xs font-medium truncate" id="modalVenue"></p>
        </div>

        <!-- 📊 Status -->
        <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
          <p class="text-gray-500 font-semibold uppercase text-xs">Status</p>
          <span id="modalStatus"
                class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium">
          </span>
        </div>
      </div>

      <!-- 📝 Details -->
   <div class="p-2 bg-gray-50 border border-gray-200 rounded-md shadow-sm text-gray-800 text-xs mb-3">
    <p class="text-gray-500 font-semibold uppercase text-[10px] mb-1">Details</p>
    <div id="modalDetails"
         class="h-32 overflow-y-auto whitespace-pre-line pr-2 p-2 bg-white rounded text-xs leading-relaxed">
    </div>
</div>


      <!-- ❌ Rejection Info -->
      <div id="modalRejection" 
           class="mt-2 p-2 bg-red-50 border border-red-200 rounded-md hidden text-xs">
        <p class="text-red-700 font-semibold">Rejection Reason:</p>
        <p id="modalReason" class="text-red-800 mt-1"></p>
      </div>

      <!-- 📸 Images -->
      <div id="modalImagesGrid" class="mt-6 hidden">
        <p class="text-sm sm:text-base font-medium text-gray-800 mb-3">Event Posters:</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="modalImagesContainer"></div>
      </div>
    </div>
  </div>
</div>

<!-- 🖼️ FULLSCREEN IMAGE MODAL -->
<div id="fullscreenImageModal" class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50 hidden p-4">
  <button onclick="closeFullscreenImage()" class="absolute top-6 right-6 text-white text-4xl font-bold hover:text-red-400 z-50">&times;</button>
  <img id="fullscreenImage" class="max-w-full max-h-full object-contain rounded-lg">
</div>

<script>
    function formatDateTime(dateStr, timeStr) {
  if (!dateStr) return '—';
  const date = new Date(`${dateStr} ${timeStr || ''}`);
  if (isNaN(date)) return '—';
  
  const options = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric', 
    hour: 'numeric', 
    minute: '2-digit', 
    hour12: true 
  };
  return date.toLocaleString('en-US', options);
}

function showEventModal(button) {
    // 🏷️ Title & Venue
    document.getElementById('modalTitle').textContent = button.dataset.title || '';
    document.getElementById('modalVenue').textContent = button.dataset.venue || '';

    // 📅 Date & Time
    const date = button.dataset.date || '—';
    const time = button.dataset.time || '—';
    document.getElementById('modalDateTime').textContent = `${date} • ${time}`;

    // 🧑‍💼 Posted / Approved / Rejected Info
    const postedLabelEl = document.getElementById('modalPostedLabel');
    const postedInfoEl = document.getElementById('modalPostedInfo');
    const postedBy = button.dataset.postedBy || '';
    const status = (button.dataset.status || '').toLowerCase();

    // Tukuyin kung anong date at label ang gagamitin
    let dateLabel = '';
    let labelTitle = '';
    if (status === 'approved') {
        dateLabel = formatDateTime(button.dataset.updatedAt);
        labelTitle = 'Approved On';
    } else if (status === 'rejected') {
        dateLabel = formatDateTime(button.dataset.updatedAt);
        labelTitle = 'Rejected On';
    } else {
        dateLabel = formatDateTime(button.dataset.createdAt);
        labelTitle = 'Posted On';
    }
    postedLabelEl.textContent = labelTitle;

    // Tukuyin kung sino ang nag-post
    const postedByLabel =
        postedBy === 'barangay'
            ? 'Barangay Council'
            : postedBy === 'sk'
            ? 'Sanggunihan'
            : 'N/A';

    // Combine date + posted by
    postedInfoEl.textContent = `${dateLabel} • ${postedByLabel}`;

    // 📊 Status
    const statusEl = document.getElementById('modalStatus');
    statusEl.textContent = button.dataset.status || '';
    statusEl.className = "inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium"; // reset classes
    if (status === "pending") {
        statusEl.classList.add("bg-yellow-100", "text-yellow-700");
    } else if (status === "approved") {
        statusEl.classList.add("bg-green-100", "text-green-700");
    } else if (status === "rejected") {
        statusEl.classList.add("bg-red-100", "text-red-700");
    } else {
        statusEl.classList.add("bg-gray-100", "text-gray-700");
    }

    // 📝 Details
    document.getElementById('modalDetails').textContent = button.dataset.details || '';

    // ❌ Rejection Info
    if (status === 'rejected') {
        document.getElementById('modalRejection').classList.remove('hidden');
        document.getElementById('modalReason').textContent = button.dataset.reason || 'N/A';
    } else {
        document.getElementById('modalRejection').classList.add('hidden');
    }

    // 📸 Images
    const imagesContainer = document.getElementById('modalImagesContainer');
    const imagesGrid = document.getElementById('modalImagesGrid');
    const images = JSON.parse(button.dataset.images || '[]');

    imagesContainer.innerHTML = ''; // clear old images
    if (images.length > 0) {
   images.forEach(path => {
        const div = document.createElement('div');
       div.className = 'relative group overflow-hidden rounded-lg shadow-sm cursor-pointer flex items-center justify-center h-35 sm:h-40';
        div.innerHTML = `<img src="${path}" class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-105 group-hover:brightness-90" onclick="showFullscreenImage('${path}')">`;
        imagesContainer.appendChild(div);
    });

        imagesGrid.classList.remove('hidden');
    } else {
        imagesGrid.classList.add('hidden');
    }

    // Show modal
    document.getElementById('eventModal').classList.remove('hidden');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.add('hidden');
}

function showFullscreenImage(src) {
    document.getElementById('fullscreenImage').src = src;
    document.getElementById('fullscreenImageModal').classList.remove('hidden');
}

function closeFullscreenImage() {
    document.getElementById('fullscreenImageModal').classList.add('hidden');
}

    // Auto-hide success message
    setTimeout(() => {
        const msg = document.getElementById('success-message');
        if (msg) msg.style.opacity = '0';
    }, 3000);
 
    function confirmApprove(button, title) {
    Swal.fire({
        title: 'Approve "' + title + '"?',
        text: 'This will mark the event "' + title + '" as approved.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a', // green
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}


    function confirmReject(button) {
        Swal.fire({
            title: 'Reject Event"'+ title+ '"?',
            text: 'This will mark the event "' + title + '" as rejected.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', // red
            cancelButtonColor: '#6b7280', // gray
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    function openRejectPrompt(button, title) {
    Swal.fire({
        title: `Reject Event "${title}"?`,
        text: 'Please select a reason to reject this event.',
        input: 'select',
        inputOptions: {
            'Incomplete details': 'Incomplete details',
            'Conflicting schedule': 'Conflicting schedule',
            'Not aligned with barangay goals': 'Not aligned with barangay goals',
            'Other': 'Other'
        },
        inputPlaceholder: 'Select a reason',
        showCancelButton: true,
        confirmButtonText: 'Next',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('You must select a reason');
            }
            return reason;
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        const reason = result.value;

        // Kapag "Other", mag second prompt para sa custom input
        if (reason === 'Other') {
            Swal.fire({
                title: 'Enter Reason',
                input: 'text',
                inputPlaceholder: 'Type the rejection reason here...',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: (customReason) => {
                    if (!customReason.trim()) {
                        Swal.showValidationMessage('You must enter a reason');
                    }
                    return customReason;
                }
            }).then((result2) => {
                if (result2.isConfirmed) {
                    submitRejectionForm(button, result2.value);
                }
            });
        } else {
            // Diretso submit kapag hindi "Other"
            submitRejectionForm(button, reason);
        }
    });
}

function submitRejectionForm(button, reason) {
    const form = button.closest('form');
    form.querySelector('input[name="reason"]').value = reason;
    form.submit();
}



const searchInput = document.getElementById('search-input');
    let timeout = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('search-form').submit();
        }, 1000); // wait 500ms after typing before submit
    });

</script>
@endsection
