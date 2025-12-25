<!-- Header / Topbar -->
<div class="bg-white p-4 flex justify-between items-center border-b border-gray-200 font-sans z-10">

    <!-- Left Title -->
    <div class="text-blue-900 text-xl font-bold">
        Dashboard
    </div>

    <!-- Profile Dropdown -->
    <div x-cloak x-data="{ open: false, loggingOut: false }" class="relative">
        <button @click="open = !open" class="text-black text-2xl focus:outline-none">
            👤
        </button>

        <!-- Dropdown -->
        <div 
            x-show="open" 
            @click.away="open = false"
            x-transition
            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 text-sm text-gray-800 z-50"
        >
            <div class="px-4 py-2 font-semibold text-gray-600 border-b">
                {{ Auth::user()->first_name ?? 'ABC' }}
            </div>
            <a href="{{ route('settings.profile') }}" class="block px-4 py-2 hover:bg-gray-100">Edit Profile</a>
            <a href="{{ route('settings.password') }}" class="block px-4 py-2 hover:bg-gray-100">Change Password</a>

             <!-- Logout Form with Animation -->
            <form method="POST" action="{{ route('logout') }}" class="logoutForm">
                @csrf
                <button
                    type="submit"
                    class="logoutButton w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center gap-2"
                >
                    <span class="logoutText">Logout</span>
                    <svg class="logoutSpinner hidden animate-spin h-4 w-4 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Optional CSS for smooth button animation -->
<style>
.logoutButton {
    transition: transform 0.15s ease, opacity 0.2s ease;
}
.logoutText.opacity-0 {
    opacity: 0;
}
.logoutSpinner {
    transition: opacity 0.2s ease;
}
</style>

<!-- JS for Logout Button Animation -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Select all logout forms (works if multiple dropdowns exist)
    document.querySelectorAll('.logoutForm').forEach(form => {
        const button = form.querySelector('.logoutButton');
        const text = form.querySelector('.logoutText');
        const spinner = form.querySelector('.logoutSpinner');

        button.addEventListener('click', (e) => {
            e.preventDefault(); // prevent immediate submit

            // Show spinner and hide text
            text.classList.add('opacity-0');
            spinner.classList.remove('hidden');

            // Subtle shrink effect
            button.classList.add('scale-95');

            // Submit form after short delay for smooth effect
            setTimeout(() => {
                form.submit();
            }, 200); // 200ms delay
        });
    });
});
</script>
