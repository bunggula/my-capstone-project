@extends('layouts.app')
@section('title', 'Municipality Concerns')
@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100">
    @include('partials.abc-sidebar')

    <!-- Main content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.abc-header')

        <div class="overflow-x-auto">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">

                    {{-- Page Title --}}
                    <h1 class="text-2xl font-bold text-blue-900 mb-6">Resolved Concerns</h1>

                    {{-- 🔹 TOTAL / Resolved Card --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6 w-full">
                        <a href="{{ route('abc.concerns.index', array_merge(request()->query(), ['status' => 'resolved'])) }}"
                           class="flex items-center justify-between p-4 rounded shadow border-l-4 transition
                           {{ request('status') === 'resolved' 
                                 ? 'bg-green-200 border-green-700 text-green-800' 
                                 : 'bg-green-100 border-green-600 text-green-700 hover:bg-green-200' }}">
                            <div>
                                <h4 class="font-semibold">
                                    Resolved
                                    <span class="text-xs text-gray-600">
                                        ({{ request('barangay') 
                                            ? $barangays->firstWhere('id', request('barangay'))->name 
                                            : 'All Barangays' }})
                                    </span>
                                </h4>
                                <p class="text-2xl font-bold">{{ $filteredCount }}</p>
                            </div>
                            <i data-lucide="check-circle" class="w-10 h-10 opacity-70 text-green-600"></i>
                        </a>
                    </div>

                    {{-- Filter Form --}}
                    <div class="flex items-center gap-4 mb-6" x-data>
                        <form method="GET" action="{{ route('abc.concerns.index') }}" x-ref="filterForm" class="flex flex-wrap items-center gap-2">
                            {{-- 🏘️ Barangay --}}
                            <select name="barangay" x-model="selectedBarangay" @change="$refs.filterForm.submit()"
                                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
                                <option value="">-- Select a Barangay --</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ request('barangay') == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->name }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Month --}}
                            <select name="month" @change="$refs.filterForm.submit()"
                                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
                                <option value="">Select months</option>
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Year --}}
                            <select name="year" @change="$refs.filterForm.submit()"
                                    class="border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-500">
                                <option value="">Select Years</option>
                                @foreach (range(date('Y'), date('Y') - 5) as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Clear Button --}}
                            @if(request('barangay') || request('month') || request('year'))
                                <a href="{{ route('abc.concerns.index') }}" 
                                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow transition">
                                   Clear
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- 🔹 Concern Type Cards --}}
                    @if($concerns->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
                            @php
                                // Group by concern type/title
                                $grouped = $concerns->groupBy('title');
                            @endphp

                            @foreach($grouped as $title => $group)
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded shadow hover:shadow-lg transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-sm capitalize">{{ $title }}</h4>
                                            <p class="text-2xl font-bold">{{ $group->count() }}</p>
                                            <p class="text-xs text-gray-600">
                                                @if(request('barangay'))
                                                    {{ $barangays->firstWhere('id', request('barangay'))->name }}
                                                @else
                                                    All Barangays
                                                @endif
                                            </p>
                                        </div>
                                        <i data-lucide="alert-circle" class="h-8 w-8 opacity-50 text-yellow-600"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mt-6 text-center">No resolved concerns available.</p>
                    @endif

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $concerns->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
@endsection
