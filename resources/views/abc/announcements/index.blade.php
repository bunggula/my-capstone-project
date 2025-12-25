@extends('layouts.app')

@section('title', 'Municipality Residents')

@section('content')
<div x-data="editModal()" class="flex h-screen overflow-hidden bg-gray-100">

    @include('partials.abc-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.abc-header', ['title' => 'Municipality Residents'])

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                {{-- 📢 Title --}}
<h1 class="text-3xl font-bold text-gray-800 mb-4">Announcements</h1>
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
{{-- 📊 Target Audience Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">

    {{-- All Announcements --}}
    <a href="{{ route('abc.announcements.index', ['target_type' => 'announcements']) }}"
       class="flex items-center justify-between p-4 rounded shadow border-l-4 {{ request('target_type') === 'announcements' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-500 text-gray-700' }}">
        <div>
            <h4 class="font-semibold text-sm">All Announcements</h4>
            <p class="text-2xl font-bold">{{ $allAnnouncementsCount }}</p>
        </div>
        <div class="p-3 bg-gray-300 rounded-full ml-auto">
            {{-- 📰 Newspaper Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z" />
            </svg>
        </div>
    </a>

    {{-- All Barangays --}}
    <a href="{{ route('abc.announcements.index', ['target_type' => 'all']) }}"
       class="flex items-center justify-between p-4 rounded shadow border-l-4 {{ request('target_type') === 'all' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700' }}">
        <div>
            <h4 class="font-semibold text-sm">All Barangays</h4>
            <p class="text-2xl font-bold">{{ $allBarangaysCount }}</p>
        </div>
        <div class="p-3 bg-green-300 rounded-full ml-auto">
            {{-- 🌍 Globe Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>
        </div>
    </a>

    {{-- Specific Barangay --}}
    <a href="{{ route('abc.announcements.index', ['target_type' => 'specific_barangay']) }}"
       class="flex items-center justify-between p-4 rounded shadow border-l-4 {{ request('target_type') === 'specific_barangay' ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700' }}">
        <div>
            <h4 class="font-semibold text-sm">Specific Barangay</h4>
            <p class="text-2xl font-bold">{{ $specificBarangayCount }}</p>
        </div>
        <div class="p-3 bg-blue-300 rounded-full ml-auto">
            {{-- 🏘️ Home Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 9.75L12 4l9 5.75V20a2 2 0 01-2 2H5a2 2 0 01-2-2V9.75z" />
            </svg>
        </div>
    </a>

    {{-- Specific Role --}}
    <a href="{{ route('abc.announcements.index', ['target_type' => 'specific_role']) }}"
       class="flex items-center justify-between p-4 rounded shadow border-l-4 {{ request('target_type') === 'specific_role' ? 'bg-purple-200 border-purple-600 text-purple-900' : 'bg-purple-100 border-purple-500 text-purple-700' }}">
        <div>
            <h4 class="font-semibold text-sm">Specific Role</h4>
            <p class="text-2xl font-bold">{{ $specificRoleCount }}</p>
        </div>
        <div class="p-3 bg-purple-300 rounded-full ml-auto">
            {{-- 👤 User Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </a>

</div>



{{-- 🔍 Search + Filter (left) + Add Button (right) --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

    {{-- 🔍 Search + Filter Container --}}
    <form id="filterForm" method="GET" action="{{ route('abc.announcements.index') }}" 
          class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

        {{-- 🔍 Search Bar --}}
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                id="searchInput"
                name="search"
                value="{{ request('search') }}" 
                placeholder="Search announcements..." 
                class="border border-gray-300 rounded px-4 py-2 pr-10 w-full text-sm focus:ring focus:border-blue-500"
            >

            {{-- ✖ Clear Button --}}
            <button 
                type="button"
                id="clear-search"
                class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 text-sm"
                title="Clear search"
                onclick="document.getElementById('searchInput').value=''; filterTable();"
            >
                &times;
            </button>
        </div>

        {{-- 🗓 Month Filter --}}
        <select name="month" 
                class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
            <option value=""> Select Months</option>
            @foreach (range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                </option>
            @endforeach
        </select>

        {{-- 📅 Year Filter --}}
        <select name="year" 
                class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
            <option value="">Select Years</option>
            @foreach (range(date('Y'), date('Y') - 5) as $y)
                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endforeach
        </select>

        {{-- 🔍 Filter Button --}}
        <button type="submit"
                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">
            Search
        </button>

        {{-- 🧹 Clear All Filters --}}
        @if(request('search') || request('month') || request('year'))
            <a href="{{ route('abc.announcements.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow transition">
                Clear
            </a>
        @endif
    </form>

    {{-- ➕ Add Button --}}
    <a @click.prevent="openForm = true"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow cursor-pointer w-fit">
        ➕ Add Announcement
    </a>
</div>


{{-- 📜 Results Info --}}
@if(request('search') || request('posted_by'))
    <p class="text-sm text-gray-500 text-right mb-4">
        Showing results 
        @if(request('search'))
            for title: <strong>{{ request('search') }}</strong>
        @endif
        @if(request('posted_by'))
            @if(request('search')) and @endif
            posted by:
            <strong>
                @switch(request('posted_by'))
                    @case('abc_president') ABC President @break
                    @case('sk_president') SK President @break
                    @default {{ ucfirst(request('posted_by')) }}
                @endswitch
            </strong>
        @endif
    </p>
@endif





                    @if ($announcements->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-blue-600 text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left">#</th>
                                        <th class="px-4 py-3 text-left">Title</th>
                                        <th class="px-4 py-3 text-left">Date/Time</th>
                                        
                                       <th class="px-4 py-3 text-left">Posted Date</th> 
                                        <th class="px-4 py-3 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($announcements as $announcement)
                                    <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-2 font-semibold text-gray-800">{{ $announcement->title }}</td>
                                         <td class="px-4 py-2">
    {{ \Carbon\Carbon::parse($announcement->date . ' ' . $announcement->time)->format('F j, Y | g:i A') }}
</td>

<td class="px-4 py-2 text-gray-700"> {{-- 🆕 Posted Date --}}
    {{ $announcement->created_at->format('F j, Y | g:i A') }}
</td>

                                                <td class="px-4 py-2 text-center space-x-1">
    {{-- 👁️ View --}}
    <button
        @click="viewAnnouncement({{ $announcement->id }})"
        class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
        title="View"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
        </svg>
    </button>

    {{-- ✏️ Edit --}}
   <button @click="openEdit({{ $announcement->id }})"
       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-md transition"
        title="Edit"
    >
    <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor"
             class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>
    </button>

    {{-- 🗑️ Delete --}}
 <form method="POST" action="{{ route('abc.announcements.destroy', $announcement->id) }}" class="inline delete-form">
    @csrf
    @method('DELETE')
    <button type="button"
        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-md transition delete-btn"
        title="Delete"
        data-title="{{ $announcement->title }}"  {{-- add this --}}
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 011 1v0a1 1 0 01-1 1H7a1 1 0 01-1-1v0a1 1 0 011-1h10z"/>
        </svg>
    </button>
</form>

</td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $announcements->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-20 text-lg">No announcements posted yet.</p>
                    @endif
                </div>
            </div>
        </section>
    </main>

    {{-- ✅ Add Form Modal --}}
    <div x-show="openForm" x-transition x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class=" w-full max-w-2xl p-6 rounded  relative">
           
            
            <form action="{{ route('abc.announcements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('abc.announcements._form', ['barangays' => $barangays])
            </form>
        </div>
    </div>
   {{-- ✅ Edit Modal (using partial) --}}
<div x-show="openEditModal" x-transition x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-3xl p-6 rounded-2xl shadow-xl relative overflow-y-auto"
         style="max-height: 90vh;">
         
        <!-- Close Button -->
        <button @click="openEditModal = false"
                class="absolute top-4 right-4 text-gray-600 hover:text-red-500 text-2xl">&times;</button>

        <!-- Title -->
        <h2 class="text-lg font-bold mb-6">✏️ Edit Announcement</h2>

        <!-- Content -->
        @foreach($announcements as $announcement)
            <div x-show="openEditModal && editId === {{ $announcement->id }}">
                @include('abc.announcements._edit_form', ['announcement' => $announcement, 'barangays' => $barangays])
            </div>
        @endforeach
    </div>
</div>




{{-- ✅ View Modal --}}
<div x-show="openViewModal" x-transition x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-2xl p-6 rounded shadow-lg relative">
        <button @click="openViewModal = false"
                class="absolute top-2 right-3 text-gray-600 hover:text-red-500 text-xl">&times;</button>

       

        <div x-html="viewData" x-init="$nextTick(() => Alpine.initTree($el))"></div>
    </div>
</div>


{{-- ✅ SweetAlert2 for Delete Confirmation --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = button.closest('form');
            const title = button.dataset.title; // get title from data-title
            Swal.fire({
                title: `Delete "${title}"?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

function editModal() {
    return {
        openForm: false,
        openEditModal: false,
        openViewModal: false,
        editId: null, // store which announcement is being edited
        viewData: '',

        openEdit(announcementId) {
            this.editId = announcementId;
            this.openEditModal = true;
        },

        viewAnnouncement(id) {
            fetch(`/abc/announcements/${id}`)
                .then(res => res.text())
                .then(html => {
                    this.viewData = html;
                    this.openViewModal = true;
                })
                .catch(err => {
                    alert('Failed to load announcement.');
                    console.error(err);
                });
        }
    }
}


 document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const rows = document.querySelectorAll('#announcementsTable tbody tr');

    searchInput.addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
});

</script>
@endsection
