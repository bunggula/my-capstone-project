@extends('layouts.app')

@section('title', 'Edit Event')

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
            <div class="flex-1 p-6 bg-white rounded shadow max-w-5xl mx-auto my-10">
                <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
                    ← Back
                </a>
                <h2 class="text-2xl font-bold mb-6">Edit Event</h2>
                
                <form method="POST" action="{{ route('secretary.events.update', $event->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Event Title -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-1">Event Title</label>
                            <input type="text" name="title" value="{{ $event->title }}" required
                                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Date</label>
                            <input type="date" name="date" value="{{ $event->date }}" required
                                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Time -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Time</label>
                            <input type="time" name="time" value="{{ $event->time }}" required
                                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Venue -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-1">Venue</label>
                            <input type="text" name="venue" value="{{ $event->venue }}" required
                                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        </div>

                        <!-- Details -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-1">Details</label>
                            <textarea name="details" rows="4" required
                                      class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">{{ $event->details }}</textarea>
                        </div>
                    </div>

                    {{-- Show existing images --}}
                    @if ($event->images && $event->images->count())
                        <div>
                            <label class="block text-sm font-medium mb-1">Current Images</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach ($event->images as $image)
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Event Image"
                                             class="rounded-lg shadow max-h-32 w-full object-cover">
                                        
                                        <label class="absolute top-1 right-1 bg-white bg-opacity-80 text-xs text-red-600 px-1 rounded cursor-pointer">
                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                                            🗑️
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upload new images --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Upload New Images</label>
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ url()->previous() }}"
                           class="px-6 py-3 bg-gray-400 text-white font-medium rounded-lg hover:bg-gray-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            Update Event
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
