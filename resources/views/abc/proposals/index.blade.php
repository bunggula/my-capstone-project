@extends('layouts.app')
@section('title', 'Municipality Proposals')
@section('content')
<div x-data="{ open: false, selectedProposal: {} }" class="flex h-screen overflow-hidden bg-gray-100">
    @include('partials.abc-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.abc-header')

        <div class="overflow-x-auto">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                <h2 class="text-2xl font-bold text-blue-900 mb-6">Barangay Proposals</h2>



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
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">

    @php
        $selectedBarangayName = $barangayId 
            ? $barangays->firstWhere('id', $barangayId)->name 
            : 'All Barangays';

        // Preserve all filters except status
        $filters = request()->only(['search', 'barangay', 'month', 'year']);
    @endphp

    {{-- Pending --}}
    <a href="{{ route('abc.proposals.index', array_merge($filters, ['status' => 'pending'])) }}"
       class="block p-4 rounded shadow border-l-4 transition
              {{ $currentStatus === 'pending' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-yellow-100 border-yellow-500 text-yellow-700 hover:bg-yellow-200' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">
                    Pending Proposals
                    <span class="text-xs text-gray-600">({{ $selectedBarangayName }})</span>
                </h4>
                <p class="text-2xl font-bold">{{ $counts['pending'] ?? 0 }}</p>
            </div>
            <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </a>

    {{-- Approved --}}
    <a href="{{ route('abc.proposals.index', array_merge($filters, ['status' => 'approved'])) }}"
       class="block p-4 rounded shadow border-l-4 transition
              {{ $currentStatus === 'approved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700 hover:bg-green-200' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">
                    Approved Proposals
                    <span class="text-xs text-gray-600">({{ $selectedBarangayName }})</span>
                </h4>
                <p class="text-2xl font-bold">{{ $counts['approved'] ?? 0 }}</p>
            </div>
            <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>
    </a>

    {{-- Rejected --}}
    <a href="{{ route('abc.proposals.index', array_merge($filters, ['status' => 'rejected'])) }}"
       class="block p-4 rounded shadow border-l-4 transition
              {{ $currentStatus === 'rejected' ? 'bg-red-200 border-red-600 text-red-900' : 'bg-red-100 border-red-500 text-red-700 hover:bg-red-200' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">
                    Rejected Proposals
                    <span class="text-xs text-gray-600">({{ $selectedBarangayName }})</span>
                </h4>
                <p class="text-2xl font-bold">{{ $counts['rejected'] ?? 0 }}</p>
            </div>
            <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
    </a>

    {{-- All --}}
    <a href="{{ route('abc.proposals.index', array_merge($filters, ['status' => 'all'])) }}"
       class="block p-4 rounded shadow border-l-4 transition
              {{ $currentStatus === 'all' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700 hover:bg-gray-200' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">
                    All Proposals
                    <span class="text-xs text-gray-600">({{ $selectedBarangayName }})</span>
                </h4>
                <p class="text-2xl font-bold">{{ $counts['all'] ?? array_sum($counts) }}</p>
            </div>
            <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2M3 20h18M9 10h6M9 14h6"/>
            </svg>
        </div>
    </a>






    </div>
  <form method="GET" action="{{ route('abc.proposals.index') }}" id="proposalSearchForm" class="mb-4 flex flex-wrap items-center gap-4">
{{-- 🔍 Search bar --}}
<input type="text" 
       name="search" 
       value="{{ request('search') }}" 
       placeholder="Search by title..."
       class="border border-gray-300 rounded px-3 py-2 text-sm w-64 focus:ring focus:border-blue-500"
        oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 100000)">

{{-- Keep current status --}}
@if($currentStatus)
    <input type="hidden" name="status" value="{{ $currentStatus }}">
@endif

{{-- 🏘️ Barangay Filter --}}

<select name="barangay" 
        onchange="document.getElementById('proposalSearchForm').submit()" 
        class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
    <option value="">Select Barangays</option>
    @foreach($barangays as $barangay)
        <option value="{{ $barangay->id }}" {{ request('barangay') == $barangay->id ? 'selected' : '' }}>
            {{ $barangay->name }}
        </option>
    @endforeach
</select>

{{-- 🗓 Month Filter --}}

<select name="month"
        onchange="document.getElementById('proposalSearchForm').submit()"
        class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
    <option value="">Select Months</option>
    @foreach (range(1, 12) as $m)
        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
        </option>
    @endforeach
</select>

{{-- 📅 Year Filter --}}

<select name="year"
        onchange="document.getElementById('proposalSearchForm').submit()"
        class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
    <option value="">Select Years</option>
    @foreach (range(date('Y'), date('Y') - 5) as $y)
        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
            {{ $y }}
        </option>
    @endforeach
</select>



{{-- 🧹 Clear All Filters Button --}}
@if(request('barangay') || request('search') || request('month') || request('year'))
    <button type="button"
            onclick="
                document.querySelector('select[name=barangay]').value = '';
                document.querySelector('select[name=month]').value = '';
                document.querySelector('select[name=year]').value = '';
                document.querySelector('input[name=search]').value = '';
                document.getElementById('proposalSearchForm').submit();
            "
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow transition text-sm">
        Clear
    </button>
@endif


</form>



                {{-- ✅ Proposals Table --}}
                @if($proposals->count())
    <div class="overflow-x-auto">
        <table class="w-full table-auto border text-sm text-left">
            <thead class="bg-blue-600 text-white uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Proposal</th>
                    <th class="px-4 py-3 text-left">Barangay</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Created</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proposals as $proposal)
                    @php
                        $status = strtolower($proposal->status);
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                        $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                        <td class="px-4 py-2">{{ $proposals->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $proposal->title }}</td>
                        <td class="px-4 py-3">{{ $proposal->barangayInfo->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-3 py-1 text-xs font-medium rounded capitalize {{ $colorClass }}">
                                {{ ucfirst($proposal->status) }}
                            </span>

                            @if ($proposal->status === 'rejected' && $proposal->rejection_reason)
                                <div class="mt-1 text-xs text-red-700 italic">
                                    Reason: {{ $proposal->rejection_reason }}
                                </div>
                            @endif
                        </td>

                                    <td class="px-4 py-3 whitespace-nowrap">{{ $proposal->created_at->format('F d, Y') }}</td>
                                   

<td class="px-4 py-3 flex items-center gap-2">
<button 
 @click='
    open = true;
    selectedProposal = {!! json_encode([
        "id" => $proposal->id,

        // Barangay data
        "barangay" => $proposal->barangayInfo->name ?? "N/A",
        "barangay_logo" => $proposal->barangayInfo->logo ?? null,

        // Municipality data
        "municipality_logo" => $proposal->barangayInfo->municipality->logo ?? null,
        "municipality" => $proposal->barangayInfo->municipality->name ?? "N/A",

        "title" => $proposal->title,
        "captain" => optional($proposal->captain)->full_name ?? "N/A",
        "description" => $proposal->description,
        "file" => $proposal->file,
        "rejection_reason" => $proposal->rejection_reason,
    ]) !!}
'
    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded transition"
    title="View"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>


    @if ($proposal->status === 'pending')
        {{-- ✅ Approve --}}
        <form method="POST" action="{{ route('abc.proposals.approve', $proposal->id) }}" class="inline-block approve-form">
            @csrf
            <button type="button" 
        title="Approve" 
        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded transition approve-btn"
        data-title="{{ $proposal->title }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
</button>

        </form>

        {{-- ❌ Reject --}}
        <button type="button"
        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded transition reject-btn"
        data-id="{{ $proposal->id }}"
        data-title="{{ $proposal->title }}"
        title="Reject"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>

    @endif
</td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    
                </div>
                @else
                    <p class="text-gray-500 mt-6">No proposals available.</p>
                @endif
                <div class="mt-4">
    {{ $proposals->links() }}
</div>
            </div>
        </div>
        
    </main>

    {{-- 🔲 Modal Popup --}}
<div 
    x-show="open" 
    x-cloak 
    x-ref="printModal"
    class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center p-4"
>
    <div class="bg-white rounded-lg w-full max-w-3xl p-8 shadow-lg relative max-h-[90vh] overflow-y-auto leading-relaxed text-gray-800">

        {{-- ❌ Close Button --}}
        <button 
            @click="open = false" 
            class="absolute top-3 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold"
        >
            &times;
        </button>

  <a 
    :href="'/abc/proposals/print/' + selectedProposal.id" 
    target="_blank" 
    class="bg-green-600 hover:bg-green-700 text-white text-sm px-2 py-1 rounded-md">
    🖨️ Print
</a>






  <div class="flex items-center justify-between mb-6">

    <!-- LEFT: Municipality Logo -->
    <template x-if="selectedProposal.municipality_logo">
        <img 
            :src="'/storage/' + selectedProposal.municipality_logo" 
            class="h-16 w-16 object-contain"
            alt="Municipality Logo"
        >
    </template>

    <div class="text-center flex-1">
        <p class="text-xs uppercase tracking-widest">Republika ng Pilipinas</p>
        <p class="text-sm font-semibold" x-text="'Barangay ' + selectedProposal.barangay"></p>
        <p class="text-sm" x-text="'Municipality of ' + selectedProposal.municipality"></p>
    </div>

    <!-- RIGHT: Barangay Logo -->
    <template x-if="selectedProposal.barangay_logo">
        <img 
            :src="'/storage/' + selectedProposal.barangay_logo"  
            class="h-16 w-16 object-contain"
            alt="Barangay Logo"
        >
    </template>

</div>


        {{-- 📄 Letter Content --}}
        <div class="text-sm">
            <p class="text-right">{{ now()->format('F d, Y') }}</p>

            <p class="mt-8">To: <strong>Municipal Office</strong></p>
            <p>From: Barangay <span x-text="selectedProposal.barangay"></span></p>
            <p>Barangay Captain: <strong x-text="selectedProposal.captain"></strong></p>

            <p class="mt-6">Dear Sir/Madam,</p>

            <p class="mt-4 text-justify indent-8">
    We, the officials of Barangay <span x-text="selectedProposal.barangay"></span>, respectfully submit this project proposal entitled 
    <strong><span x-text="selectedProposal.title"></span></strong>
    <template x-if="selectedProposal.target_date">
        with a target completion date of <strong x-text="new Date(selectedProposal.target_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })"></strong>
    </template>.
</p>


            <p class="mt-4 text-justify indent-8" x-text="selectedProposal.description"></p>

            <template x-if="selectedProposal.file">
                <p class="mt-5">
                    📎 <a class="text-blue-600 underline" :href="'/storage/' + selectedProposal.file" target="_blank">View Attached Document</a>
                </p>
            </template>

            <p class="mt-6">Thank you for your consideration. We look forward to your support and favorable response.</p>

            <p class="mt-6">Respectfully yours,</p>

            <div class="mt-8">
                <p><strong x-text="selectedProposal.captain"></strong></p>
                <p>Barangay Captain</p>
            </div>
          
        </div>
    </div>
</div>


    {{-- ✅ Include Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
     document.addEventListener('DOMContentLoaded', function () {
    const approveButtons = document.querySelectorAll('.approve-btn');

    approveButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const form = this.closest('form');
            const title = this.dataset.title; // kunin ang title mula sa data attribute

            Swal.fire({
                title: `Approve this proposal?`,
                html: `<strong>Proposal Title:</strong> ${title}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

document.querySelectorAll('.reject-btn').forEach(button => {
    button.addEventListener('click', async () => {
        const proposalId = button.dataset.id;
        const proposalTitle = button.dataset.title; // kunin ang title

        const { value: reason } = await Swal.fire({
            title: `Reject Proposal: ${proposalTitle}`,
            input: 'select',
            inputLabel: 'Select a reason',
            inputOptions: {
                'Incomplete requirements': 'Incomplete requirements',
                'Out of budget': 'Out of budget',
                'Does not meet criteria': 'Does not meet criteria',
                'Others': 'Others'
            },
            inputPlaceholder: 'Choose reason',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) return 'Please select a reason.';
            }
        });

        if (reason === 'Others') {
            const { value: customReason } = await Swal.fire({
                title: 'Other Reason',
                input: 'text',
                inputLabel: 'Please provide a reason',
                inputPlaceholder: 'Type your reason...',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) return 'This field is required.';
                }
            });

            if (customReason) submitRejectForm(proposalId, customReason);
        } else if (reason) {
            submitRejectForm(proposalId, reason);
        }
    });
});

function submitRejectForm(id, reason) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/abc/proposals/${id}/reject`;

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';

    const reasonInput = document.createElement('input');
    reasonInput.type = 'hidden';
    reasonInput.name = 'rejection_reason';
    reasonInput.value = reason;

    form.appendChild(csrf);
    form.appendChild(reasonInput);

    document.body.appendChild(form);
    form.submit();
}

</script>

@endsection
