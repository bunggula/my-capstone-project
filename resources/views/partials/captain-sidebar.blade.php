@php
    $user = auth()->user();

    // Kunin ang barangay ng NAKA-LOGIN na CAPTAIN
    $barangay = $user->barangay ?? null;

    // Gumamit ng barangay logo kung meron
    $logo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : asset('images/logo.png'); // default
@endphp

<!-- Include Lucide Icons CDN -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- White Sidebar -->
<div x-data="{ openDropdown: '{{ 
    (request()->routeIs('captain.documents.*') || request()->routeIs('captain.manage.*')) 
        ? 'services' 
        : ((request()->routeIs('captain.residents.*') || request()->routeIs('captain.new.*')) 
            ? 'population' 
            : ((request()->routeIs('captain.events.*') || request()->routeIs('captain.announcements.*')) 
                ? 'events' 
                : '')) 
}}' }"
     style="width: 250px; background-color: #ffffff; padding: 30px 20px; display: flex; flex-direction: column; font-family: 'Segoe UI', sans-serif; height: 100vh; border-right: 1px solid #e5e7eb; overflow-y: auto;">

    <!-- Centered Dynamic Logo -->    
    <div style="margin-bottom: 20px; text-align: center;">
        <img src="{{ $logo }}" alt="Barangay Logo" style="height: 80px; object-fit: contain; display: inline-block;">
    </div>

    <!-- Header -->
    <div style="margin-bottom: 32px; text-align: center;">
        <a href="{{ route('captain.dashboard') }}" style="text-decoration: none; color: #1e3a8a; font-size: 20px; font-weight: 700;">
             Captain Panel
        </a>
    </div>

    <!-- Navigation Links -->
    <nav style="display: flex; flex-direction: column; gap: 16px;">

        <a href="{{ route('captain.dashboard') }}" 
           class="sidebar-link {{ request()->routeIs('captain.dashboard') ? 'active' : '' }}">
            <i data-lucide="home" class="icon"></i>
            <span>Dashboard</span>
        </a>

        <!-- Services Dropdown -->
        <div class="relative">
            <button @click="openDropdown = openDropdown === 'services' ? '' : 'services'" 
                    class="sidebar-link w-full flex items-center justify-between"
                    :class="{ 'active': openDropdown === 'services' }">
                <div class="flex items-center gap-2">
                    <i data-lucide="megaphone" class="icon"></i>
                    <span>Services</span>
                </div>
                <svg :class="{ 'rotate-180': openDropdown === 'services' }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="openDropdown === 'services'" x-transition x-cloak class="ml-4 mt-2 flex flex-col space-y-1">
                <a href="{{ route('captain.documents.index') }}" class="sidebar-link {{ request()->routeIs('captain.documents.*') ? 'active' : '' }}">
                    <i data-lucide="inbox" class="icon"></i>
                    <span>Document Request</span>
                </a>
                <a href="{{ route('captain.manage.index') }}" class="sidebar-link {{ request()->routeIs('captain.manage.*') ? 'active' : '' }}">
                    <i data-lucide="message-square" class="icon"></i>
                    <span>Manage Document</span>
                </a>
            </div>
        </div>

        <!-- Population Dropdown -->
        <div class="relative">
            <button @click="openDropdown = openDropdown === 'population' ? '' : 'population'" 
                    class="sidebar-link w-full flex items-center justify-between"
                    :class="{ 'active': openDropdown === 'population' }">
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="icon"></i>
                    <span>Population</span>
                </div>
                <svg :class="{ 'rotate-180': openDropdown === 'population' }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openDropdown === 'population'" x-transition x-cloak class="ml-4 mt-2 flex flex-col space-y-1">
                <a href="{{ route('captain.residents.index') }}" class="sidebar-link {{ request()->routeIs('captain.residents.*') ? 'active' : '' }}">
                    <i data-lucide="user" class="icon"></i>
                    <span>List of Residents</span>
                </a>
                <a href="{{ route('captain.new.index') }}" class="sidebar-link {{ request()->routeIs('captain.new.*') ? 'active' : '' }}">
                    <i data-lucide="user-plus" class="icon"></i>
                    <span>Barangay Officials</span>
                </a>
            </div>
        </div>

        <!-- Events Dropdown -->
        <div class="relative">
            <button @click="openDropdown = openDropdown === 'events' ? '' : 'events'" 
                    class="sidebar-link w-full flex items-center justify-between"
                    :class="{ 'active': openDropdown === 'events' }">
                <div class="flex items-center gap-2">
                    <i data-lucide="megaphone" class="icon"></i>
                    <span>Events And News</span>
                </div>
                <svg :class="{ 'rotate-180': openDropdown === 'events' }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="openDropdown === 'events'" x-transition x-cloak class="ml-4 mt-2 flex flex-col space-y-1">
                <a href="{{ route('captain.events.index') }}" class="sidebar-link {{ request()->routeIs('captain.events.*') ? 'active' : '' }}">
                    <i data-lucide="calendar-check" class="icon"></i>
                    <span>Barangay Events</span>
                </a>
                <a href="{{ route('captain.announcements.index') }}" class="sidebar-link {{ request()->routeIs('captain.announcements.*') ? 'active' : '' }}">
                    <i data-lucide="message-square" class="icon"></i>
                    <span>Announcements</span>
                </a>
            </div>
        </div>

        <!-- Other Links -->
        <a href="{{ route('captain.proposal.index') }}" class="sidebar-link {{ request()->routeIs('captain.proposal.index') ? 'active' : '' }}">
            <i data-lucide="file-text" class="icon"></i>
            <span>Proposals</span>
        </a>

        <a href="{{ route('captain.concerns.index') }}" class="sidebar-link {{ request()->routeIs('captain.concerns.*') ? 'active' : '' }}">
            <i data-lucide="alert-triangle" class="icon"></i>
            <span>General Concerns</span>
        </a>

        <a href="{{ route('captain.barangay-info.show') }}" class="sidebar-link {{ request()->routeIs('captain.barangay-info.*') ? 'active' : '' }}">
            <i data-lucide="info" class="icon"></i>
            <span>Barangay Info</span>
        </a>

        <a href="{{ route('captain.secretary.index') }}" class="sidebar-link {{ request()->routeIs('captain.secretary.*') ? 'active' : '' }}">
            <i data-lucide="user-plus" class="icon"></i>
            <span>Add Secretary</span>
        </a>

    </nav>
</div>
<!-- Sidebar Styling -->
<style>
.sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1e3a8a;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
        padding: 10px 12px;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        white-space: nowrap;   /* prevent wrapping text */
    }

    .sidebar-link .icon {
        width: 20px;
        height: 20px;
        stroke: #9ca3af;
        transition: stroke 0.2s ease;
    }

    .sidebar-link:hover {
        background-color: #f3f4f6;
        color: #1e3a8a;
    }

    .sidebar-link:hover .icon {
        stroke: #1e3a8a;
    }

    .sidebar-link.active {
        background-color: #1e3a8a;
        color: white;
        font-weight: 700;
    }

    .sidebar-link.active .icon {
        stroke: white;
    }
</style>

<!-- Init Lucide Icons -->
<script>
    lucide.createIcons();
</script>
