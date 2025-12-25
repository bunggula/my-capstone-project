@extends('layouts.app')

@section('title', 'ABC Announcements')

@section('content')
<div x-data="{ 
        open: false, 
        announcement: null,
        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },
        formatTime(time) {
            if (!time) return '-';
            return new Date('1970-01-01T' + time).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        }
    }" class="flex w-screen h-screen font-sans overflow-hidden">
    {{-- Sidebar --}}
    @include('partials.secretary-sidebar')

    {{-- Main Content --}}
    <main class="flex-1 flex flex-col overflow-hidden">
        {{-- Header --}}
        @include('partials.secretary-header')

        {{-- Page Content --}}
        <section class="flex-1 overflow-y-auto p-6 bg-gray-50">
         

            <div class="flex flex-col gap-2 mb-6">
    {{-- Header --}}
    <h1 class="text-3xl font-bold text-gray-800">Announcements</h1>
    {{-- 📊 All Announcements Card --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    
    {{-- 📊 All Announcements Card --}}
    <a href="{{ route('secretary.announcements.index') }}"
       class="p-4 rounded shadow border-l-4 text-sm transition 
              bg-blue-100 border-blue-500 text-blue-700 
              hover:bg-blue-200 hover:border-blue-600 hover:shadow-md cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold">All Announcements</h4>
                <p class="text-2xl font-bold mt-1 text-blue-900">{{ $allAnnouncementsCount ?? 0 }}</p>
            </div>
            {{-- 📢 Icon --}}
            <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 19V6l12-2v13l-12 2zM3 6h3v13H3z" />
            </svg>
        </div>
    </a>

</div>

{{-- 🔍 Search + Filter Container --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">

    {{-- 🔍 Search + Filter Form --}}
    <form method="GET" action="{{ route('secretary.announcements.index') }}" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

        {{-- 🔍 Search Bar --}}
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search announcements..." 
                class="w-full px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 text-sm"
            >
        </div>

        {{-- 📅 Month Filter --}}
        <div>
            <select name="month" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring focus:ring-indigo-200">
                <option value="">All Months</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 📅 Year Filter --}}
        <div>
            <select name="year" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring focus:ring-indigo-200">
                <option value="">All Years</option>
                @foreach(range(date('Y'), date('Y')-5) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- 🧹 Clear Filters --}}
        @if(request('search') || request('month') || request('year'))
            <a href="{{ route('secretary.announcements.index') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow text-sm transition">
                Clear
            </a>
        @endif

    </form>
</div>


{{-- 📜 Results Info --}}
@if(request('search') || request('posted_by'))
    <p class="text-sm text-gray-500 text-right mb-4">
        Showing results
        @if(request('search'))
            for title: <strong>{{ request('search') }}</strong>
        @endif
        @if(request('posted_by'))
            @if(request('search')) and @endif
            posted by:
            <strong>
                @switch(request('posted_by'))
                 
                    @case('abc_president') ABC President @break
                    @case('sk_president') SK President @break
                    @default {{ ucfirst(request('posted_by')) }}
                @endswitch
            </strong>
        @endif
    </p>
@endif



{{-- Table --}}
@if ($announcements->count())
<div class="bg-white shadow rounded-lg p-4">
    <div class="overflow-x-auto">
        <table class="w-full table-auto border text-sm">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">No.</th>
                    <th class="px-6 py-3 text-left">Announcement</th>
                    <th class="px-6 py-3 text-left">Date</th>
               <th class="px-6 py-3 text-left">Posted Date</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @foreach ($announcements as $announcement)
                <tr class="{{ $loop->even ? 'bg-gray-100' : 'bg-white' }} hover:bg-gray-200">
                    {{-- Row number --}}
                    <td class="px-6 py-4 align-top">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 align-top">
                        {{ $announcement->title }}
                    </td>

                    {{-- Date / Time --}}
    <td class="px-6 py-4 align-top">
        {{ \Carbon\Carbon::parse($announcement->date . ' ' . $announcement->time)->format('F d, Y | g:i A') }}
    </td>

    {{-- Posted Date / Time --}}
    <td class="px-6 py-4 align-top">
        {{ \Carbon\Carbon::parse($announcement->created_at)->format('F d, Y | g:i A') }}
    </td>
                    <td class="px-6 py-4 align-top">
                        <div class="flex justify-center">
                            <button
                                @click="announcement = {{ $announcement->toJson() }}; open = true"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white w-9 h-9 flex items-center justify-center rounded-md shadow transition"
                                title="View Announcement"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                    class="w-5 h-5" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor" 
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                                           @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $announcements->withQueryString()->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center mt-20 text-lg">No announcements available.</p>
            @endif
            <div 
    x-show="open" 
    x-transition.opacity
    x-cloak
    x-data="{ imageModal: false, imageSrc: '' }"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
>
    <div class="bg-white w-full max-w-2xl p-6 sm:p-8 rounded-2xl shadow-xl relative overflow-hidden">

        <!-- ❌ Close Button -->
        <button 
            @click="open = false" 
            class="absolute top-4 right-5 text-gray-500 hover:text-red-500 text-2xl font-bold"
            title="Close"
        >&times;</button>

        <!-- 📰 Title -->
        <h3 class="text-2xl sm:text-2xl font-bold text-blue-800 mb-6 border-b pb-4" 
            x-text="announcement?.title">
        </h3>

        <!-- 📅 Date, Time, Posted Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4 text-xs text-gray-700">

            <!-- 📅 Date & Time -->
         <!-- 📅 Date & Time -->
<div class="p-3 bg-gray-50 rounded shadow-sm">
    <p class="text-gray-500 font-semibold uppercase text-[10px]">Date & Time</p>
    <p class="mt-1 text-gray-900 text-xs font-medium"
       x-text="(() => {
            if (!announcement?.date) return '—';
            const dateTimeString = announcement.time 
                ? `${announcement.date}T${announcement.time}` 
                : `${announcement.date}T00:00`;
            const dateObj = new Date(dateTimeString);
            const options = { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
            return dateObj.toLocaleString('en-US', options);
       })()">
    </p>
</div>

<!-- 🧑‍💼 Posted By / On -->
<div class="p-3 bg-gray-50 rounded shadow-sm">
    <p class="text-gray-500 font-semibold uppercase text-[10px]">Posted On</p>
    <p class="mt-1 text-gray-900 text-xs font-medium"
       x-text="(() => {
            if (!announcement?.created_at) return '—';
            const postedDate = new Date(announcement.created_at);
            const options = { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
            return postedDate.toLocaleString('en-US', options);
       })()">
    </p>
</div>

        </div>

        <!-- 📝 Details -->
        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm text-gray-800 text-sm mb-4">
            <p class="text-gray-600 font-semibold uppercase text-xs mb-2">Details</p>
            <div class="max-h-36 overflow-y-auto whitespace-pre-line px-3 py-2 bg-white rounded-md border text-gray-700 leading-relaxed pr-3">
                <p x-text="announcement?.details"></p>
                <p x-text="announcement?.content" class="mt-2"></p>
            </div>
        </div>

        <!-- 🖼️ Images -->
    <template x-if="announcement?.images?.length">
    <div class="mb-4">
        <p class="text-gray-500 font-semibold uppercase text-xs mb-3">Images</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
            <template x-for="img in announcement.images" :key="img.id">
                <div class="relative group overflow-hidden rounded-lg cursor-pointer flex items-center justify-center h-56"
                     @click="imageSrc = '/storage/' + img.path; imageModal = true">
                    <img :src="'/storage/' + img.path" alt="Announcement Image"
                         class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                </div>
            </template>
        </div>
    </div>
</template>



        <!-- 🔍 Fullscreen Image Modal -->
        <div x-show="imageModal" x-transition.opacity x-cloak
             @click.self="imageModal = false"
             class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50">
            
            <!-- Close Button -->
            <button @click="imageModal = false"
                    class="absolute top-6 right-6 text-white text-4xl font-bold hover:text-red-400 transition z-50">
                &times;
            </button>

            <!-- Modal Image -->
            <img :src="imageSrc" class="w-full h-full object-contain">
        </div>

    </div>
</div>



        </section>
    </main>
</div>
@endsection
