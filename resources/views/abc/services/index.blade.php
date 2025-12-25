@extends('layouts.app')

@section('title', 'Municipality Services')

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100">
    {{-- Sidebar --}}
    @include('partials.abc-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.abc-header', ['title' => 'Municipality Residents'])

        <div class="overflow-x-auto">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Page Title --}}
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Services</h1>

                    {{-- 🔹 TOTAL / Completed Card --}}
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                        <a href="{{ route('abc.services.index', ['status' => 'completed', 'barangay' => request('barangay')]) }}"
                           class="block p-4 rounded shadow border-l-4 
                           {{ request('status') === 'completed' ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-sm">
                                        Completed
                                        <span class="text-xs text-gray-600">
                                            ({{ request('barangay') ? $barangays->firstWhere('id', request('barangay'))->name : 'All Barangays' }})
                                        </span>
                                    </h4>
                                    <p class="text-2xl font-bold">{{ $completedCount }}</p>
                                </div>
                                <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6"/>
                                </svg>
                            </div>
                        </a>
                    </div>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('abc.services.index') }}" 
                          class="bg-white p-6 rounded-lg shadow flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        
                        <div class="flex flex-wrap items-center gap-4">
                            {{-- Document Type Filter --}}
                            <label class="text-gray-700 font-semibold">Document Type:</label>
                            <select name="document_type" onchange="this.form.submit()" 
                                    class="border-gray-300 rounded px-3 py-2 shadow-sm">
                                <option value="">All</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type }}" {{ request('document_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Barangay Filter --}}
                            <label class="text-gray-700 font-semibold">Barangay:</label>
                            <select name="barangay" onchange="this.form.submit()" 
                                    class="border-gray-300 rounded px-3 py-2 shadow-sm">
                                <option value="">All</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ request('barangay') == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Clear Filters --}}
                        @if(request()->hasAny(['document_type', 'barangay']) && (request('document_type') || request('barangay')))
                            <div class="flex items-center justify-end w-full lg:w-auto">
                                <a href="{{ route('abc.services.index') }}" 
                                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow transition">
                                    Clear
                                </a>
                            </div>
                        @endif
                    </form>

                    {{-- 🔹 Document Requests Summary Cards --}}
                    @if ($requests->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
                            @php
                                // Group requests by document type
                                $grouped = $requests->groupBy('document_type');
                            @endphp

                            @foreach ($grouped as $docType => $group)
                                <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded shadow hover:shadow-lg transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-sm capitalize">{{ $docType ?? 'N/A' }}</h4>
                                            <p class="text-2xl font-bold">{{ $group->count() }}</p>
                                            <p class="text-xs text-gray-600">
                                                @if(request('barangay'))
                                                    {{ $barangays->firstWhere('id', request('barangay'))->name }}
                                                @else
                                                    All Barangays
                                                @endif
                                            </p>
                                        </div>
                                        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-600 py-6">No document requests found.</div>
                    @endif

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $requests->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
@endsection
