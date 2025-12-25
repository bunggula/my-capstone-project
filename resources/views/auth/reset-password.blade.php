@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Set New Password</h2>

    <form method="POST" action="{{ route('password.update') }}" id="resetForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-4">
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" value="{{ $email ?? old('email') }}" required class="w-full border p-2 rounded">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-4 relative">
            <label for="password" class="block font-semibold">New Password</label>
            <input type="password" name="password" id="password" required class="w-full border p-2 rounded pr-10">
            <span onclick="togglePassword('password')" class="absolute right-3 top-9 cursor-pointer text-gray-600">👁️</span>
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-4 relative">
            <label for="password_confirmation" class="block font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border p-2 rounded pr-10">
            <span onclick="togglePassword('password_confirmation')" class="absolute right-3 top-9 cursor-pointer text-gray-600">👁️</span>
            <p id="mismatchWarning" class="text-red-600 text-sm mt-1 hidden">Passwords do not match</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
            Reset Password
        </button>
    </form>
</div>

{{-- Scripts --}}
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

const password = document.getElementById('password');
const confirmPassword = document.getElementById('password_confirmation');
const mismatchWarning = document.getElementById('mismatchWarning');

function checkPasswordMatch() {
    if (confirmPassword.value !== password.value) {
        mismatchWarning.classList.remove('hidden');
    } else {
        mismatchWarning.classList.add('hidden');
    }
}

password.addEventListener('input', checkPasswordMatch);
confirmPassword.addEventListener('input', checkPasswordMatch);
</script>
@endsection
