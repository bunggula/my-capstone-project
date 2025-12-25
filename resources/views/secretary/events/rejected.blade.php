@extends('layouts.app')

@section('title', 'Rejected Events')

@section('content')
<div class="flex h-screen overflow-hidden font-sans">

    <!-- Sidebar -->
    @include('partials.secretary-sidebar')

    <!-- Main content area -->
    <div class="flex flex-col flex-1 max-h-screen">

        <!-- Header -->
        @include('partials.secretary-header', ['title' => 'Resident Details'])

        <!-- Scrollable content -->
        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">

    {{-- ✅ Main Content Area --}}
    <main class="flex-1 p-6 bg-white rounded shadow max-w-5xl mx-auto my-10">

        {{-- 🔙 Back Button --}}
        <div class="mb-6">
            <a href="{{ route('secretary.events.index') }}"
               class="inline-flex items-center bg-yellow-300 text-blue-900 px-4 py-2 rounded shadow hover:bg-yellow-400 transition font-semibold">
                ← Back
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-6">❌ Rejected Events</h1>

        {{-- 📋 Rejected Events List --}}
        @forelse ($events as $event)
            <div class="border border-gray-200 p-4 mb-6 rounded-lg bg-gray-50 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="font-semibold text-xl">{{ $event->title }}</h2>

                    {{-- ✏️ Edit / 🗑️ Delete / 🔁 Resubmit --}}
                    <div class="space-x-2">
                        <a href="{{ route('secretary.events.edit', $event->id) }}" class="text-blue-600 hover:underline text-sm">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('secretary.events.destroy', $event->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this event?')"
                                    class="text-red-600 hover:underline text-sm">
                                🗑️ Delete
                            </button>
                        </form>
                        <form action="{{ route('secretary.events.resubmit', $event->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    onclick="return confirm('Resubmit this event for captain approval?')"
                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                                🔁 Resubmit
                            </button>
                        </form>
                    </div>
                </div>

                <p class="mb-1"><strong>Date:</strong> {{ $event->date }}</p>
                <p class="mb-1"><strong>Time:</strong> {{ $event->time }}</p>
                <p class="mb-1"><strong>Venue:</strong> {{ $event->venue }}</p>
                <p class="mb-2">{{ $event->details }}</p>
                <div class="mt-4 bg-red-100 border border-red-300 text-red-800 p-4 rounded">
    <p><strong>Rejection Reason:</strong> {{ $event->rejection_reason }}</p>
    @if ($event->rejection_notes)
        <p><strong>Notes:</strong> {{ $event->rejection_notes }}</p>
    @endif
</div>

                {{-- 📸 Images --}}
                @if ($event->images && $event->images->count())
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 mt-3">
                        @foreach ($event->images as $image)
                            <div class="w-full h-24 bg-white border rounded flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     alt="Event Image"
                                     class="max-h-full max-w-full object-contain" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-600 text-center">No rejected events found.</p>
        @endforelse
    </main>
</div>
@endsection
