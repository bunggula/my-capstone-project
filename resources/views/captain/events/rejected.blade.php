@extends('layouts.app')

@section('title', 'Rejected Events')

@section('content')
<div class="flex w-screen h-screen font-sans overflow-hidden">

    <!-- Sidebar -->
    @include('partials.captain-sidebar')

    <!-- Main content area -->
    <main class="flex-1 flex flex-col relative max-h-screen overflow-hidden">

        {{-- Header --}}
        @include('partials.captain-header', ['title' => 'Resident Details'])

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">


    {{-- 🔙 Back Button --}}
    <div class="mb-6">
        <a href="{{ route('captain.events.index') }}"
           class="inline-flex items-center bg-yellow-300 text-blue-900 px-4 py-2 rounded shadow hover:bg-yellow-400 transition font-semibold">
            ← Back
        </a>
    </div>

    <h1 class="text-2xl font-bold text-blue-900 mb-6">❌ Rejected Events</h1>

    {{-- 🔁 Events List --}}
    @forelse ($events as $event)
        <div class="bg-gray-50 border border-gray-200 p-4 mb-6 rounded-lg shadow-sm">
            <h2 class="text-lg font-semibold text-red-700 mb-1">{{ $event->title }}</h2>

            <ul class="text-sm text-gray-800 space-y-1 mb-3">
                <li><strong>Date:</strong> {{ $event->date }}</li>
                <li><strong>Time:</strong> {{ $event->time }}</li>
                <li><strong>Venue:</strong> {{ $event->venue }}</li>
            </ul>

            <p class="text-sm text-gray-700 mb-2 whitespace-pre-line">{{ $event->details }}</p>

            {{-- 🖼️ Image Viewer --}}
                        <div x-data="{ open: false, image: '' }" @keydown.escape.window="open = false">
                            @if ($event->images && $event->images->count())
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4">
                                    @foreach ($event->images as $img)
                                        <img
                                            src="{{ asset('storage/' . $img->path) }}"
                                            class="cursor-pointer w-full h-36 object-cover rounded-lg shadow hover:shadow-lg transition"
                                            @click="open = true; image = '{{ asset('storage/' . $img->path) }}'"
                                        >
                                    @endforeach
                                </div>

                                <!-- Fullscreen Image Modal -->
                                <div
                                    x-show="open"
                                    x-transition
                                    class="fixed inset-0 z-50 bg-black bg-opacity-80 flex items-center justify-center"
                                    style="display: none;"
                                >
                                    <div class="relative">
                                        <button
                                            @click="open = false"
                                            class="absolute top-2 right-2 text-white text-3xl font-bold hover:text-red-500 focus:outline-none"
                                            aria-label="Close"
                                        >
                                            &times;
                                        </button>
                                        <img :src="image" class="max-h-[70vh] max-w-[90vw] rounded shadow-lg">
                                    </div>
                                </div>
                            @endif
                        </div>
        </div>
    @empty
        <p class="text-gray-600 text-center italic">No rejected events found.</p>
    @endforelse

</div>
@endsection
