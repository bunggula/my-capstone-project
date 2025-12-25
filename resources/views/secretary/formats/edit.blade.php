@extends('layouts.app')

@section('title', 'Edit Format')
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

    <h1 class="text-2xl font-bold mb-6">Edit Document Format</h1>

    <form method="POST" action="{{ route('secretary.formats.update', $format->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block font-semibold mb-2">Format Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $format->title) }}" required
                class="w-full border border-gray-300 rounded p-2">
            @error('title')
                <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block font-semibold mb-2">Format Content</label>
            <textarea name="content" id="content" rows="8" required
                class="w-full border border-gray-300 rounded p-2 font-mono">{{ old('content', $format->content) }}</textarea>
            @error('content')
                <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-yellow-400 text-blue-900 px-6 py-2 rounded font-semibold hover:bg-yellow-500 transition">
                Update Format
            </button>
            <a href="{{ route('secretary.formats.index') }}" class="text-gray-600 hover:underline">← Back</a>
        </div>
    </form>
</div>
@endsection
