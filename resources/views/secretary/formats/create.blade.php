@extends('layouts.app')

@section('title', 'Add Document Format')
@section('content')
<div class="flex h-screen overflow-hidden font-sans">

    @include('partials.secretary-sidebar')

    <div class="flex flex-col flex-1 max-h-screen">

        @include('partials.secretary-header', ['title' => 'Resident Details'])

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">

            <main class="flex-1 p-6 bg-white rounded shadow max-w-5xl mx-auto my-10">

                <a href="{{ route('secretary.formats.index') }}" class="inline-block mb-6 text-blue-600 hover:underline">
                    &larr; Back to Formats
                </a>

                <h1 class="text-2xl font-bold mb-6">Add Document Format</h1>

                <form method="POST" action="{{ route('secretary.formats.store') }}">
                    @csrf

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="block font-semibold mb-2">Format Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="w-full border border-gray-300 rounded p-2">
                        @error('title')
                            <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-4">
                        <label for="content" class="block font-semibold mb-2">
                            Format Content <small class="text-gray-500">(use placeholders like <code>@{{name}}</code>, <code>@{{birthdate}}</code>, etc.)</small>
                        </label>
                        <textarea name="content" id="content" rows="8" required
                            class="w-full border border-gray-300 rounded p-2 font-mono">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <label for="price" class="block font-semibold mb-2">Price (₱)</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', '0.00') }}" required
                            class="w-full border border-gray-300 rounded p-2">
                        @error('price')
                            <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="bg-yellow-400 text-blue-900 px-6 py-2 rounded font-semibold hover:bg-yellow-500 transition">
                        Save Format
                    </button>
                </form>

            </main>
        </main>
    </div>
</div>
@endsection
