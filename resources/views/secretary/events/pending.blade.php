@extends('layouts.app')

@section('title', 'Pending Events')

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
            <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:underline inline-block">
                ← Back
            </a>
        </div>

        {{-- 🔘 Header & Add Button --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">⏳ Pending Events for Approval</h1>
            <a href="{{ route('secretary.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                ➕ Add Event
            </a>
        </div>

        {{-- 🗑️ Bulk Delete Form --}}
        <form action="{{ route('secretary.events.destroy-multiple') }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')

            <div class="mb-4">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    🗑️ Delete Selected
                </button>
            </div>

            {{-- 📋 Events Loop --}}
            @foreach ($events as $event)
                <div class="border border-gray-200 p-4 mb-6 rounded-lg bg-gray-50 shadow-sm">
                    <div class="flex justify-between items-start mb-2">

                        {{-- ✅ Checkbox with Title --}}
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="selected_events[]" value="{{ $event->id }}" class="mt-1">
                            <h2 class="font-semibold text-xl">{{ $event->title }}</h2>
                        </div>

                        <div class="space-x-2">
                            <a href="{{ route('secretary.events.edit', $event->id) }}"
                               class="text-blue-600 hover:underline text-sm">
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
                        </div>
                    </div>

                    <p class="mb-1"><strong>Date:</strong> {{ $event->date }}</p>
                    <p class="mb-1"><strong>Time:</strong> {{ $event->time }}</p>
                    <p class="mb-1"><strong>Venue:</strong> {{ $event->venue }}</p>
                    <p class="mb-2">{{ $event->details }}</p>

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
            @endforeach
        </form>
    </main>
</div>
@endsection
