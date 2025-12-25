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
            @include('partials.captain-header', ['title' => '👥 List of Residents'])
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6"
              x-data="{ showModal: false, modalData: {} }">

            {{-- Title --}}
            <h2 class="text-2xl font-bold text-blue-900 mb-4">
                List of Residents – Barangay {{ Auth::user()->barangay->name ?? 'N/A' }}
            </h2>
            {{-- Summary Cards --}}
            {{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    {{-- Residents --}}
    <a href="{{ route('captain.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => null])) }}"
       class="block p-4 rounded shadow border-l-4 {{ request('status') === 'approved' && !request('gender') ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Residents</h4>
                <p class="text-2xl font-bold">{{ $counts['all'] ?? 0 }}</p>
            </div>
            <i data-lucide="users" class="w-8 h-8 opacity-60"></i>
        </div>
    </a>

    {{-- Archived --}}
    <a href="{{ route('captain.residents.index', array_merge(request()->query(), ['status' => 'archived', 'gender' => null])) }}"
       class="block p-4 rounded shadow border-l-4 {{ request('status') === 'archived' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Archived Residents</h4>
                <p class="text-2xl font-bold">{{ $counts['archived'] ?? 0 }}</p>
            </div>
            <i data-lucide="archive" class="w-8 h-8 opacity-60"></i>
        </div>
    </a>

    {{-- Male --}}
    <a href="{{ route('captain.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => 'Male'])) }}"
       class="block p-4 rounded shadow border-l-4 {{ request('gender') === 'Male' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Male Residents</h4>
                <p class="text-2xl font-bold">{{ $counts['male'] ?? 0 }}</p>
            </div>
            <i data-lucide="mars" class="w-10 h-10 opacity-70 text-green-600"></i>
        </div>
    </a>

    {{-- Female --}}
    <a href="{{ route('captain.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => 'Female'])) }}"
       class="block p-4 rounded shadow border-l-4 {{ request('gender') === 'Female' ? 'bg-pink-200 border-pink-600 text-pink-900' : 'bg-pink-100 border-pink-500 text-pink-700' }}">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Female Residents</h4>
                <p class="text-2xl font-bold">{{ $counts['female'] ?? 0 }}</p>
            </div>
            <i data-lucide="venus" class="w-10 h-10 opacity-70 text-pink-600"></i>
        </div>
    </a>
</div>

<script>
    lucide.createIcons();
</script>






            {{-- Search & Filter --}}
            {{-- Search & Filters --}}
<form method="GET" action="{{ route('captain.residents.index') }}"
      x-data="{ search: '{{ request('search') }}' }"
      class="mb-6 flex flex-col md:flex-row gap-4 items-start md:items-center">

    {{-- Search --}}
    <div class="relative w-full sm:w-72">
        <input type="text"
               name="search"
               x-model="search"
               @input.debounce.100000ms="$root.submit()"
               placeholder="Search by name"
               class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" />

        <button type="button"
                x-show="search"
                @click="search = ''; $nextTick(() => $root.submit())"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600 text-base focus:outline-none">
            &times;
        </button>
    </div>

   {{-- Category Filter --}}
<div class="flex items-center gap-2 mb-2">
    <label for="categoryFilter" class="text-gray-700 font-semibold">Category:</label>
    <select id="categoryFilter" name="category"
            class="border-gray-300 rounded px-3 py-2 shadow-sm text-gray-800"
            onchange="applyFilters()">
        <option value="">All</option>
        <option value="PWD" {{ request('category') == 'PWD' ? 'selected' : '' }}>PWD</option>
        <option value="Senior" {{ request('category') == 'Senior' ? 'selected' : '' }}>Senior Citizen</option>
        <option value="Indigenous" {{ request('category') == 'Indigenous' ? 'selected' : '' }}>Indigenous People</option>
        <option value="Single Parent" {{ request('category') == 'Single Parent' ? 'selected' : '' }}>Single Parent</option>
    </select>
</div>

{{-- Voter Filter --}}
<div class="flex items-center gap-2">
    <label for="voterFilter" class="text-gray-700 font-semibold">Voter:</label>
    <select id="voterFilter" name="voter"
            class="border-gray-300 rounded px-3 py-2 shadow-sm text-gray-800"
            onchange="applyFilters()">
        <option value="">All</option>
        <option value="Yes" {{ request('voter') == 'Yes' ? 'selected' : '' }}>Registered Voters</option>
        <option value="No" {{ request('voter') == 'No' ? 'selected' : '' }}>Non-Registered Voters</option>
    </select>
</div>

<script>
    function applyFilters() {
        const category = document.getElementById('categoryFilter').value;
        const voter = document.getElementById('voterFilter').value;

        const params = new URLSearchParams(window.location.search);
        category ? params.set('category', category) : params.delete('category');
        voter ? params.set('voter', voter) : params.delete('voter');

        window.location.search = params.toString();
    }
</script>

    {{-- Clear Filters --}}
    <div class="flex items-center mt-2 md:mt-0" x-show="search || '{{ request('category') }}' || '{{ request('voter') }}'" x-cloak>
        <a href="{{ route('captain.residents.index') }}"
           class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
           Clear
        </a>
    </div>

    {{-- Print Button (always visible) --}}
    <a href="{{ route('captain.residents.print', [
            'search' => request('search'),
            'category' => request('category'),
            'voter' => request('voter')
        ]) }}"
       target="_blank"
       class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-2 transition ml-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5h20v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
        </svg>
        Print
    </a>

</form>

<script>
document.getElementById('combinedFilter').addEventListener('change', function() {
    const form = this.closest('form');
    const value = this.value;

    // Remove any existing hidden inputs
    form.querySelectorAll('input[name="category"], input[name="voter"]').forEach(el => el.remove());

    if(value.includes('category:')) {
        const category = value.split(':')[1];
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'category';
        input.value = category;
        form.appendChild(input);
    } else if(value.includes('voter:')) {
        const voter = value.split(':')[1];
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'voter';
        input.value = voter;
        form.appendChild(input);
    }

    form.submit();
});
</script>



            {{-- Full Width Table --}}
            <div class="bg-white shadow rounded-lg p-2">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border text-sm text-left">
                    <thead class="bg-blue-600 text-white uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Full Name</th>
                            <th class="px-4 py-3">Gender</th>
                            <th class="px-4 py-3">Birthdate</th>
                            <th class="px-4 py-3">Civil Status</th>
                         
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-black">
                        @forelse($residents as $resident)
                        <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-100' }} border-t">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">
                                {{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}
                            </td>
                             <td class="px-4 py-2">{{ $resident->gender }}</td>
                           <td class="px-4 py-2">
    {{ \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') }}
</td>

                            <td class="px-4 py-2">{{ $resident->civil_status }}</td>
                          
                            <td class="px-4 py-2">
                            <button 
    @click="modalData = {{ json_encode($resident) }}; showModal = true"
    class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700"
    title="View">
    <!-- Eye Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" 
         fill="none" viewBox="0 0 24 24" 
         stroke-width="1.5" stroke="currentColor" 
         class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M2.25 12s3.75-6.75 9.75-6.75 9.75 6.75 9.75 6.75-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" />
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
</button>

                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center px-4 py-6 text-gray-500">No residents found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-6">
    {{ $residents->appends(request()->query())->links() }}
</div>

            </div>

            {{-- Include Modal --}}
            @include('partials.resident-modal')

        </main>
    </div>
</div>
@endsection
