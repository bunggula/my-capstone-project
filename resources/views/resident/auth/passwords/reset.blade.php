@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center mb-6">I-reset ang iyong Password</h2>

        {{-- Error Message --}}
        <div id="error-message" class="hidden mb-4 text-red-600 text-sm text-center"></div>

        <form method="POST" action="{{ route('resident.password.update') }}" onsubmit="return validateForm()">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- Password --}}
            <div class="mb-4 relative">
                <label for="password" class="block text-gray-700">Bagong Password</label>
                <input type="password" name="password" id="password" required minlength="6"
                    class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span onclick="togglePassword('password', this)" class="absolute right-3 top-[48px] cursor-pointer text-sm text-gray-500">
                    👁️
                </span>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-6 relative">
                <label for="password_confirmation" class="block text-gray-700">Kumpirmahin ang Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full border border-gray-300 rounded px-4 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-[48px] cursor-pointer text-sm text-gray-500">
                    👁️
                </span>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200">
                I-reset ang Password
            </button>
        </form>
    </div>
</div>

{{-- SweetAlert for success --}}
@if (session('status'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'success',
                title: 'Password Updated!',
                text: 'You can now use your new password.',
                confirmButtonColor: '#3085d6'
            });
        });
    </script>
@endif

{{-- Password toggle + validation --}}
<script>
    function togglePassword(fieldId, iconElement) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
        iconElement.textContent = field.type === 'password' ? '👁️' : '🙈';
    }

    function validateForm() {
        const password = document.getElementById('password').value.trim();
        const confirm = document.getElementById('password_confirmation').value.trim();
        const errorDiv = document.getElementById('error-message');

        if (password.length < 6) {
            errorDiv.innerText = 'Password must be at least 6 characters.';
            errorDiv.classList.remove('hidden');
            return false;
        }

        if (password !== confirm) {
            errorDiv.innerText = 'Passwords do not match.';
            errorDiv.classList.remove('hidden');
            return false;
        }

        errorDiv.classList.add('hidden');
        return true;
    }
</script>
@endsection
