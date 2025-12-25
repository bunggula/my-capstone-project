

<?php $__env->startSection('title', 'Municipality Residents'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="eventModal()" class="flex h-screen overflow-hidden bg-gray-100">

    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.abc-header', ['title' => 'Municipality Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                    <h2 class="text-2xl font-bold mb-6">Barangay Events</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                    <a href="<?php echo e(route('abc.events.index', ['status' => 'approved', 'barangay' => request('barangay')])); ?>"
   class="block p-4 rounded shadow border-l-4 <?php echo e(request('status') === 'approved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold text-sm">
                Barangay Events
                <span class="text-xs text-gray-600">
                    (<?php echo e(request('barangay') ? $barangays->firstWhere('id', request('barangay'))->name : 'All Barangays'); ?>)
                </span>
            </h4>
            <p class="text-2xl font-bold"><?php echo e($approvedCount); ?></p>
        </div>
        <svg class="h-8 w-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
</a>
</div> 






<form method="GET" action="<?php echo e(route('abc.events.index')); ?>" 
      class="mb-6 flex flex-col md:flex-row gap-3 items-center flex-wrap" 
      id="filterForm">



<div class="w-full md:w-1/5 relative">
    <input 
        type="text" 
        name="search" 
        id="searchInput"
        value="<?php echo e(request('search')); ?>" 
        placeholder="Search title..." 
        class="border border-gray-300 rounded px-3 py-2 w-full text-sm focus:ring focus:border-blue-500"
         oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 100000)">
    
</div>


<div class="w-full md:w-1/6">
    <select 
        name="barangay" 
        id="barangaySelect"
        class="border border-gray-300 rounded px-3 py-2 w-full text-sm focus:ring focus:border-blue-500"
        onchange="toggleClearAllBtn(); document.getElementById('filterForm').submit();"
    >
        <option value="">Select Barangays</option>
        <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brgy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option 
                value="<?php echo e($brgy->id); ?>" 
                <?php echo e(request('barangay') == $brgy->id ? 'selected' : ''); ?>

            >
                <?php echo e($brgy->name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>


<div class="w-full md:w-1/6">
    <select 
        name="month" 
        id="monthSelect"
        class="border border-gray-300 rounded px-3 py-2 w-full text-sm focus:ring focus:border-blue-500"
        onchange="toggleClearAllBtn(); document.getElementById('filterForm').submit();"
    >
        <option value="">Select Months</option>
        <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>


<div class="w-full md:w-1/6">
    <select 
        name="year" 
        id="yearSelect"
        class="border border-gray-300 rounded px-3 py-2 w-full text-sm focus:ring focus:border-blue-500"
        onchange="toggleClearAllBtn(); document.getElementById('filterForm').submit();"
    >
        <option value="">Select Years</option>
        <?php $__currentLoopData = range(date('Y'), date('Y') - 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
                <?php echo e($y); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>


<div>
    <button 
        type="button" 
        id="clearAllBtn" 
        onclick="clearAllFilters()" 
        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm hidden"
    >
        Clear
    </button>
</div>

</form>







<script>
    function toggleClearAllBtn() {
    const hasFilters = document.getElementById('searchInput').value.trim() ||
                       document.getElementById('barangaySelect').value ||
                       document.getElementById('monthSelect').value ||
                       document.getElementById('yearSelect').value;
    document.getElementById('clearAllBtn').classList.toggle('hidden', !hasFilters);
}

function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('barangaySelect').value = '';
    document.getElementById('monthSelect').value = '';
    document.getElementById('yearSelect').value = '';
    document.getElementById('filterForm').submit();
}
    document.addEventListener('DOMContentLoaded', function() {
        toggleClearAllBtn();
    });


</script>



                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left">#</th>
                                    <th class="py-3 px-6 text-left">Event</th>
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-left">Barangay</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $approvedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200">
                                    <td class="px-4 py-2"><?php echo e($loop->iteration); ?></td>
                                    <td class="py-3 px-6"><?php echo e($event->title); ?></td>
                                    <td class="py-3 px-6">
    <?php echo e(\Carbon\Carbon::parse($event->date . ' ' . $event->time)->format('F d, Y g:i A')); ?>

</td>

                                    <td class="py-3 px-6"><?php echo e($event->barangay->name ?? 'N/A'); ?></td>
                                    <td class="py-3 px-6 text-center">
                                    <button 
    @click="openModal($el.dataset)" 
    data-id="<?php echo e($event->id); ?>"
    data-title="<?php echo e(e($event->title)); ?>"
    data-date="<?php echo e(\Carbon\Carbon::parse($event->date)->format('F d, Y')); ?>"
    data-time="<?php echo e(\Carbon\Carbon::parse($event->time)->format('g:i A')); ?>"
    data-venue="<?php echo e(e($event->venue)); ?>"
    data-details="<?php echo e(e($event->details)); ?>"
    data-barangay="<?php echo e($event->barangay->name ?? 'N/A'); ?>"
    data-posted_by="<?php echo e($event->posted_by); ?>" 
    data-created_at="<?php echo e(\Carbon\Carbon::parse($event->created_at)->format('F d, Y h:i A')); ?>"
    data-updated_at="<?php echo e(\Carbon\Carbon::parse($event->updated_at)->format('F d, Y h:i A')); ?>"
    data-status="<?php echo e(ucfirst($event->status)); ?>"
    data-images='<?php echo json_encode($event->images->map(fn($img) => asset("storage/" . $img->path)), 15, 512) ?>'
    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition"
    title="View Event"
>
    <!-- Eye SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">
                                        No approved events found.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="mt-4">
    <?php echo e($approvedEvents->links()); ?>

</div>

                    </div>
                </div>
            </div>
        </div>
<!-- Event Modal -->
<!-- Event Modal -->
<div x-show="open" x-transition x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg relative">
        <!-- Close Button -->
        <button @click="open = false"
                class="absolute top-3 right-3 text-gray-600 hover:text-red-500 text-2xl">&times;</button>

  
        <!-- Title -->
        <h3 class="text-2xl sm:text-3xl font-semibold text-blue-900 mb-4 border-b pb-3" x-text="event.title"></h3>

        <!-- 📅 Date/Time + 🧑‍💼 Posted By/On -->
        <div class="grid grid-cols-2 gap-2 text-xs text-gray-700 mb-3">
            <!-- 📅 Date & Time -->
            <div class="p-2 bg-gray-50 rounded shadow-sm">
                <p class="text-gray-500 font-semibold uppercase text-[9px]">Date & Time</p>
                <p class="mt-0.5 text-gray-900 text-xs font-medium"
                   x-text="event.date && event.time ? `${event.date} • ${event.time}` : '—'"></p>
            </div>

            <!-- 🧑‍💼 Posted By / On -->
            <div class="p-2 bg-gray-50 rounded shadow-sm">
                <p class="text-gray-500 font-semibold uppercase text-[9px]">Approved On</p>
                <p class="mt-0.5 text-gray-900 text-xs font-medium"
                   x-text="` ${event.updated_at}`"></p>
            </div>
        </div>

        <!-- 🏘 Barangay + 📍 Venue -->
        <div class="grid grid-cols-2 gap-2 text-xs text-gray-700 mb-4">
            <!-- Barangay -->
            <div class="p-2 bg-gray-50 rounded shadow-sm">
                <p class="text-gray-500 font-semibold uppercase text-[9px]">Barangay</p>
                <p class="mt-0.5 text-gray-900 text-xs font-medium" x-text="event.barangay"></p>
            </div>

            <!-- Venue -->
            <div class="p-2 bg-gray-50 rounded shadow-sm">
                <p class="text-gray-500 font-semibold uppercase text-[9px]">Venue</p>
                <p class="mt-0.5 text-gray-900 text-xs font-medium truncate" x-text="event.venue"></p>
            </div>
        </div>

       <!-- 📝 Details -->
<div class="p-2 bg-gray-50 border border-gray-200 rounded-md shadow-sm text-gray-800 text-xs mb-3">
    <p class="text-gray-500 font-semibold uppercase text-[10px] mb-1">Details</p>
    <div class="h-32 overflow-y-auto whitespace-pre-line pr-2 p-2 bg-white rounded text-xs leading-relaxed">
        <p x-text="event.details || 'No details available.'"></p>
    </div>
</div>

        <!-- 🖼 Images -->
       <!-- 🖼 Images -->
<template x-if="event.images && event.images.length">
    <div>
        <p class="text-gray-500 font-semibold uppercase text-xs mb-3">Event Images</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
            <template x-for="(img, idx) in event.images" :key="idx">
                <div 
                    @click="openImage(img)" 
                    class="cursor-pointer relative group overflow-hidden rounded-lg"
                    style="width:100%; height:12rem;">
                    <img :src="img" 
                         class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110">
                </div>
            </template>
        </div>
    </div>
</template>

    </div>
</div>

<!-- Fullscreen Image Modal -->
<div x-show="imageModalOpen" x-transition.opacity x-cloak
     @click.self="imageModalOpen = false"
     class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50 p-4">

    <!-- Close Button -->
    <button @click="imageModalOpen = false"
            class="absolute top-6 right-6 text-white text-4xl font-bold hover:text-red-400 z-50">
        &times;
    </button>

    <!-- Modal Image -->
    <img :src="imageModalSrc"
         class="max-w-full max-h-full object-contain rounded-lg">
</div>




<script>
function eventModal() {
    return {
        open: false,                 // para sa event modal
        imageModalOpen: false,       // para sa fullscreen image
        imageModalSrc: '',           // source ng fullscreen image
        event: {
            id: '',
            title: '',
            date: '',
            time: '',
            venue: '',
            details: '',
            barangay: '',
            posted_by: '',           // dito natin idadagdag
            created_at: '',
            updated_at: '',
            status: '',
            rejection_reason: '',
            images: []
        },
        // Open event modal at i-set ang data
        openModal(data) {
            this.event = {
                id: data.id,
                title: data.title,
                date: data.date,
                time: data.time,
                venue: data.venue,
                details: data.details,
                barangay: data.barangay,
                posted_by: data.posted_by || '',   // bagong field
                created_at: data.created_at,
                updated_at: data.updated_at,
                status: data.status,
                rejection_reason: data.rejection_reason || '',
                images: JSON.parse(data.images || "[]"),
            };
            this.open = true;
        },
        // Open fullscreen image modal
        openImage(src) {
            this.imageModalSrc = src;
            this.imageModalOpen = true;
        },
        // Optional: close modals
        closeModal() { this.open = false; },
        closeImageModal() { this.imageModalOpen = false; this.imageModalSrc = ''; },
        // Format time to 12-hour
        formatTime(timeStr) {
            if (!timeStr) return '';
            const [hour, minute] = timeStr.split(':');
            let h = parseInt(hour);
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            return `${h}:${minute} ${ampm}`;
        },
        // Format date
        formatDate(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
        }
    }
}

</script>

<style>
    [x-cloak] { display: none !important; }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/events/index.blade.php ENDPATH**/ ?>