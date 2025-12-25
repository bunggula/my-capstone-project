<div x-data="{ imageModal: false, imageSrc: '' }" class="space-y-6 relative">

    <!-- Title -->
    <h3 class="text-2xl sm:text-2xl font-bold text-blue-800 mb-6 border-b pb-4">
        {{ $announcement->title }}
    </h3>

    <!-- 📅 Date & Time + 🧑‍💼 Posted By / On -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-700">

        <!-- 📅 Date & Time -->
        <div class="p-3 bg-gray-50 rounded shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-[9px]">Date & Time</p>
            <p class="mt-0.5 text-gray-900 text-xs font-medium">
                @if ($announcement->date && $announcement->time)
                    {{ \Carbon\Carbon::parse($announcement->date . ' ' . $announcement->time)->format('F j, Y , g:i A') }}
                @else
                    —
                @endif
            </p>
        </div>

        <!-- 🧑‍💼 Posted By / On -->
        <div class="p-3 bg-gray-50 rounded shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-[9px]">Posted On</p>
            <p class="mt-0.5 text-gray-900 text-xs font-medium">
             
                {{ $announcement->created_at->format('M j, Y , g:i A') }}
            </p>
        </div>
    </div>

    @php
    $roles = [
        'brgy_captain' => 'Barangay Captain',
        'secretary' => 'Secretary',
    ];
@endphp

<!-- Target Audience -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
    <div class="p-2 bg-gray-50 rounded shadow-sm">
        <p class="text-gray-500 font-semibold uppercase text-[9px]">Recipients</p>
        <p class="mt-0.5 text-gray-900 text-xs font-medium">
            @if ($announcement->target === 'all')
                All Barangays
                @if ($announcement->target_role)
                    ({{ $roles[$announcement->target_role] ?? $announcement->target_role }})
                @endif
            @else
                {{ $announcement->barangay->name ?? '—' }}
                @if ($announcement->target_role)
                    ({{ $roles[$announcement->target_role] ?? $announcement->target_role }})
                @endif
            @endif
        </p>
    </div>
</div>


<!-- Details Section -->
<div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm mb-4">
    <p class="text-gray-600 font-semibold uppercase text-xs mb-1">Details</p>
    <div class="h-36 overflow-y-auto whitespace-pre-line px-3 py-2 bg-white rounded-md border text-gray-700 text-sm leading-relaxed">
        {{ $announcement->details }}
    </div>
</div>
<!-- Images Grid -->
@if ($announcement->images->count())
    <div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
            @foreach ($announcement->images as $img)
                <div class="relative group overflow-hidden rounded-lg cursor-pointer"
                     style="width:100%; height:12rem;"
                     @click="imageSrc = '{{ asset('storage/' . $img->path) }}'; imageModal = true">

                    <img src="{{ asset('storage/' . $img->path) }}"
                         alt="Announcement Image"
                         class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                </div>
            @endforeach
        </div>
    </div>
@endif


    <!-- Fullscreen Image Modal -->
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
