<!-- Secretary Topbar -->
<div class="bg-blue-900 px-6 py-4 flex justify-between items-center border-b border-gray-300 z-20 relative">
    <!-- Panel Title -->
    <h1 class="text-white text-lg font-semibold">
        Dashboard
    </h1>

    <!-- User Dropdown -->
    <div x-cloak x-data="{ open: false }" class="relative">
        <button @click="open = !open"
                class="text-white font-semibold focus:outline-none flex items-center gap-1 hover:text-gray-200 transition">
            <?php echo e(ucfirst(Auth::user()->role)); ?> <?php echo e(Auth::user()->first_name); ?> — Barangay <?php echo e(Auth::user()->barangay->name ?? 'N/A'); ?>

            <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Dropdown -->
        <div x-show="open" @click.away="open = false" x-transition
             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 text-sm text-gray-800 z-30">
            <a href="<?php echo e(route('settings.profile')); ?>" class="block px-4 py-2 hover:bg-gray-100 transition">
                Edit Profile
            </a>
            <a href="<?php echo e(route('settings.password')); ?>" class="block px-4 py-2 hover:bg-gray-100 transition">
                Change Password
            </a>

            <!-- Logout Form with Animation -->
            <form method="POST" action="<?php echo e(route('logout')); ?>" id="logoutForm">
                <?php echo csrf_field(); ?>
                <button
                    type="submit"
                    id="logoutButton"
                    class="w-full text-left px-4 py-2 hover:bg-gray-100 transition flex items-center gap-2"
                >
                    <span id="logoutText">Logout</span>
                    <svg id="logoutSpinner" class="hidden animate-spin h-4 w-4 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
#logoutButton {
    transition: transform 0.15s ease, opacity 0.2s ease;
}
#logoutText.opacity-0 {
    opacity: 0;
}
#logoutSpinner {
    transition: opacity 0.2s ease;
}
</style>

<!-- JS for Logout Button Animation -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const logoutButton = document.getElementById('logoutButton');
    const logoutText = document.getElementById('logoutText');
    const logoutSpinner = document.getElementById('logoutSpinner');
    const logoutForm = document.getElementById('logoutForm');

    logoutButton.addEventListener('click', (e) => {
        e.preventDefault(); // prevent immediate submit

        // Show spinner and hide text
        logoutText.classList.add('opacity-0');
        logoutSpinner.classList.remove('hidden');

        // Subtle shrink effect
        logoutButton.classList.add('scale-95');

        // Submit form after short delay for smooth effect
        setTimeout(() => {
            logoutForm.submit();
        }, 200); // 200ms delay
    });
});
</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/partials/secretary-header.blade.php ENDPATH**/ ?>