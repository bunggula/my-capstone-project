<?php
    $currentBarangayId = request('barangay');
    $municipality = null;

    if ($currentBarangayId) {
        $barangay = \App\Models\Barangay::with('municipality')->find($currentBarangayId);
        $municipality = $barangay ? $barangay->municipality : null;
    } else {
        // fallback default municipality
        $municipality = \App\Models\Municipality::first();
    }
?>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Sidebar Wrapper -->
<div style="width: 250px; background-color: #ffffff; padding: 30px 20px; display: flex; flex-direction: column; font-family: 'Segoe UI', sans-serif; height: 100vh; border-right: 1px solid #e5e7eb;">

    <!-- Header with Logo -->
    <div style="margin-bottom: 40px; text-align: center;">
        
     <img 
    src="<?php echo e($municipality && $municipality->logo 
            ? asset('storage/' . $municipality->logo)
            : asset('images/default_logo.png')); ?>" 
    alt="Municipality Logo" 
    style="width: 80px; height: auto; margin: 0 auto 10px;"
>

        
        
        <a href="<?php echo e(route('abc.dashboard')); ?>" style="text-decoration: none; color: #1e3a8a; font-size: 22px; font-weight: 700;">
             ABC Admin
        </a>
    </div>

    <!-- Navigation Links -->
        <nav style="display: flex; flex-direction: column; gap: 20px;">
    
            <a href="<?php echo e(route('abc.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('abc.dashboard') ? 'active' : ''); ?>">
                <i data-lucide="home" class="icon"></i>
                <span>Dashboard</span>
            </a>
<div x-cloak x-data="{ 
        openPopulation: <?php echo e(request()->routeIs('abc.residents.*') || request()->routeIs('abc.all.*') ? 'true' : 'false'); ?>,
        openFlyout: ''  }" class="relative">

    <button 
        @click="openPopulation = !openPopulation; openFlyout=''"  
        :class="['nav-link flex items-center justify-between w-full', (openPopulation ? 'active' : '')]">
        <div class="flex items-center gap-2">
            <i data-lucide="users" class="icon"></i>
            <span>Population</span>
        </div>
        <svg :class="{ 'rotate-180': openPopulation }" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="openPopulation" class="ml-4 mt-2 space-y-1">

        <a href="<?php echo e(route('abc.residents.index')); ?>"
           :class="[
               'nav-link flex items-center w-full whitespace-nowrap', 
               <?php echo e(request()->routeIs('abc.residents.*') ? "'active'" : "''"); ?>

           ]">
            <div class="flex items-center gap-2">
                <i data-lucide="user" class="icon"></i>
                <span>Residents</span>
            </div>
        </a>

        <a href="<?php echo e(route('abc.all.index')); ?>"
           :class="[
               'nav-link flex items-center w-full whitespace-nowrap', 
               <?php echo e(request()->routeIs('abc.all.*') ? "'active'" : "''"); ?>

           ]">
            <div class="flex items-center gap-2">
                <i data-lucide="users" class="icon"></i>
                <span>Barangay Officials</span>
            </div>
        </a>

    </div>
</div>


<!-- Services Button -->
<div class="relative">
    <a href="<?php echo e(route('abc.services.index')); ?>" 
       class="nav-link flex items-center justify-between w-full <?php echo e(request()->routeIs('abc.services.*') ? 'active' : ''); ?>">
        
        <div class="flex items-center gap-2">
            <i data-lucide="clipboard-list" class="icon"></i>
            <span>Services</span>
        </div>
    </a>
</div>



   <!-- Barangay Events (Dropdown) -->
<!-- Announcement Group (Sidebar) -->
<div 
   x-cloak x-data="{ open: <?php echo e(request()->routeIs('abc.announcements.*') || request()->routeIs('abc.events.*') ? 'true' : 'false'); ?> }" 
    class="nav-group"
>
    <!-- Parent Button -->
    <button 
        @click="open = !open"
        class="nav-link flex items-center justify-between w-full 
        <?php echo e(request()->routeIs('abc.announcements.*') || request()->routeIs('abc.events.*') ? 'active' : ''); ?>">
        
        <div class="flex items-center gap-2">
            <i data-lucide="calendar" class="icon"></i>
            <span>Announcement</span>
        </div>

        <svg :class="{ 'rotate-180': open }" 
             class="w-4 h-4 transition-transform" 
             fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Items -->
    <div x-cloak x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
        <!-- Announcements Link -->
        <a href="<?php echo e(route('abc.announcements.index')); ?>"
           class="nav-sublink nav-link <?php echo e(request()->routeIs('abc.announcements.*') ? 'active' : ''); ?>">
            <i data-lucide="megaphone" class="icon"></i>
            <span>Announcements</span>
        </a>

<!-- Main Events Button -->
<div class="relative">
    <a href="<?php echo e(route('abc.events.index')); ?>" 
       class="nav-link nav-sublink flex items-center justify-between w-full text-left 
              py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition 
              <?php echo e(request()->routeIs('abc.events.*') ? 'active' : ''); ?>">
        
        <div class="flex items-center gap-2">
            <i data-lucide="calendar-check" class="icon"></i>
            <span class="whitespace-nowrap">Barangay Events</span>
        </div>
    </a>
</div>


    </div>
</div>



<!-- Concerns Button -->
<div class="relative">
    <a href="<?php echo e(route('abc.concerns.index')); ?>" 
       class="nav-link flex items-center justify-between w-full <?php echo e(request()->routeIs('abc.concerns.*') ? 'active' : ''); ?>">
        
        <div class="inline-flex items-center">
            <i data-lucide="alert-triangle" class="icon mr-1"></i>
            <span>Concerns</span>
        </div>
    </a>
</div>


<!-- Proposals Button -->
<div class="relative">
    <a href="<?php echo e(route('abc.proposals.index')); ?>" 
       class="nav-link flex items-center justify-between w-full <?php echo e(request()->routeIs('abc.proposals.*') ? 'active' : ''); ?>">
        
        <div class="inline-flex items-center">
            <i data-lucide='file-bar-chart' class='icon mr-1'></i>
            <span>Proposals</span>
        </div>
    </a>
</div>


<div x-cloak x-data="{ openAccounts: false, openOfficials: false, positionAccounts: 'bottom', positionOfficials: 'bottom' }" class="space-y-2">

<div class="relative">
    <a href="<?php echo e(route('abc.accounts.list')); ?>" 
       class="nav-link flex items-center justify-between w-full
              <?php echo e(request()->routeIs('abc.accounts.*') ? 'active' : ''); ?>">
        <div class="inline-flex items-center">
            <i data-lucide="user-check" class="icon mr-1"></i>
            <span>Accounts</span>
        </div>
    </a>
</div>
    </nav>
</div>

<!-- Styles -->
<style>
    /* ==============================
   Base Styles
   ============================== */
.nav-link,
.nav-sublink,
.flyout-link {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 500;
    color: #1e3a8a;       /* Dark blue text */
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s ease-in-out;
}

/* Icon Styles */
.nav-link .icon,
.nav-sublink .icon,
.flyout-link .icon {
    width: 20px;
    height: 20px;
    stroke: #1e3a8a;
    flex-shrink: 0;
    transition: stroke 0.2s ease;
}

/* ==============================
   Hover State
   ============================== */
.nav-link:hover,
.nav-sublink:hover,
.flyout-link:hover {
    background-color: #1e3a8a;
    color: white;
}

.nav-link:hover .icon,
.nav-sublink:hover .icon,
.flyout-link:hover .icon {
    stroke: white;
}

/* ==============================
   Active State
   ============================== */
.nav-link.active,
.nav-sublink.active,
.flyout-link.active {
    background-color: #1e3a8a;
    color: white;
    font-weight: 700;
}

.nav-link.active .icon,
.nav-sublink.active .icon,
.flyout-link.active .icon {
    stroke: white;
}

/* ==============================
   Flyout Panel
   ============================== */
.flyout-panel a {
    display: block;
}
[x-cloak] {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}
</style>

<!-- Init Lucide Icons -->
<script>
    lucide.createIcons();
</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/partials/abc-sidebar.blade.php ENDPATH**/ ?>