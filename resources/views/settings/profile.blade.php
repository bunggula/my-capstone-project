@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

@php
    $role = Auth::user()->role;

    $partialPrefix = match($role) {
        'admin' => 'abc',
        'secretary' => 'secretary',
        'brgy_captain' => 'captain',
        default => 'abc'
    };
@endphp

<div class="flex min-h-screen bg-gray-100">

    {{-- Sidebar --}}
    @include("partials.{$partialPrefix}-sidebar")

    <div class="flex-1 flex flex-col">

        {{-- Header --}}
        @include("partials.{$partialPrefix}-header")

        {{-- Main Content --}}
        <main class="flex-1 p-6">
            <div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow-lg">

                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: '{{ session('success') }}',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: '{{ session('error') }}',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        });
                    </script>
                @endif

                <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Profile</h2>

                {{-- Email Not Verified --}}
                @if (!$user->hasVerifiedEmail())
                    <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4 text-sm">
                        Your email is not verified.
                        <form method="POST" action="{{ route('verification.send') }}" class="inline">
                            @csrf
                            <button type="submit" class="underline text-blue-600 hover:text-blue-800 ml-1">
                                Resend verification email
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Profile Form --}}
                <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- First Name --}}
                        <div>
                            <label for="first_name" class="block font-semibold mb-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                        {{-- Middle Name --}}
                        <div>
                            <label for="middle_name" class="block font-semibold mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label for="last_name" class="block font-semibold mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                        {{-- Suffix --}}
                        <div>
                            <label for="suffix" class="block font-semibold mb-1">Suffix</label>
                            <input type="text" name="suffix" value="{{ old('suffix', $user->suffix) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block font-semibold mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label for="gender" class="block font-semibold mb-1">Gender</label>
                            <select name="gender" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                <option value="">-- Select Gender --</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        {{-- Birthday --}}
                        <div>
                            <label for="birthday" class="block font-semibold mb-1">Birthday</label>
                            <input type="date" name="birthday" value="{{ old('birthday', $user->birthday) }}" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-3 rounded-lg transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>

    </div>
</div>

@endsection
