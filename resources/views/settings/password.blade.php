@extends('layouts.app')

@section('title', 'Change Password')

@section('content')

@php
    $role = Auth::user()->role;
    $partialPrefix = match($role) {
        'admin' => 'abc',
        'secretary' => 'secretary',
        'brgy_captain' => 'captain',
        default => 'abc'
    };
    $mustChange = Auth::user()->must_change_password;
@endphp

<div class="flex min-h-screen bg-gray-100">

    {{-- Sidebar --}}
    @include("partials.{$partialPrefix}-sidebar")

    <div class="flex-1 flex flex-col">

        {{-- Header --}}
        @include("partials.{$partialPrefix}-header")

        {{-- Main Content --}}
        <main class="flex-1 p-6">
            <div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-lg">

                <h2 class="text-2xl font-bold mb-4 text-gray-800">Change Password</h2>

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 mb-4 rounded border border-green-200 text-center font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('settings.password.update') }}" method="POST" onsubmit="return validatePasswordMatch()">
                    @csrf

                    <div class="space-y-4">

                        {{-- Current Password (only if not first login) --}}
                        @unless($mustChange)
                        <div class="relative">
                            <label for="current_password" class="block font-semibold mb-1">Current Password</label>
                            <input type="password" name="current_password" id="current_password" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none pr-10" required>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700" 
                                onclick="toggleVisibility('current_password')" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            @error('current_password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endunless

                        {{-- New Password --}}
                        <div class="relative">
                            <label for="password" class="block font-semibold mb-1">New Password</label>
                            <input type="password" name="password" id="password" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none pr-10" required>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700" 
                                onclick="toggleVisibility('password')" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="relative">
                            <label for="password_confirmation" class="block font-semibold mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none pr-10" required>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700" 
                                onclick="toggleVisibility('password_confirmation')" tabindex="-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <p id="password-match-alert" class="text-red-600 text-sm mt-1 hidden">Passwords do not match.</p>
                        </div>

                    </div>

                    <div class="mt-6">
                        <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-3 rounded-lg transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </main>

    </div>
</div>

<script>
    function toggleVisibility(id) {
        const field = document.getElementById(id);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    function validatePasswordMatch() {
        const pwd = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const alert = document.getElementById('password-match-alert');

        if (pwd.value !== confirm.value) {
            alert.classList.remove('hidden');
            confirm.focus();
            return false;
        } else {
            alert.classList.add('hidden');
            return true;
        }
    }

    document.getElementById('password_confirmation').addEventListener('input', () => {
        const pwd = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const alert = document.getElementById('password-match-alert');
        alert.classList.toggle('hidden', pwd === confirm);
    });
</script>
@endsection
