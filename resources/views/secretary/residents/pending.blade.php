@extends('layouts.app')

@section('content')
<div class="flex font-sans min-h-screen overflow-hidden">
    {{-- Sidebar --}}
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        @include('partials.secretary-sidebar')
    </div>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">
        {{-- Header --}}
        <div class="sticky top-0 z-10 bg-white shadow">
            @include('partials.secretary-header', ['title' => '📄 Pending Document Requests'])
        </div>

        {{-- Page Content --}}
        <div class="p-6 overflow-y-auto flex-1">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Resident Approval</h2>
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
    <a href="{{ route('secretary.residents.pending', ['status' => 'pending']) }}"
       class="p-4 rounded shadow border-l-4 text-sm {{ $status === 'pending' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-yellow-100 border-yellow-500 text-yellow-700' }}">
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
    <a href="{{ route('secretary.residents.pending', ['status' => 'approved']) }}"
       class="p-4 rounded shadow border-l-4 text-sm {{ $status === 'approved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700' }}">
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
    <a href="{{ route('secretary.residents.pending', ['status' => 'rejected']) }}"
       class="p-4 rounded shadow border-l-4 text-sm {{ $status === 'rejected' ? 'bg-red-200 border-red-600 text-red-900' : 'bg-red-100 border-red-500 text-red-700' }}">
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

    {{-- All --}}
<a href="{{ route('secretary.residents.pending', ['status' => 'all']) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ $status === 'all' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">All</h4>
            <p class="text-xl font-bold">{{ $counts['all'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 20h18M9 10h6M9 14h6" />
        </svg>
    </div>
</a>

</div>

<form method="GET" action="{{ route('secretary.residents.pending') }}" id="searchForm" class="mb-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:w-80 relative">
        <input
            type="text"
            name="search"
            id="searchInput"
            value="{{ request('search') }}"
            placeholder="Search by name or email..."
            class="border border-gray-300 rounded px-3 py-2 w-full pr-10"
        />

        @if(request('search'))
        <a href="{{ route('secretary.residents.pending', ['status' => request('status')]) }}"
           class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xl"
           title="Clear search">&times;</a>
        @endif
    </div>
</form>




                @if($allResidents->isEmpty())
                    <div class="bg-blue-100 text-blue-800 text-sm p-4 rounded-md">
                        No residents found.
                    </div>
                @else
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm text-center">
                            <thead class="bg-blue-800 text-white sticky top-0 z-10">
                                <tr>
                                <th class="px-3 py-2">#</th>
                                    <th class="px-3 py-2">Full Name</th>
                                    <th class="px-3 py-2">Birthdate</th>
                                    <th class="px-3 py-2">Email</th>
                                    <th class="px-3 py-2">Barangay</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($allResidents as $resident)
                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-100' }} border-t">
                                <td class="px-2 py-2 font-medium">{{ $loop->iteration }}</td>
                                        <td class="px-2 py-2">
                                            {{ trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name} {$resident->suffix}") }}
                                        </td>
                                       <td class="px-2 py-2">{{ \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') }}</td>

                                        <td class="px-2 py-2">{{ $resident->email }}</td>
                                        <td class="px-2 py-2">{{ $resident->barangay->name ?? 'N/A' }}</td>
                                        <td class="px-2 py-2">
                                        <span class="inline-block px-2 py-1 rounded text-xs font-medium
    {{ $resident->status === 'approved' ? 'bg-green-100 text-green-800' : ($resident->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
    {{ ucfirst($resident->status) }}
</span>

                                        </td>
                                        <td class="px-2 py-2">
                                            <div class="flex justify-center gap-2">
                                                <button onclick="openResidentModal({{ $resident->id }})"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs flex items-center justify-center"
                                                    title="View Details">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>

                                                @if($resident->status === 'pending')
                                                <button 
    onclick="approveResident({{ $resident->id }}, @js($resident->first_name . ' ' . $resident->last_name))"
    class="bg-green-600 hover:bg-green-700 text-white p-2 rounded transition"
    title="Approve">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
</button>


<button 
    onclick="rejectResident({{ $resident->id }}, @js($resident->full_name ?? ($resident->first_name . ' ' . $resident->last_name)))"
    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded transition"
    title="Reject">
    <!-- X Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>

@endif

<form id="approve-form-{{ $resident->id }}" method="POST"
      action="{{ route('secretary.residents.approve', $resident->id) }}" class="hidden">
    @csrf @method('PUT')
</form>

<form id="reject-form-{{ $resident->id }}" method="POST"
      action="{{ route('secretary.residents.reject', $resident->id) }}" class="hidden">
    @csrf @method('PATCH')
</form>

                                        </td>

                                        <!-- Modal content as template -->
                                        <template id="resident-content-{{ $resident->id }}">
    <div class="max-h-[90vh] overflow-y-auto p-6 bg-white rounded-2xl shadow-xl space-y-6 text-gray-800">

   

        <!-- Info Grid -->
       <!-- Info Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Name</p>
        <p class="mt-1 text-gray-900 font-medium">{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Birthdate</p>
        <p class="mt-1 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') }}</p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Gender</p>
        <p class="mt-1 text-gray-900 font-medium">{{ $resident->gender }}</p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Civil Status</p>
        <p class="mt-1 text-gray-900 font-medium">{{ $resident->civil_status }}</p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Email</p>
        <p class="mt-1 text-gray-900 font-medium">{{ $resident->email ?? 'N/A' }}</p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Phone</p>
        <p class="mt-1 text-gray-900 font-medium">{{ $resident->phone ?? 'N/A' }}</p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Zone</p>
        <p class="mt-1 text-gray-900 font-medium">{{ $resident->zone ?? 'N/A' }}</p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Voter</p>
        <p class="mt-1 font-medium
            {{ strtolower($resident->voter) === 'yes' ? 'text-green-600' : 'text-red-600' }}">
            {{ strtolower($resident->voter) === 'yes' ? 'Registered' : 'Non-Registered' }}
        </p>
    </div>

    <div class="p-3 bg-gray-50 rounded-md shadow-sm">
        <p class="text-gray-500 text-xs font-semibold uppercase">Proof of Residency Type</p>
        <p class="mt-1 text-gray-900 font-medium">{{ $resident->proof_type ?? 'N/A' }}</p>
    </div>
@php
$statusTextColors = [
    'pending' => 'text-yellow-800',
    'approved' => 'text-green-800',
    'rejected' => 'text-red-800',
];
$textColorClass = $statusTextColors[$resident->status] ?? 'text-gray-800';
@endphp

   <div class="p-3 bg-gray-50 rounded-md shadow-sm">
    <p class="text-gray-500 text-xs font-semibold uppercase">Status</p>
    <p class="mt-1 font-medium {{ $textColorClass }}">{{ ucfirst($resident->status) }}</p>
</div>


    @if($resident->previously_rejected)
        <div class="p-3 bg-yellow-100 text-yellow-800 rounded-md shadow-sm md:col-span-2">
            <p class="font-semibold">⚠ Re-registration</p>
            <p>This resident previously had a rejected application.</p>
        </div>
    @endif

    @if ($resident->status === 'rejected' && $resident->reject_reason)
        <div class="md:col-span-2 bg-red-100 text-red-700 p-3 rounded-md border border-red-300">
            <p class="font-semibold">❌ Reason for Rejection:</p>
            <p>{{ $resident->reject_reason }}</p>
        </div>
    @endif
</div>

<!-- Proof of Residency -->
@if($resident->proof_of_residency)
<div>
    <h4 class="font-semibold text-gray-700 mb-2 flex items-center gap-2">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h6v6m-6-6l3-3 3 3" />
        </svg>
        Proof of Residency
    </h4>
    <div class="flex justify-center">
        <img src="{{ asset('storage/' . $resident->proof_of_residency) }}"
             alt="Proof of Residency"
             class="max-h-32 sm:max-h-48 w-auto rounded-md shadow border border-gray-300 object-contain cursor-pointer"
             onclick="openImageModal('{{ asset('storage/' . $resident->proof_of_residency) }}')" />
    </div>
</div>
@else
    <p class="italic text-gray-500">No proof of residency uploaded.</p>
@endif

<!-- Fullscreen Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50">
    <button onclick="closeImageModal()"
            class="absolute top-4 right-6 text-white text-3xl font-bold hover:text-red-400">
        &times;
    </button>
    <img id="modalImage" src="" alt="Fullscreen Image" class="max-h-full max-w-full rounded shadow-lg">
</div>

    </div>
</template>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
    {{ $allResidents->links() }}
</div>

                @endif
            </div>
        </div>
    </main>

    <div id="residentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl max-w-3xl w-full shadow-xl overflow-hidden">
        
        <!-- Header -->
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Resident Details
            </h2>
            <button onclick="closeResidentModal()" 
                    class="text-gray-400 hover:text-red-600 text-2xl font-bold transition-colors">&times;</button>
        </div>

        <!-- Modal Content -->
        <div id="residentModalContent" 
             class="p-6 space-y-6 text-gray-800 text-sm overflow-y-auto" 
             style="max-height: calc(90vh - 64px);">
            <!-- JS will fill this with resident template content -->
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function approveResident(id, name) {
    Swal.fire({
        title: 'Approve ' + name + '?',
        text: "This will approve " + name + " as a resident.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, approve!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form-' + id).submit();
        }
    });
}
function rejectResident(id, name) {
    Swal.fire({
        title: 'Reject ' + name + '?',
        icon: 'warning',
        input: 'select',
        inputOptions: {
            'Incomplete documents': 'Incomplete documents',
            'Invalid information': 'Invalid information',
            'Duplicate registration': 'Duplicate registration',
            'Other': 'Other'
        },
        inputPlaceholder: 'Select reason',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Next',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
            if (!value) return 'Please select a reason';
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            let reason = result.value;

            if (reason === 'Other') {
                // Second popup for custom reason
                const { value: customReason } = await Swal.fire({
                    title: 'Other Reason',
                    input: 'text',
                    inputLabel: 'Please provide a reason',
                    inputPlaceholder: 'Type your reason...',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    inputValidator: (value) => {
                        if (!value) return 'This field is required.';
                    }
                });

                if (!customReason) return; // cancelled

                reason = customReason;
            }

            // Submit form
            const form = document.getElementById('reject-form-' + id);

            const prevInput = form.querySelector('input[name="reject_reason"]');
            if (prevInput) prevInput.remove();

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'reject_reason';
            input.value = reason;
            form.appendChild(input);

            form.submit();
        }
    });
}


    function openResidentModal(id) {
        const template = document.getElementById(`resident-content-${id}`);
        if (template) {
            document.getElementById('residentModalContent').innerHTML = template.innerHTML;
            document.getElementById('residentModal').classList.remove('hidden');
        }
    }

    function closeResidentModal() {
        document.getElementById('residentModal').classList.add('hidden');
        document.getElementById('residentModalContent').innerHTML = '';
    }
    let debounceTimer;
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');

    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            searchForm.submit();
        }, 500); // 500ms delay after typing stops
    });
    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
@endsection
