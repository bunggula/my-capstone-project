@extends('layouts.app')

@section('title', 'Municipality Barangay Officials')

@section('content')
<div x-data="residentViewer()" class="flex h-screen overflow-hidden bg-gray-100">
    @include('partials.abc-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        
        
        
        @include('partials.abc-header', ['title' => 'Municipality Residents'])
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
   <div class="overflow-x-auto">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                     <h2 class="text-2xl font-bold text-blue-900 mb-6">Official Accounts</h2>
                {{-- 🔍 Active/Inactive/All Filter Summary Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">



{{-- 🟢 Active Accounts Card --}}
<a href="{{ route('abc.accounts.list', array_merge(request()->except('status'), ['status' => 'active'])) }}"
   class="block p-4 rounded shadow border-l-4 
          {{ (request('status') === 'active' || !request('status')) 
              ? 'bg-green-200 border-green-600 text-green-900' 
              : 'bg-green-100 border-green-500 text-green-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold text-sm">
                Active Accounts
                <span class="text-xs text-gray-600">
                    ({{ request('barangay') ? $barangays->firstWhere('id', request('barangay'))->name : 'All Barangays' }})
                </span>
            </h4>
            <p class="text-2xl font-bold">{{ $statusCounts['active'] ?? 0 }}</p>
        </div>
        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
</a>


{{-- 🔴 Inactive Accounts Card --}}
<a href="{{ route('abc.accounts.list', array_merge(request()->except('status'), ['status' => 'inactive'])) }}"
   class="block p-4 rounded shadow border-l-4 
          {{ request('status') === 'inactive' ? 'bg-red-200 border-red-600 text-red-900' : 'bg-red-100 border-red-500 text-red-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold text-sm">
                Inactive Accounts
                <span class="text-xs text-gray-600">
                    ({{ request('barangay') ? $barangays->firstWhere('id', request('barangay'))->name : 'All Barangays' }})
                </span>
            </h4>
            <p class="text-2xl font-bold">{{ $statusCounts['inactive'] ?? 0 }}</p>
        </div>
        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </div>
</a>

</div>



                <div class="flex justify-between items-end flex-wrap gap-4 mb-6 flex-row-reverse">
    <!-- + Add Account Button -->
    <button onclick="toggleModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow text-sm">
        + Add Account
    </button>
   
 
<!-- Search & Filter Form -->
<form method="GET" id="accountSearchForm" 
      x-data="{ 
          barangay: '{{ request('barangay') }}', 
          role: '{{ request('role') }}' 
      }" 
      class="flex flex-wrap items-end gap-2">

    <!-- Barangay Select -->
    <select 
        name="barangay" 
        x-model="barangay"
        @change="$el.form.submit()"
        class="w-40 border border-gray-300 rounded px-2 py-1 text-sm"
    >
        <option value="">All Barangays</option>
        @foreach($barangays as $brgy)
            <option value="{{ $brgy->id }}">{{ $brgy->name }}</option>
        @endforeach
    </select>

    <!-- Role Select -->
    <select 
        name="role" 
        x-model="role"
        @change="$el.form.submit()"
        class="w-40 border border-gray-300 rounded px-2 py-1 text-sm"
    >
        <option value="">All Roles</option>
        <option value="secretary">Secretary</option>
        <option value="brgy_captain">Barangay Captain</option>
    </select>

    <!-- Clear Button: shows only if any filter is active -->
    <button 
        type="button" 
        x-show="barangay || role"
        @click="
            barangay=''; 
            role='';
            $nextTick(() => $el.form.submit())
        "
        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition"
    >
        Clear
    </button>

</form>


</div>


                {{-- Table --}}
                 <div class="bg-white shadow rounded-lg p-2">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200">
                        <thead class="bg-blue-600 text-white text-left">
                            <tr>
                                <th class="px-4 py-3 border-b">#</th>
                                <th class="px-4 py-3 border-b">Name</th>
                              
                                <th class="px-4 py-3 border-b">Role</th>
                                <th class="px-4 py-3 border-b">Barangay</th>
                           
                                <th class="px-4 py-3 border-b text-center">Status</th>
                                <th class="px-4 py-3 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($users as $user)
                            <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                                <td class="px-4 py-2">{{ $users->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2">
                                   
                                    {{ $user->first_name }}
                                    {{ $user->middle_name ? strtoupper(substr($user->middle_name, 0, 1)) . '.' : '' }}
                                    {{ $user->last_name }}
                                    {{ $user->suffix ?? '' }}
                                </td>
                              
                                <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $user->role) }}</td>
                                <td class="px-4 py-2">{{ $user->barangay->name ?? 'N/A' }}</td>
                              
                                <td class="px-4 py-2 text-center">
                                @if ($user->status === 'active')
    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">
        Active
    </span>
@else
    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
        Inactive
    </span>
@endif

                                </td>
                                <td class="px-4 py-2 text-center space-x-2">
    {{-- View --}}
    {{-- View --}}
<button 
    onclick="loadViewModal({{ $user->id }})" 
    title="View"
    class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-700 text-white hover:bg-blue-600 transition"
>
<svg xmlns="http://www.w3.org/2000/svg" 
                                            class="w-4 h-4" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor" 
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
</button>
{{-- Edit --}}
@if($user->role === 'brgy_captain')
<button 
    onclick="loadEditModal({{ $user->id }})" 
    title="Edit"
    class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-100 hover:bg-yellow-200 text-yellow-600 transition"
>
    <svg xmlns="http://www.w3.org/2000/svg"
         fill="none" viewBox="0 0 24 24"
         stroke-width="1.5" stroke="currentColor"
         class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
    </svg>
</button>
@endif

{{-- Toggle status --}}
@if($user->role === 'brgy_captain')
<form id="toggle-form-{{ $user->id }}" action="{{ route('abc.toggle_status', $user->id) }}" method="POST" class="inline">
    @csrf
    @method('PATCH')
    <button 
        type="button"
        onclick="confirmToggle({{ $user->id }}, '{{ $user->status }}', '{{ $user->first_name }} {{ $user->last_name }}', '{{ $user->barangay->name ?? 'N/A' }}')"
        title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}"
        class="inline-flex items-center justify-center w-8 h-8 rounded-md {{ $user->status === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} transition"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6v6m5.657-4.243a8 8 0 11-11.314 0" />
        </svg>
    </button>
</form>
@endif




</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-6">No accounts found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
    {{ $users->links() }}
</div>

        </div>
    </main>
</div>

{{-- Confirm Delete & Toggle --}}
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${userId}`).submit();
            }
        });
    }

    function confirmToggle(userId, currentStatus, userName, barangayName) {
    const actionText = currentStatus === 'active' ? 'deactivate' : 'activate';
    Swal.fire({
        title: 'Are you sure?',
        html: `Do you want to <b>${actionText}</b> the account of <b>${userName}</b> from barangay  <b>${barangayName}</b>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: `Yes, ${actionText} it`
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`toggle-form-${userId}`).submit();
        }
    });
}


    function toggleModal(show) {
        const modal = document.getElementById('addAccountModal');
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
    }


    function toggleEditModal(show) {
        const modal = document.getElementById('editAccountModal');
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
    }

    function toggleViewModal(show) {
        const modal = document.getElementById('viewAccountModal');
        modal.classList.toggle('hidden', !show);
        modal.classList.toggle('flex', show);
    }

    function loadEditModal(userId) {
    toggleEditModal(true);
    const content = document.getElementById('editAccountContent');

    // Tanggalin ang loading line:
    // content.innerHTML = '<div class="text-center text-gray-500">Loading...</div>';

    fetch(`/abc/accounts/${userId}/edit`)
        .then(response => response.text())
        .then(html => content.innerHTML = html)
        .catch(() => content.innerHTML = '<div class="text-red-600 text-center">Error loading form.</div>');
}

function loadViewModal(userId) {
    toggleViewModal(true);
    const content = document.getElementById('viewAccountContent');

    // Tanggalin ang loading line:
    // content.innerHTML = '<div class="text-center text-gray-500">Loading...</div>';

    fetch(`/abc/accounts/${userId}`)
        .then(response => response.text())
        .then(html => content.innerHTML = html)
        .catch(() => content.innerHTML = '<div class="text-red-600 text-center">Error loading details.</div>');
}


</script>
     
{{-- SweetAlert for success feedback --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

{{-- Edit Modal --}}
<div id="editAccountModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-white w-full max-w-4xl p-6 rounded-lg relative shadow-lg overflow-y-auto max-h-[90vh]">
        <button onclick="toggleEditModal(false)" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl">&times;</button>
       
        <div id="editAccountContent" class="text-sm">
            <div class="text-center text-gray-500">Loading...</div>
        </div>
    </div>
</div>

{{-- View Modal --}}
<div id="viewAccountModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-white w-full max-w-3xl p-6 rounded-lg relative shadow-lg overflow-y-auto max-h-[90vh]">
        <button onclick="toggleViewModal(false)" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl">&times;</button>
      
        <div id="viewAccountContent" class="text-sm">
            <div class="text-center text-gray-500">Loading...</div>
        </div>
    </div>
</div>
{{-- Add Account Modal --}}
<div id="addAccountModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-white w-full max-w-3xl p-6 rounded-lg relative shadow-lg overflow-y-auto max-h-[90vh]">
        <button onclick="toggleModal(false)" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl">&times;</button>
        <h2 class="text-lg font-semibold mb-4">Add Account</h2>
        
        <div id="addAccountContent" class="text-sm">
            {{-- Pwede mong ilagay dito yung form mo directly --}}
            @include('abc.add_account') 
            {{-- OR kung gusto mo AJAX gaya ng view/edit, palitan mo ng loading at fetch --}}
        </div>
    </div>
</div>

@endsection
