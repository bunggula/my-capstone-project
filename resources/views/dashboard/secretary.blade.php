@extends('layouts.app')

@section('title', 'Secretary Dashboard')

@section('content')
<div class="flex h-screen w-screen overflow-hidden font-sans">

    <!-- Sidebar -->
    @include('partials.secretary-sidebar')

    <!-- Main Area -->
    <main class="flex-1 relative overflow-hidden">

        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('/images/back.jpg');"></div>

        <!-- White Overlay -->
        <div class="absolute inset-0 bg-white bg-opacity-80 z-10"></div>

        <!-- Floating Bubbles -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            @for ($i = 1; $i <= 15; $i++)
                <span class="bubble"></span>
            @endfor
        </div>

        <!-- Content -->
      <div class="relative z-20 overflow-y-auto p-8" style="height: calc(100vh);">
    <div class="max-w-7xl mx-auto flex justify-between items-center mb-10">
        <h2 class="text-3xl font-bold text-black">Dashboard</h2>

        <div class="flex items-center space-x-6 relative">

         <!-- Notifications Dropdown -->
<div x-data='{
    open: false,
    visible: 5,
    notifications: {!! json_encode($notifications) !!},
    loadMore() { this.visible += 5 },
    markRead(key) {
        let notif = this.notifications.find(n => n.key === key);
        if (notif) notif.read = true;

        // Optional: Update read status sa server
        fetch("/secretary/notifications/mark-read", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ key })
        });
    },
    markAllRead() {
        this.notifications.forEach(n => n.read = true);
        // Optional: Mark all read sa server
        fetch("/secretary/notifications/mark-all-read", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        });
    }
}' class="relative">

    <!-- Bell Button -->
    <button @click="open = !open; markAllRead()"
            class="relative focus:outline-none w-10 h-10 flex items-center justify-center hover:text-indigo-600 transition"
            :class="{ 'animate-bell-shake': notifications.some(n => !n.read) }">
        <!-- Bell Icon -->
        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24" stroke-width="1.5"
             stroke="currentColor" class="w-8 h-8 text-gray-700">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.857 17.082a1.5 1.5 0 01-1.357.918H10.5a1.5 1.5 0 01-1.357-.918M8.25 9V8.25a4.5 4.5 0 119 0V9m1.5 0a1.5 1.5 0 011.5 1.5v2.25a7.5 7.5 0 01-15 0V10.5a1.5 1.5 0 011.5-1.5h12z" />
        </svg>

        <!-- Red Dot for Unread -->
        <template x-if="notifications.some(n => !n.read)">
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
        </template>
    </button>

    <!-- Dropdown Panel -->
  <div 
    x-cloak
    x-show="open" 
    @click.outside="open = false" 
    x-transition
    class="absolute right-0 mt-3 w-96 bg-white rounded-xl shadow-lg border border-gray-200 z-50"
    style="min-width: 20rem;"
>


        <!-- Header -->
        <div class="px-4 py-3 border-b flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Notifications</h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
            <template x-for="(notif, index) in notifications" :key="notif.key">
                <div x-show="index < visible"
                     @click="markRead(notif.key)"
                     class="px-4 py-3 hover:bg-indigo-50 transition cursor-pointer rounded-md m-1"
                     :class="{ 'bg-indigo-50': !notif.read }">
                    <p class="text-sm text-gray-800 font-medium" x-text="notif.message"></p>
                    <p class="text-xs text-gray-500 mt-1" x-text="notif.created_at"></p>
                </div>
            </template>

            <!-- Empty state -->
            <div x-show="notifications.length === 0" class="p-4 text-sm text-gray-500 text-center">
                No new notifications
            </div>

            <!-- Show More Button -->
            <div class="p-2 text-center" x-show="visible < notifications.length">
                <button @click="loadMore()" class="text-indigo-600 text-sm font-medium hover:underline">
                    Show More
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 text-right border-t">
            <button @click="open = false" class="text-indigo-600 text-sm font-medium hover:underline">
                Close
            </button>
        </div>
    </div>
</div>



                     <!-- Profile Dropdown -->
<div x-data="{ profileOpen: false, loggingOut: false }" class="relative">

    <!-- Profile Button -->
    <button 
        @click="profileOpen = !profileOpen" 
        class="w-10 h-10 flex items-center justify-center text-2xl text-black focus:outline-none"
    >
        👤
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="profileOpen"
        @click.outside="profileOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 text-sm text-gray-800 z-50"
    >
        <div class="px-4 py-2 font-semibold text-gray-600 border-b">
            {{ Auth::user()->first_name }}
        </div>
        <a href="{{ route('settings.profile') }}" class="block px-4 py-2 hover:bg-gray-100">
            Edit Profile
        </a>
        <a href="{{ route('settings.password') }}" class="block px-4 py-2 hover:bg-gray-100">
            Change Password
        </a>

        <!-- Logout Button with Animation -->
       <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button
        type="submit"
        @click.prevent="loggingOut = true; setTimeout(() => $el.closest('form').submit(), 200)"
        :class="{'scale-95': loggingOut}"
        class="w-full text-left px-4 py-2 flex items-center gap-2 hover:bg-gray-100 transition-transform transform"
    >
        <span :class="{'opacity-0': loggingOut, 'transition-opacity duration-200': true}">
            Logout
        </span>
        <svg 
            class="h-4 w-4 text-gray-800 animate-spin"
            :class="{'opacity-0': !loggingOut, 'opacity-100 transition-opacity duration-200': loggingOut}" 
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
    </button>
</form>

    </div>
</div>

                </div>
            </div>

<!-- 🌈 Dashboard Main Container -->
<div class="max-w-7xl mx-auto px-4 mt-6 grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- 🧭 LEFT SIDE: CARDS (3 columns) -->
    <div class="lg:col-span-3 space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @php
                $cards = [
                    ['title'=>'Residents','description'=>"View and manage residents' data.",'icon'=>'🧑‍🤝‍🧑','badge'=>$totalResidents,'badgeColor'=>'green','route'=>route('secretary.residents.index')],
                    ['title'=>'Announcements','description'=>'Check wide announcements.','icon'=>'📢','badge'=>$totalAnnouncements,'badgeColor'=>'yellow','route'=>route('secretary.announcements.index')],
                    ['title'=>'Events','description'=>'Browse all events.','icon'=>'🎉','badge'=>$totalEvents,'badgeColor'=>'green','route'=>route('secretary.events.index')],
                    ['title'=>'Services','description'=>'Review and manage barangay requests.','icon'=>'📝','badge'=>$totalRequests,'badgeColor'=>'cyan','route'=>route('secretary.document_requests.index')],
                    ['title'=>'New Requests','description'=>'Pending document requests awaiting action.','icon'=>'📄','badge'=>$totalNewRequests,'badgeColor'=>'purple','route'=>route('secretary.document_requests.index')],
                    ['title'=>'Residents Approval','description'=>'Pending Residents registrations.','icon'=>'✅','badge'=>$totalApprovedAccounts,'badgeColor'=>'red','route'=>url('/secretary/approval-accounts')],
                ];
            @endphp

            @foreach ($cards as $card)
                <a href="{{ $card['route'] }}" 
                   class="bg-white rounded-2xl shadow-xl p-6 border border-transparent hover:bg-blue-50 hover:shadow-2xl hover:border-blue-300 transition transform hover:scale-[1.03] duration-300 relative flex flex-col justify-between h-full animate-fade-up">

                    <!-- Badge -->
                    @if (isset($card['badge']))
                        <span class="absolute top-4 right-4 bg-{{ $card['badgeColor'] }}-100 text-{{ $card['badgeColor'] }}-700 text-sm font-bold px-3 py-1 rounded-full shadow animate-badge">
                            {{ $card['badge'] ?? 0 }}
                        </span>
                    @endif

                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-white rounded-xl shadow-lg flex items-center justify-center text-3xl">
                            {{ $card['icon'] }}
                        </div>
                        <h3 class="text-xl font-bold text-black">{{ $card['title'] }}</h3>
                    </div>
                    <p class="text-sm text-black">{{ $card['description'] }}</p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- 📅 RIGHT SIDE: Upcoming (1 column) -->
    <div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow-md p-2 compact-upcoming h-full overflow-y-auto">

            <!-- Mini Calendar -->
                <div class="bg-blue-50 rounded-md p-2 shadow-inner text-center mb-2">
            <h4 class="text-xs font-semibold text-gray-700 mb-0.5">Today's Date</h4>
            <p class="text-xs font-bold text-gray-900">
                {{ \Carbon\Carbon::now()->format('l, M d, Y') }}
            </p>
        </div>

            <!-- Upcoming Announcements & Events -->
            <div class="space-y-3">
                <h4 class="text-xs font-semibold text-gray-700 text-center">Upcoming Announcements & Events</h4>


 <!-- Announcements -->
        <div class="mb-2">
            <h4 class="text-sm font-bold mb-1 text-yellow-600 text-center">Announcements</h4>
            @if($upcomingAnnouncements->count())
                @php
                    $nextAnnouncement = $upcomingAnnouncements->sortBy('date')
                        ->firstWhere(fn($a) => \Carbon\Carbon::parse($a->date)->isToday() || \Carbon\Carbon::parse($a->date)->isFuture());
                @endphp
                <ul class="space-y-0.5 max-h-24 overflow-y-auto pr-1">
                    @foreach ($upcomingAnnouncements as $announcement)
                        @php $isNext = $announcement->id === $nextAnnouncement?->id; @endphp
                        <li class="border-b pb-0.5 hover:translate-x-0.5 transition-transform duration-200 
                                   {{ $isNext ? 'bg-yellow-100 rounded p-0.5 animate-bounce-slow' : '' }}">
                            <h5 class="text-xs font-bold text-gray-900 truncate">{{ $announcement->title }}</h5>
                            <p class="text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($announcement->date)->format('M d, Y') }}
                                @ {{ \Carbon\Carbon::parse($announcement->time)->format('g:i A') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-xs text-center">No upcoming announcements.</p>
            @endif
        </div>

        <!-- Events -->
        <div>
            <h4 class="text-sm font-bold mb-1 text-green-600 text-center">Events</h4>
            @if($upcomingEvents->count())
                @php
                    $nextEvent = $upcomingEvents->sortBy('date')
                        ->firstWhere(fn($e) => \Carbon\Carbon::parse($e->date)->isToday() || \Carbon\Carbon::parse($e->date)->isFuture());
                @endphp
                <ul class="space-y-0.5 max-h-24 overflow-y-auto pr-1">
                    @foreach ($upcomingEvents as $event)
                        @php $isNextEvent = $event->id === $nextEvent?->id; @endphp
                        <li class="border-b pb-0.5 hover:translate-x-0.5 transition-transform duration-200 
                                   {{ $isNextEvent ? 'bg-green-100 rounded p-0.5 animate-bounce-slow' : '' }}">
                            <h5 class="text-xs font-bold text-gray-900 truncate">{{ $event->title }}</h5>
                            <p class="text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}
                                @ {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-[9px] text-center">No upcoming events.</p>
            @endif
            </div>
        </div>
    </div>
    </div>
</div>


<div class="mt-8">
            @include('partials.secretary_chart')
        </div>



        </div>

    </main>
</div>

<style>
[x-cloak] {
    display: none !important;
}

 /* Card and Badge Animations */
    @keyframes fade-up { 0% { opacity:0; transform:translateY(20px);} 100% {opacity:1; transform:translateY(0);} }
    .animate-fade-up { animation: fade-up 0.8s ease-out forwards; }

  
@keyframes fade-up { 
    0% { opacity:0; transform:translateY(20px);} 
    100% {opacity:1; transform:translateY(0);} 
}
.animate-fade-up { animation: fade-up 0.8s ease-out forwards; }

/* Upcoming items hover effect */
.upcoming-item:hover { transform: translateX(5px); transition: transform 0.3s ease-in-out; }

/* Bell shake animation */
@keyframes bell-shake {
    0%, 100% { transform: rotate(0deg); }
    20% { transform: rotate(-15deg); }
    40% { transform: rotate(15deg); }
    60% { transform: rotate(-10deg); }
    80% { transform: rotate(10deg); }
}
.animate-bell-shake {
    animation: bell-shake 3s ease-in-out infinite;
}
</style>
@endsection
