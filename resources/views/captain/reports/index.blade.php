@extends('layouts.app')

@section('title', 'Captain - Reports')

@section('content')
<div class="flex h-screen font-sans overflow-hidden bg-gray-100">
    <!-- Sidebar -->
    @include('partials.captain-sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col max-h-screen overflow-hidden">
        <!-- Header -->
        @include('partials.captain-header')

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-blue-600"></i> Uploaded Reports
                </h1>

                <div class="bg-white shadow-lg rounded-2xl p-6">
                    @if($reports->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">📂 No reports available at the moment.</p>
                        </div>
                    @else
                        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($reports as $report)
                                <li class="bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-700 font-medium truncate w-4/5">
                                            📄 {{ $report->filename }}
                                        </span>
                                        <a href="{{ asset('storage/' . $report->filepath) }}"
                                           target="_blank"
                                           class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm transition">
                                            View
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-400">Uploaded: {{ $report->created_at->format('M d, Y') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
