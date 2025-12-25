@extends('layouts.app')

@section('title', 'Resident Details')

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100">
    @include('partials.abc-sidebar')

    <main class="flex-1 flex flex-col overflow-hidden">
    @include('partials.abc-header', ['title' => 'Resident Details'])

    <div class="p-6">
        {{-- Back Button --}}
        <a href="{{ url()->previous() }}" class="inline-block mb-4 text-sm text-blue-600 hover:underline">
            ← Back
        </a>

        <h2 class="text-3xl font-bold text-gray-800 mb-8">Resident Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700 text-sm">
            <!-- existing content continues here -->
                    <div>
                        <span class="font-medium text-gray-600">Name:</span><br>
                        {{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Gender:</span><br>
                        {{ $resident->gender }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Birthdate:</span><br>
                        {{ $resident->birthdate }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Age:</span><br>
                        {{ $resident->age }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Civil Status:</span><br>
                        {{ $resident->civil_status }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Category:</span><br>
                        {{ $resident->category ?? 'N/A' }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Email:</span><br>
                        {{ $resident->email ?? 'N/A' }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Phone:</span><br>
                        {{ $resident->phone ?? 'N/A' }}
                    </div>

                    <div>
                        <span class="font-medium text-gray-600">Barangay:</span><br>
                        {{ $resident->barangay->name ?? 'N/A' }}
                    </div>

                    <div class="md:col-span-2">
                        <span class="font-medium text-gray-600">Address:</span><br>
                        {{ $resident->address }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
