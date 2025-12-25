
@php
    $user = auth()->user();

    // Kunin ang barangay ng NAKA-LOGIN na CAPTAIN
    $barangay = $user->barangay ?? null;

    // Gumamit ng barangay logo kung meron
    $logo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : asset('images/logo.png'); // default
@endphp
<!-- Include Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
@php
    use Illuminate\Support\Str;
    $active = request()->path();

    // Detect default dropdown
    $defaultDropdown = null;
    if (request()->routeIs('secretary.document_requests.*') || request()->routeIs('secretary.documents.*')) {
        $defaultDropdown = 'services';
    } elseif (Str::startsWith($active, 'secretary/events-announcements') || Str::startsWith($active, 'secretary/announcements')) {
        $defaultDropdown = 'events';
    } elseif (Str::startsWith($active, 'secretary/residents') || Str::startsWith($active, 'secretary/officials')) {
        $defaultDropdown = 'population';
    } elseif (request()->routeIs('secretary.reports.*') || request()->routeIs('vawc_reports.*')) {
        $defaultDropdown = 'reports';
         } elseif (request()->routeIs('secretary.brco.*') || request()->routeIs('brco.*')) {
        $defaultDropdown = 'reports';
    }
    // Barangay Section (check kung nasa sub-link route)
elseif (request()->routeIs('secretary.blotter.*') || request()->routeIs('secretary.faq.*')) {
    $defaultDropdown = 'barangay_section';
}
@endphp

<!-- Sidebar Container -->
<div x-data="{ openDropdown: '{{ $defaultDropdown }}' }"
     style="width: 250px; background-color: #ffffff; padding: 30px 20px; display: flex; flex-direction: column; font-family: 'Segoe UI', sans-serif; height: 100vh; border-right: 1px solid #e5e7eb; overflow-y: auto;">

    <!-- Logo -->
        <div style="margin-bottom: 20px; text-align: center;">
        <img src="{{ $logo }}" alt="Barangay Logo" style="height: 80px; object-fit: contain; display: inline-block;">
    </div>

    <!-- Header -->
    <div style="margin-bottom: 32px; text-align: center;">
        <a href="{{ route('secretary.dashboard') }}" style="text-decoration: none; color: #1e3a8a; font-size: 20px; font-weight: 700;">
            Secretary Panel
        </a>
    </div>

    <!-- Navigation -->
    <nav style="display: flex; flex-direction: column; gap: 16px;">

        <!-- Dashboard -->
        <a href="{{ route('secretary.dashboard') }}" class="sidebar-link {{ request()->routeIs('secretary.dashboard') ? 'active' : '' }}">
            <i data-lucide="home" class="icon"></i>
            <span>Dashboard</span>
        </a>
        
        
         <a href="{{ route('secretary.document_requests.index') }}" class="sidebar-link {{ request()->routeIs('secretary.document_requests.index') ? 'active' : '' }}">
            <i data-lucide="inbox" class="icon"></i>
            <span>Document Request</span>
        </a>

      
        <!-- Events Dropdown -->
        <div>
    <button @click="openDropdown = (openDropdown === 'events' ? null : 'events')"
            class="sidebar-link w-full flex items-center justify-between whitespace-nowrap"
            :class="{ 'active': openDropdown === 'events' }">
        <div class="flex items-center gap-3 whitespace-nowrap">
            <i data-lucide="megaphone" class="icon"></i>
            <span>Events and News</span>
        </div>
        <svg :class="{ 'rotate-180': openDropdown === 'events' }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="openDropdown === 'events'" class="ml-4 mt-2 space-y-1" x-cloak>
        <a href="{{ url('/secretary/events-announcements') }}" class="sidebar-link whitespace-nowrap {{ $active == 'secretary/events-announcements' ? 'active' : '' }}">
            <i data-lucide="calendar-check" class="icon"></i>
            <span>Barangay Events</span>
        </a>
        <a href="{{ route('secretary.announcements.index') }}" class="sidebar-link whitespace-nowrap {{ request()->routeIs('secretary.announcements.index') ? 'active' : '' }}">
            <i data-lucide="message-square" class="icon"></i>
            <span>Announcements</span>
        </a>
    </div>
</div>


        <!-- Population Dropdown -->
        <div>
            <button @click="openDropdown = (openDropdown === 'population' ? null : 'population')"
                    class="sidebar-link w-full flex items-center justify-between"
                    :class="{ 'active': openDropdown === 'population' }">
                <div class="flex items-center gap-3">
                    <i data-lucide="users" class="icon"></i>
                    <span>Population</span>
                </div>
                <svg :class="{ 'rotate-180': openDropdown === 'population' }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openDropdown === 'population'" class="ml-4 mt-2 space-y-1" x-cloak>
                <a href="{{ route('secretary.residents.index') }}" class="sidebar-link {{ $active == 'secretary/residents' ? 'active' : '' }}">
                    <i data-lucide="user" class="icon"></i>
                    <span>List of Residents</span>
                </a>
                <a href="{{ route('secretary.officials.index') }}" class="sidebar-link {{ $active == 'secretary/officials' ? 'active' : '' }}">
                    <i data-lucide="user-cog" class="icon"></i>
                    <span>Barangay Officials</span>
                </a>
            </div>
        </div>

        <!-- Reports Dropdown -->
        <div>
            <button @click="openDropdown = (openDropdown === 'reports' ? null : 'reports')"
                    class="sidebar-link w-full flex items-center justify-between"
                    :class="{ 'active': openDropdown === 'reports' }">
                <div class="flex items-center gap-3">
                    <i data-lucide="bar-chart-3" class="icon"></i>
                    <span>Reports</span>
                </div>
                <svg :class="{ 'rotate-180': openDropdown === 'reports' }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openDropdown === 'reports'" class="ml-4 mt-2 space-y-1" x-cloak>
                <a href="{{ route('secretary.reports.index') }}" class="sidebar-link {{ request()->routeIs('secretary.reports.index') ? 'active' : '' }}">
                    <i data-lucide="trash-2" class="icon"></i>
                    <span>Waste Reports</span>
                </a>
                <a href="{{ route('secretary.reports.services') }}" class="sidebar-link {{ request()->routeIs('secretary.reports.services*') ? 'active' : '' }}">
                    <i data-lucide="clipboard-list" class="icon"></i>
                    <span>Service Reports</span>
                </a>
                <a href="{{ route('vawc_reports.index') }}" class="sidebar-link {{ request()->routeIs('vawc_reports.*') ? 'active' : '' }}">
                    <i data-lucide="shield" class="icon"></i>
                    <span>VAWC Reports</span>
                </a>
                <a href="{{ route('secretary.brco.index') }}" class="sidebar-link {{ request()->routeIs('secretary.brco.*') ? 'active' : '' }}">
                    <i data-lucide="map" class="icon"></i>
                    <span>BRCO Reports</span>
                </a>
            </div>
        </div>

        <!-- Approval Accounts -->
        <a href="/secretary/approval-accounts" class="sidebar-link {{ $active == 'secretary/approval-accounts' ? 'active' : '' }}">
            <i data-lucide="user-check" class="icon"></i>
            <span>Residents Approval</span>
        </a>

   <!-- FAQ Management -->
<a href="{{ route('secretary.faq.index') }}" 
   class="sidebar-link {{ request()->routeIs('secretary.faq.*') ? 'active' : '' }}">
    <i data-lucide="book" class="icon"></i>
    <span>FAQ Management</span>
</a>

<!-- Blotter Logbook -->
<a href="{{ route('secretary.blotter.index') }}" 
   class="sidebar-link {{ request()->routeIs('secretary.blotter.*') ? 'active' : '' }}">
    <i data-lucide="book-open" class="icon"></i>
    <span>Blotter Logbook</span>
</a>




    </nav>
</div>

<!-- Sidebar Styles -->
<style>
  .sidebar-container {
    width: 250px;
    height: 100vh;          /* full viewport height */
    overflow-y: auto;       /* allow vertical scrolling */
    scrollbar-width: thin;  /* Firefox thin scrollbar */
}

/* Chrome, Edge, Safari scrollbar */
.sidebar-container::-webkit-scrollbar {
    width: 6px;  /* puwede mo paliitin dito, e.g., 4px */
}
.sidebar-container::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}
.sidebar-container::-webkit-scrollbar-track {
    background: transparent;
}


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

    [x-cloak] { display: none !important; }
</style>


<!-- Lucide Init -->
<script>
    lucide.createIcons();
</script>
