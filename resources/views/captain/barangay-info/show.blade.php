@extends('layouts.app')

@section('title', 'Barangay Information')

@section('content')
<div x-data="{ openModal: false }" class="flex h-screen bg-gray-100 text-gray-800">

    {{-- Sidebar --}}
    @include('partials.captain-sidebar')

    {{-- Main Panel --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header --}}
        @include('partials.captain-header')

        {{-- Toasts --}}
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: '{{ session('error') }}', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                });
            </script>
        @endif

        {{-- Main Content --}}
        <div class="flex-1 p-4 md:p-6 overflow-y-auto">
            <div class="bg-white p-6 rounded-xl shadow-md w-full max-w-4xl mx-auto">

                <h1 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Barangay Information</h1>

                {{-- Logo --}}
                @if($barangay->logo)
                    <div class="mb-6 text-center">
                        <img src="{{ asset('storage/' . $barangay->logo) }}" alt="Barangay Logo"
                             class="h-20 w-20 mx-auto rounded-full shadow-md object-cover">
                    </div>
                @endif

                {{-- Info Grid --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-700 text-sm">
    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Barangay Name:</span>
        <div>{{ $barangay->name }}</div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Chairperson:</span>
        <div>{{ $chairperson ? $chairperson->first_name . ' ' . $chairperson->last_name : 'Not assigned' }}</div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Secretary:</span>
        <div>{{ $secretary ? $secretary->first_name . ' ' . $secretary->last_name : 'Not assigned' }}</div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Contact Number:</span>
        <div>{{ $barangay->contact_number }}</div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Email:</span>
        <div>{{ $userEmail }}</div>
    </div>

    <div class="bg-gray-50 p-2 rounded shadow-sm">
        <span class="font-semibold">Office Hours:</span>
        <div>{{ $barangay->office_hours }}</div>
    </div>
</div>

{{-- Edit Button (Right-Aligned) --}}
<div class="mt-4 flex justify-end">
    <button @click="openModal = true"
            class="bg-blue-600 text-white px-4 py-1 rounded-lg hover:bg-blue-700 transition duration-300">
        Edit Information
    </button>
</div>



            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div x-show="openModal" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="openModal = false"
             class="bg-white w-full max-w-3xl p-4 rounded-xl shadow-xl overflow-y-auto max-h-[80vh]">
            
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-semibold">Edit Barangay Information</h2>
                <button @click="openModal = false" class="text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
            </div>

            @include('captain.barangay-info.edit')
        </div>
    </div>
</div>
@endsection
