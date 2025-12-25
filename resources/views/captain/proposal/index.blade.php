@extends('layouts.app')

@section('content')
<div x-data="{ editingProposal: null, showCreateModal: false, viewingProposal: null }" class="font-sans bg-gray-100 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow h-screen flex-shrink-0">
        @include('partials.captain-sidebar')
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        @include('partials.captain-header', ['title' => 'All Proposals'])

        {{-- Scrollable Content --}}
        <main class="flex-1 overflow-y-auto p-6 bg-gray-100">

        

                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold">Proposals</h1>
                    {{-- Optional: Add top-right buttons here --}}
            

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

                
                </div>
            {{-- 🔍 Proposal Filter Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
 {{-- Pending --}}
<a href="{{ route('captain.proposal.index', array_merge(request()->all(), ['status' => 'pending'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ request('status') === 'pending' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-yellow-100 border-yellow-500 text-yellow-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Pending</h4>
            <p class="text-xl font-bold">{{ $counts['pending'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
        </svg>
    </div>
</a>

{{-- Approved --}}
<a href="{{ route('captain.proposal.index', array_merge(request()->all(), ['status' => 'approved'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ request('status') === 'approved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Approved</h4>
            <p class="text-xl font-bold">{{ $counts['approved'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
</a>

{{-- Rejected --}}
<a href="{{ route('captain.proposal.index', array_merge(request()->all(), ['status' => 'rejected'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ request('status') === 'rejected' ? 'bg-red-200 border-red-600 text-red-900' : 'bg-red-100 border-red-500 text-red-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Rejected</h4>
            <p class="text-xl font-bold">{{ $counts['rejected'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </div>
</a>

{{-- All --}}
<a href="{{ route('captain.proposal.index', array_merge(request()->all(), ['status' => 'all'])) }}"
   class="p-4 rounded shadow border-l-4 text-sm {{ request('status') === 'all' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700' }}">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">All</h4>
            <p class="text-xl font-bold">{{ $counts['all'] ?? 0 }}</p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18" />
        </svg>
    </div>
</a>


</div>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 w-full gap-4">


<!-- 🔍 Search & Filters Form -->
<form id="filterForm" action="{{ route('captain.proposal.index') }}" method="GET" class="flex flex-wrap gap-2 w-full md:w-auto items-center">

    @if (request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif

    {{-- Search Input --}}
    <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Search proposals..." 
        class="border px-4 py-2 rounded w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
        oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 100000)">
    

    {{-- Month Dropdown --}}
    <select 
        name="month" 
        class="border px-3 py-2 rounded w-full md:w-40 focus:outline-none focus:ring-2 focus:ring-blue-500"
        onchange="this.form.submit()"
    >
        <option value="">Select Months</option>
        @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
            </option>
        @endforeach
    </select>

    {{-- Year Dropdown --}}
    <select 
        name="year" 
        class="border px-3 py-2 rounded w-full md:w-32 focus:outline-none focus:ring-2 focus:ring-blue-500"
        onchange="this.form.submit()"
    >
        <option value="">Select Years</option>
        @foreach(range(date('Y'), date('Y')-10) as $y)
            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                {{ $y }}
            </option>
        @endforeach
    </select>

    {{-- Clear Filters Button --}}
    @if(request('search') || request('month') || request('year'))
        <a href="{{ route('captain.proposal.index') }}"
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm shadow transition">
            Clear
        </a>
    @endif

</form>

<!-- ➕ Create Button -->
<div class="w-full md:w-auto">
    <button 
        @click="showCreateModal = true"
        type="button"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full md:w-auto"
    >
        + Create Proposal
    </button>
</div>


</div>

@if($proposals->isEmpty())
    <div class="flex-1 flex items-center justify-center">
        <p class="text-gray-600 text-lg">No proposals available.</p>
    </div>
@else
    <div class="bg-white shadow rounded-lg p-4">
        <div class="overflow-x-auto">
            <table class="w-full table-auto border text-sm text-left">
                <thead class="bg-blue-600 text-white uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4 border-b">#</th>
                        <th class="py-3 px-4 border-b">Proposal</th>
                        <th class="py-3 px-4 border-b">Created at</th>
                        <th class="py-3 px-4 border-b">Status</th>
                        <th class="py-3 px-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800">
                    @foreach($proposals as $index => $proposal)
                        <tr class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-b">
                            <td class="py-3 px-4">{{ $proposals->firstItem() + $index }}</td>
                            <td class="py-3 px-4">{{ $proposal->title }}</td>
                            <td class="py-3 px-4">
                                @if($proposal->status === 'pending')
                                    {{ $proposal->created_at->format('F d, Y') }}
                                @else
                                    {{ $proposal->updated_at->format('F d, Y') }}
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{
                                        $proposal->status === 'approved' ? 'bg-green-100 text-green-700' :
                                        ($proposal->status === 'rejected' ? 'bg-red-100 text-red-700' :
                                        ($proposal->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                        'bg-gray-100 text-gray-700'))
                                    }}">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                                    </td>
                                   <td class="py-3 px-4 flex space-x-2 items-center" x-data="{ proposal: @js($proposal) }">

    {{-- View --}}
  
<button 
    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
    @click="viewingProposal = proposal"
    title="View Proposal"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>


    {{-- Edit (pending or rejected) --}}
    @if (in_array($proposal->status, ['pending', 'rejected']))
        <button 
            class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-md transition"
            @click="editingProposal = {{ $proposal->toJson() }}"
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
    @endif

    {{-- Resubmit (rejected only) --}}
    @if ($proposal->status === 'rejected')
        <form 
            action="{{ route('captain.proposal.resubmit', $proposal->id) }}" 
            method="POST" 
            x-ref="resubmitForm{{ $proposal->id }}"
            @submit.prevent="
                Swal.fire({
                    title: 'Resubmit Proposal?',
                    text: 'This will set the proposal status back to pending.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, resubmit it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $refs.resubmitForm{{ $proposal->id }}.submit()
                    }
                })
            "
            class="inline"
        >
            @csrf
            @method('PATCH')
            <button 
                type="submit"
                class="bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-md transition"
                title="Resubmit"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 4v5h.582a9 9 0 0115.356-2.54M20 20v-5h-.581a9 9 0 01-15.356 2.54" />
                </svg>
            </button>
        </form>
    @endif

    {{-- Delete (not approved) --}}
    @if ($proposal->status !== 'approved')
        <form 
            action="{{ route('captain.proposal.destroy', $proposal->id) }}" 
            method="POST" 
            x-ref="deleteForm{{ $proposal->id }}"
            @submit.prevent="
                Swal.fire({
                    title: 'Are you sure?',
                    html: 'You are about to delete the proposal: <strong>{{ $proposal->title }}</strong>.<br>This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $refs.deleteForm{{ $proposal->id }}.submit()
                    }
                })
            "
            class="inline"
        >
            @csrf
            @method('DELETE')
            <button 
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-md transition"
                title="Delete"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 011 1v0a1 1 0 01-1 1H7a1 1 0 01-1-1v0a1 1 0 011-1h10z"/>
                </svg>
            </button>
        </form>
    @endif

    {{-- Print (approved only) --}}
    @if ($proposal->status === 'approved')
        <button 
            class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-md transition"
            @click="window.open('{{ route('captain.proposal.print', $proposal->id) }}', '_blank')"
            title="Print Proposal"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5h20v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
        </svg>
        </button>
    @endif

</td>



                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $proposals->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- CREATE MODAL -->
        <div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative" @click.outside="showCreateModal = false">
                <h2 class="text-xl font-bold mb-4">Create Proposal</h2>
                @include('captain.proposal.create')
            </div>
        </div>
<!-- VIEW MODAL -->
<div x-show="viewingProposal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative" @click.outside="viewingProposal = null">
        <h2 class="text-xl font-bold mb-4">Proposal Details</h2>

        <div class="space-y-2">
            <p><strong>Title:</strong> <span x-text="viewingProposal.title"></span></p>
            <p><strong>Description:</strong> <span x-text="viewingProposal.description"></span></p>
            <p><strong>Source of Fund:</strong> <span x-text="viewingProposal.source_of_fund"></span></p>
            <p><strong>Target Date:</strong> 
                <span x-text="viewingProposal.target_date ? new Date(viewingProposal.target_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'N/A'"></span>
            </p>
            <template x-if="viewingProposal.status === 'rejected' && viewingProposal.rejection_reason">
                <p class="text-red-600"><strong>Rejection Reason:</strong> <span x-text="viewingProposal.rejection_reason"></span></p>
            </template>
        </div>

        <div class="flex justify-end mt-4">
            <button @click="viewingProposal = null" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Close</button>
        </div>
    </div>
</div>

       <!-- EDIT MODAL -->
<div x-show="editingProposal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative" @click.outside="editingProposal = null">
        <h2 class="text-xl font-bold mb-4">Edit Proposal</h2>

        <form :action="'{{ route('captain.proposal.update', '__ID__') }}'.replace('__ID__', editingProposal.id)" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">
    <label class="block text-sm font-medium">Title</label>
    <input type="text" name="title" x-model="editingProposal.title" class="w-full border rounded px-3 py-2 mt-1" required>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Description</label>
    <textarea name="description" x-model="editingProposal.description" rows="4" class="w-full border rounded px-3 py-2 mt-1"></textarea>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Source of Fund</label>
    <input type="text" name="source_of_fund" x-model="editingProposal.source_of_fund" class="w-full border rounded px-3 py-2 mt-1">
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Target Date</label>
    <input type="date" name="target_date" x-model="editingProposal.target_date" class="w-full border rounded px-3 py-2 mt-1">
</div>


          

            <div class="flex justify-end space-x-2">
                <button type="button" @click="editingProposal = null" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

    </main>
</div>



@endsection
