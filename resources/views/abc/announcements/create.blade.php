@extends('layouts.app')

@section('title', 'Add Announcement')

@section('content')
<div class="flex h-screen bg-gray-100 overflow-hidden">
    {{-- ✅ Sidebar --}}
    @include('partials.abc-sidebar')

    {{-- ✅ Main Content --}}
    <main class="flex-1 flex flex-col overflow-hidden">
        {{-- ✅ Header --}}
        @include('partials.abc-header', ['title' => 'Add Announcement'])

        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
                <a href="{{ route('abc.announcements.index') }}" class="text-gray-600 hover:text-gray-900 font-medium inline-block mb-4">
                    ← Back to Announcements
                </a>

                <h1 class="text-2xl font-bold mb-6">➕ New Announcement</h1>

                <form action="{{ route('abc.announcements.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-semibold">Title:</label>
                        <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Details:</label>
                        <textarea name="details" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Date:</label>
                        <input type="date" name="date" class="w-full border rounded px-3 py-2"
                               min="{{ now()->toDateString() }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Time:</label>
                        <input type="time" name="time" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Images (optional):</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- 🧠 Target Audience --}}
                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Target Audience:</label>

                        <div class="flex gap-4 items-center mb-2">
                            <label>
                                <input type="radio" name="target" value="all" checked>
                                <span class="ml-1">All Barangays</span>
                            </label>
                            <label>
                                <input type="radio" name="target" value="specific">
                                <span class="ml-1">Specific Barangay + Role</span>
                            </label>
                        </div>

                        <div id="target-filters" style="display: none;">
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700">Barangay:</label>
                                <select name="barangay_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Select Barangay --</option>
                                    @foreach ($barangays as $barangay)
                                        <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role:</label>
                                <select name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Select Role --</option>
                                    <option value="brgy_captain">Barangay Captain</option>
                                    <option value="secretary">Secretary</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">Post</button>
                        <a href="{{ route('abc.announcements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const targetRadios = document.querySelectorAll('input[name="target"]');
        const targetFilters = document.getElementById('target-filters');

        function toggleTargetFields() {
            const selected = document.querySelector('input[name="target"]:checked').value;
            if (selected === 'specific') {
                targetFilters.style.display = 'block';
            } else {
                targetFilters.style.display = 'none';
            }
        }

        // Initial state
        toggleTargetFields();

        // Add listeners
        targetRadios.forEach(radio => {
            radio.addEventListener('change', toggleTargetFields);
        });
    });
</script>
@endpush
