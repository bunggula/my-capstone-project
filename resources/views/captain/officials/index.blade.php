@extends('layouts.app')

@section('title', 'Barangay Officials')

@section('content')
<div class="flex h-screen bg-gray-100 overflow-hidden">
    @include('partials.captain-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
        @include('partials.captain-header')
        <div class="flex-1 overflow-y-auto p-6">
        <div class="max-w-7xl mx-auto p-6 bg-white rounded shadow" x-data="{ showModal: false }">
        <h1 class="text-2xl font-bold mb-4">Barangay Officials</h1>

            <!-- Officials Table -->
            <div class="overflow-x-auto">
    <table class="w-full table-auto border text-sm text-left">
        <thead class="bg-blue-600 text-white uppercase text-xs">
        <tr>
                <th class="px-4 py-2">Full Name</th>
                <th class="px-4 py-2">Position</th>
               
                <th class="px-4 py-2">Actions</th>
            </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">

                        {{-- Barangay Captain --}}
                        @if ($captain)
                        <tr x-data="{ showViewCaptain: false }" class="bg-blue-50">
                            <td class="px-4 py-2 font-semibold">
                                {{ $captain->last_name }}, {{ $captain->first_name }} {{ $captain->middle_name }}{{ $captain->suffix ? ' '.$captain->suffix : '' }}
                            </td>
                            <td class="px-4 py-2">Barangay Captain</td>
                         
                            <td class="px-4 py-2">
                            <button @click="showViewCaptain = true" 
                            class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md flex items-center justify-center">
    <!-- Eye Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>




                                <!-- Modal -->
                                <div x-show="showViewCaptain" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 py-12 px-4">
                                    <div @click.away="showViewCaptain = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh] transform transition-all scale-95 duration-200 ease-out">
                                        <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
                                            <h2 class="text-2xl font-bold text-blue-800">Barangay Captain Details</h2>
                                            <button @click="showViewCaptain = false" class="text-gray-400 hover:text-red-600 text-2xl font-bold">&times;</button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 gap-y-6 text-gray-700 text-sm sm:text-base">
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Full Name</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $captain->first_name }} {{ $captain->middle_name }} {{ $captain->last_name }}{{ $captain->suffix ? ' '.$captain->suffix : '' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Gender</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ ucfirst($captain->gender) }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Birthday</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($captain->birthday)->format('F d, Y') }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Age</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($captain->birthday)->age }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm sm:col-span-2">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Email</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $captain->email ?? '-' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Start Year</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $captain->start_year ?? '-' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">End Year</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $captain->end_year ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif

                        {{-- Barangay Secretary --}}
                        @if ($secretary)
                        <tr x-data="{ showViewSecretary: false }" class="bg-green-50">
                            <td class="px-4 py-2 font-semibold">
                                {{ $secretary->last_name }}, {{ $secretary->first_name }} {{ $secretary->middle_name }}{{ $secretary->suffix ? ' '.$secretary->suffix : '' }}
                            </td>
                            <td class="px-4 py-2">Barangay Secretary</td>
                         
                            <td class="px-4 py-2">
                            <button @click="showViewSecretary = true" 
                            class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md flex items-center justify-center">
    <!-- Eye Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>


                                <!-- Modal -->
                                <div x-show="showViewSecretary" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 py-12 px-4">
                                    <div @click.away="showViewSecretary = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh] transform transition-all scale-95 duration-200 ease-out">
                                        <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
                                            <h2 class="text-2xl font-bold text-blue-800">Barangay Secretary Details</h2>
                                            <button @click="showViewSecretary = false" class="text-gray-400 hover:text-red-600 text-2xl font-bold">&times;</button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 gap-y-6 text-gray-700 text-sm sm:text-base">
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Full Name</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $secretary->first_name }} {{ $secretary->middle_name }} {{ $secretary->last_name }}{{ $secretary->suffix ? ' '.$secretary->suffix : '' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Gender</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ ucfirst($secretary->gender) }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Birthday</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($secretary->birthday)->format('F d, Y') }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Age</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($secretary->birthday)->age }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm sm:col-span-2">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Email</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $secretary->email ?? '-' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Start Year</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $secretary->start_year ?? '-' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">End Year</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $secretary->end_year ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif

                        {{-- Other Officials --}}
                        @forelse($officials ?? [] as $official)
                        <tr x-data="{ showView{{ $official->id }}: false }" class="bg-white">
                            <td class="px-4 py-2">{{ $official->first_name }} {{ $official->middle_name }} {{ $official->last_name }}{{ $official->suffix ? ' '.$official->suffix : '' }}</td>
                            <td class="px-4 py-2">{{ $official->position }}</td>
                           
                            <td class="px-4 py-2">
                            <button @click="showView{{ $official->id }} = true" 
        class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md flex items-center justify-center">
    <!-- Eye Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>


                                <!-- Modal -->
                                <div x-show="showView{{ $official->id }}" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 py-12 px-4">
                                    <div @click.away="showView{{ $official->id }} = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh] transform transition-all scale-95 duration-200 ease-out">
                                        <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
                                            <h2 class="text-2xl font-bold text-blue-800">Official Details</h2>
                                            <button @click="showView{{ $official->id }} = false" class="text-gray-400 hover:text-red-600 text-2xl font-bold">&times;</button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 gap-y-6 text-gray-700 text-sm sm:text-base">
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Full Name</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $official->first_name }} {{ $official->middle_name }} {{ $official->last_name }}{{ $official->suffix ? ' '.$official->suffix : '' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Gender</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ ucfirst($official->gender) }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Birthday</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($official->birthday)->format('F d, Y') }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Age</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($official->birthday)->age }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Email</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $official->email ?? '-' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Position</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $official->position }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Term Start</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $official->start_year ?? '-' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Term End</p>
                                                <p class="mt-1 text-gray-900 font-medium">{{ $official->end_year ?? '-' }}</p>
                                            </div>
                                            <div class="p-4 bg-gray-50 rounded-lg shadow-sm sm:col-span-2">
                                                <p class="text-gray-500 font-semibold uppercase text-xs">Categories</p>
                                                <p class="mt-1 text-gray-900 font-medium">
                                                    @php
                                                        $categories = is_array($official->categories) ? $official->categories : json_decode($official->categories, true);
                                                    @endphp
                                                    {{ $categories ? implode(', ', $categories) : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500 italic">No other officials found.</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
