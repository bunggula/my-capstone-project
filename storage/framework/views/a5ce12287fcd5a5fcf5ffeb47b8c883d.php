

<?php $__env->startSection('title', 'Events and Announcements'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden font-sans"
x-data="{ 
         viewModal: false, 
         editModal: false, 
         addModal: false, 
         selectedEvent: null,
         fullscreenImage: null,
         formatDate(date) {
             if (!date) return '-';
             return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
         },
         formatTime(time) {
             if (!time) return '-';
             return new Date('1970-01-01T' + time).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
         }
     }">



    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-col flex-1 max-h-screen">
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Events and Announcements'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">

            
            <?php if(session('success')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: '<?php echo e(session('success')); ?>',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                </script>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: '<?php echo e(session('error')); ?>',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                </script>
            <?php endif; ?>

            
<h1 class="text-2xl font-bold text-blue-900 mb-6">
    Events  - Barangay <?php echo e(Auth::user()->barangay->name ?? 'N/A'); ?>

</h1>


<div class="space-y-4 mb-6">

    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 w-full">
        
       
<a href="<?php echo e(route('secretary.events.index', array_merge(request()->except('status'), ['status' => 'pending']))); ?>"
   class="p-4 rounded shadow border-l-4 text-sm <?php echo e($status === 'pending' ? 'bg-yellow-200 border-yellow-600 text-yellow-900' : 'bg-yellow-100 border-yellow-500 text-yellow-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Pending</h4>
            <p class="text-xl font-bold"><?php echo e($counts['pending'] ?? 0); ?></p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
        </svg>
    </div>
</a>


<a href="<?php echo e(route('secretary.events.index', array_merge(request()->except('status'), ['status' => 'approved']))); ?>"
   class="p-4 rounded shadow border-l-4 text-sm <?php echo e($status === 'approved' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Approved</h4>
            <p class="text-xl font-bold"><?php echo e($counts['approved'] ?? 0); ?></p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
</a>


<a href="<?php echo e(route('secretary.events.index', array_merge(request()->except('status'), ['status' => 'rejected']))); ?>"
   class="p-4 rounded shadow border-l-4 text-sm <?php echo e($status === 'rejected' ? 'bg-red-200 border-red-600 text-red-900' : 'bg-red-100 border-red-500 text-red-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">Rejected</h4>
            <p class="text-xl font-bold"><?php echo e($counts['rejected'] ?? 0); ?></p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </div>
</a>


<a href="<?php echo e(route('secretary.events.index', array_merge(request()->except('status'), ['status' => 'all']))); ?>"
   class="p-4 rounded shadow border-l-4 text-sm <?php echo e($status === 'all' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700'); ?>">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">All</h4>
            <p class="text-xl font-bold"><?php echo e($counts['all'] ?? 0); ?></p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 20h18M9 10h6M9 14h6"/>
        </svg>
    </div>
</a>


    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">



<form method="GET" action="<?php echo e(route('secretary.events.index')); ?>" class="w-full md:w-auto" id="eventSearchForm">
    <div class="flex flex-col md:flex-row gap-2 items-center">
        
        
        <div class="relative w-full sm:w-72">
            <input 
                type="text" 
                name="search" 
                id="searchInput"
                value="<?php echo e(request('search')); ?>" 
                placeholder="Search events..." 
                class="border rounded-lg px-5 py-2.5 w-full pr-10 text-sm"
                oninput="toggleClearAllButton(); document.getElementById('eventSearchForm').submit();"
            />
        </div>

        
        <select 
            name="month" 
            id="monthSelect"
            class="border rounded-lg px-3 py-2 text-sm"
            onchange="toggleClearAllButton(); document.getElementById('eventSearchForm').submit();"
        >
            <option value="">Select Months</option>
            <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                    <?php echo e(DateTime::createFromFormat('!m', $m)->format('F')); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <select 
            name="year" 
            id="yearSelect"
            class="border rounded-lg px-3 py-2 text-sm"
            onchange="toggleClearAllButton(); document.getElementById('eventSearchForm').submit();"
        >
            <option value="">Select Years</option>
            <?php $__currentLoopData = range(date('Y'), date('Y')-10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
                    <?php echo e($y); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        
        <div>
            <button 
                type="button" 
                id="clearAllBtn" 
                onclick="clearAllFilters()" 
                class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm hidden"
            >
                Clear All
            </button>
        </div>
    </div>
</form>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        toggleClearAllButton();
    });

    function toggleClearAllButton() {
        const search = document.getElementById('searchInput').value;
        const month = document.getElementById('monthSelect').value;
        const year = document.getElementById('yearSelect').value;
        const btn = document.getElementById('clearAllBtn');

        btn.style.display = (search || month || year) ? 'inline-block' : 'none';
    }

    function clearAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('monthSelect').value = '';
        document.getElementById('yearSelect').value = '';
        document.getElementById('eventSearchForm').submit();
    }
</script>



    
    <div class="w-full md:w-auto">
        <button 
            @click="addModal = true"
            class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2.5 rounded-md shadow-md text-sm font-semibold w-full md:w-auto">
            ➕ Add Event
        </button>
    </div>
</div>



                
                <div class="bg-white shadow rounded-lg p-4">
    <div class="overflow-x-auto">
        <table class="w-full table-auto border text-sm">
            <thead class="bg-blue-600 text-white text-left">
                 <tr>
                     <th class="px-4 py-3">#</th>
        <th class="px-4 py-3">Event</th>
        <th class="px-4 py-3">Date</th>
        <th class="px-4 py-3">Time</th>
        <th class="px-4 py-3">Status</th>
        <th class="px-4 py-3 text-center">Actions</th>
    </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800 text-sm">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200">
                       <td class="px-4 py-3 text-left align-middle font-medium">
            <?php echo e($loop->iteration); ?>

        </td>
                        
                        <td class="px-4 py-3 font-medium text-left align-middle">
                            <?php echo e($event->title); ?>

                        </td>
  </td>

    
    <td class="px-4 py-3 text-left align-middle whitespace-nowrap">
        <?php echo e(\Carbon\Carbon::parse($event->date)->format('F j, Y')); ?>

    </td>

    
    <td class="px-4 py-3 text-left align-middle whitespace-nowrap">
        <span class="text-xs text-gray-500">
            <?php echo e(\Carbon\Carbon::parse($event->time)->format('h:i A')); ?>

        </span>
    </td>

                      

                        
                        <td class="px-4 py-3 text-center align-middle">
                        <span class="px-2 py-1 rounded text-xs font-medium
    <?php if($event->status === 'approved'): ?> bg-green-100 text-green-700
    <?php elseif($event->status === 'pending'): ?> bg-yellow-100 text-yellow-700
    <?php elseif($event->status === 'rejected'): ?> bg-red-100 text-red-700
    <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
    <?php echo e(ucfirst($event->status)); ?>

</span>

                        </td>

                               <td class="px-4 py-3 text-center">
    <div class="flex justify-center items-center space-x-1">
        
        <button 
            @click.prevent="selectedEvent = JSON.parse(atob('<?php echo e(base64_encode(json_encode($event->load('images')))); ?>')); viewModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white w-9 h-9 flex items-center justify-center rounded-md shadow transition"
            title="View">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                         -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
        </button>

        
        <?php if($event->status !== 'approved'): ?>
        <button 
            @click.prevent="selectedEvent = JSON.parse(atob('<?php echo e(base64_encode(json_encode($event->load('images')))); ?>')); editModal = true"
            class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 w-9 h-9 flex items-center justify-center rounded-md shadow transition"
            title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
        </button>
        <?php endif; ?>

     
<?php if($event->status === 'rejected'): ?>
<form 
    action="<?php echo e(route('secretary.events.resubmit', $event->id)); ?>" 
    method="POST" 
    class="inline resubmit-form"   
    data-title="<?php echo e($event->title); ?>" 
>
    <?php echo csrf_field(); ?>
    <?php echo method_field('PATCH'); ?>
    <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white w-9 h-9 flex items-center justify-center rounded-md shadow transition"
        title="Resubmit">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 4v5h.582a9 9 0 0115.356-2.54M20 20v-5h-.581a9 9 0 01-15.356 2.54" />
        </svg>
    </button>
</form>
<?php endif; ?>


      
<form action="<?php echo e(route('secretary.events.destroy', $event->id)); ?>" method="POST" class="inline delete-form" data-title="<?php echo e($event->title); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="submit"
            class="bg-red-600 hover:bg-red-700 text-white w-9 h-9 flex items-center justify-center rounded-md shadow transition"
            title="Delete">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 011 1v0a1 1 0 01-1 1H7a1 1 0 01-1-1v0a1 1 0 011-1h10z"/>
        </svg>
    </button>
</form>
    </div>
</td>




                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="mt-4">
    <?php echo e($events->withQueryString()->links()); ?>

</div>

                </div>
            </form>
        </main>
    </div>

    
<div 
    x-show="viewModal" 
    x-transition.opacity
    x-cloak
    @keydown.escape.window="viewModal = false"
    class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center"
>
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl relative border border-gray-200 p-6 sm:p-8 overflow-y-auto max-h-[90vh] flex flex-col">

        <!-- ❌ Close Button -->
        <button 
            @click="viewModal = false" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl font-bold"
            title="Close"
        >&times;</button>

        <!-- 📄 Modal Title -->
        <h2 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-6 border-b pb-4" x-text="selectedEvent?.title"></h2>

        <!-- Event Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 mb-4 text-xs text-gray-700">

            <!-- 📅 Date & Time -->
            <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm md:col-span-2">
                <p class="text-gray-500 font-semibold uppercase text-xs">Date & Time</p>
                <p class="mt-1 text-gray-900 text-sm font-medium" 
                   x-text="selectedEvent?.date && selectedEvent?.time ? `${formatDate(selectedEvent.date)} • ${formatTime(selectedEvent.time)}` : '—'"></p>
            </div>

            <!-- 🧑‍💼 Posted / Approved / Rejected -->
            <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm md:col-span-2">
                <p class="text-gray-500 font-semibold uppercase text-xs"
                   x-text="selectedEvent?.status === 'approved' 
                       ? 'Approved On' 
                       : (selectedEvent?.status === 'rejected' ? 'Rejected On /' : 'Posted On')"></p>
              <p class="mt-1 text-gray-900 text-sm font-medium"
   x-text="`${formatDateTime(selectedEvent?.status === 'pending' ? selectedEvent?.created_at : selectedEvent?.updated_at)}`">
</p>

            </div>

            <!-- 🏛️ Venue -->
            <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm md:col-span-2">
                <p class="text-gray-500 font-semibold uppercase text-xs">Venue</p>
                <p class="mt-0.5 text-gray-900 text-xs font-medium truncate" x-text="selectedEvent?.venue"></p>
            </div>

            <!-- 📊 Status -->
            <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                <p class="text-gray-500 font-semibold uppercase text-xs">Status</p>
                <span 
                    class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-medium"
                    :class="{
                        'bg-yellow-100 text-yellow-700': selectedEvent?.status === 'pending',
                        'bg-green-100 text-green-700': selectedEvent?.status === 'approved',
                        'bg-red-100 text-red-700': selectedEvent?.status === 'rejected',
                    }"
                    x-text="selectedEvent?.status?.replaceAll('_', ' ').toUpperCase()"
                ></span>
            </div>
        </div>

        <!-- 📝 Details -->
     <!-- 📝 Details -->
<div class="p-2 bg-gray-50 border border-gray-200 rounded-md shadow-sm text-gray-800 text-xs mb-3">
    <p class="text-gray-500 font-semibold uppercase text-[10px] mb-1">Details</p>
    <div class="h-32 overflow-y-auto whitespace-pre-line pr-2 p-2 bg-white rounded text-xs leading-relaxed">
        <p x-text="selectedEvent?.details || 'No details available.'"></p>
    </div>
</div>
    


        <!-- ❌ Rejection Reason -->
        <template x-if="selectedEvent?.status === 'rejected'">
            <div class="p-2 bg-red-50 border border-red-200 rounded-md shadow-sm text-sm">
                <p class="text-red-700 font-semibold text-xs">Rejection Reason</p>
                <p class="text-red-800 mt-1" x-text="selectedEvent?.rejection_reason || 'N/A'"></p>
            </div>
        </template>

        <!-- 📸 Images -->
<template x-if="selectedEvent?.images?.length">
  <div class="mt-4">
      <p class="text-gray-500 font-semibold uppercase text-xs mb-2">Event Images</p>
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
          <template x-for="img in selectedEvent.images" :key="img.id">
              <div class="relative group overflow-hidden rounded-lg shadow-sm flex items-center justify-center cursor-pointer h-40 sm:h-48"
                   @click="fullscreenImage = '/storage/' + img.path">
                  <img :src="'/storage/' + img.path"
                       class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-105">
                  <!-- Overlay removed -->
              </div>
          </template>
      </div>
  </div>
</template>






    </div>
</div>

<!-- 🖼️ Fullscreen Image Modal -->
<div x-show="fullscreenImage" x-transition.opacity x-cloak
     @click.self="fullscreenImage = false"
     class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50 p-4">
    <button @click="fullscreenImage = false"
            class="absolute top-6 right-6 text-white text-4xl font-bold hover:text-red-400 z-50">&times;</button>
    <img :src="fullscreenImage"
         class="max-w-full max-h-full object-contain rounded-lg">
</div>

<!-- Add Event Modal -->
<div
    x-show="addModal"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    style="display: none"
>
    <div
        @click.away="addModal = false"
        class="bg-white rounded-lg shadow-lg w-full max-w-xl max-h-[90vh] overflow-y-auto p-4"
    >
        <?php echo $__env->make('secretary.events.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
    </div>
</div>





<div x-show="editModal" x-transition.opacity x-cloak
     class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl relative flex flex-col"
         style="max-height: 90vh;">

        <!-- Close Button -->
        <button @click="editModal = false"
                class="absolute top-3 right-4 text-gray-600 hover:text-red-500 text-2xl font-bold">&times;</button>

        <!-- Title -->
        <h2 class="text-xl font-bold p-4 border-b sticky top-0 bg-white z-10">
            ✏️ Edit Event
        </h2>

        <!-- Scrollable Content -->
        <div class="p-6 overflow-y-auto flex-1">
            <form id="editForm" :action="`/secretary/events/${selectedEvent.id}`" method="POST" enctype="multipart/form-data"
                  class="space-y-4" x-data="editEvent()" x-init="init()">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Event Title -->
                <div>
                    <label class="block text-sm font-medium mb-1">Title</label>
                    <input type="text" name="title" x-model="selectedEvent.title"
                           class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <!-- Date & Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Date</label>
                        <input type="date" name="date" x-model="selectedEvent.date"
                               class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Time</label>
                        <input type="time" name="time" x-model="selectedEvent.time"
                               class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    </div>
                </div>

                <!-- Venue -->
                <div>
                    <label class="block text-sm font-medium mb-1">Venue</label>
                    <input type="text" name="venue" x-model="selectedEvent.venue"
                           class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>

                <!-- Details -->
                <div>
                    <label class="block text-sm font-medium mb-1">Details</label>
                    <textarea name="details" x-model="selectedEvent.details" rows="4" maxlength="500"
                              class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>
                    <p class="text-xs text-gray-500 mt-1" x-text="selectedEvent.details ? selectedEvent.details.length + '/500' : '0/500'"></p>
                </div>

                <!-- Event Images -->
                <div>
                    <label class="block text-sm font-medium mb-1">Event Posters</label>

                    <!-- Existing Images -->
                    <template x-if="selectedEvent.images?.length">
                        <div class="mb-3">
                            <p class="text-gray-600 font-semibold mb-2">Current Images</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <template x-for="img in selectedEvent.images" :key="img.id">
                                    <div class="relative rounded-lg overflow-hidden shadow-md">
                                        <img :src="`/storage/${img.path}`" class="w-full h-24 object-contain bg-gray-100 rounded-md">
                                        <div class="absolute top-2 right-2 bg-white bg-opacity-90 rounded-md px-2 py-1 shadow">
                                            <input type="checkbox" :value="img.id" name="delete_images[]">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Upload New Images -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Upload New Images</label>
                        <input type="file" id="eventImageInput" name="images[]" multiple accept="image/*"
                               @change="previewFiles"
                               class="border rounded-lg px-3 py-2 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <small class="text-gray-500">Max 2MB per image, up to 3 images</small>

                        <!-- Preview -->
                        <div id="eventPreviewContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-3">
                            <template x-for="(img, index) in previewImages" :key="index">
                                <div class="relative rounded-lg overflow-hidden shadow-md">
                                    <img :src="img" class="w-full h-24 object-contain bg-gray-100">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <!-- Buttons -->
        <div class="p-4 border-t flex justify-end gap-4 bg-white sticky bottom-0 z-10">
            <button type="button" @click="editModal = false"
                    class="px-5 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg hover:bg-gray-500 transition-colors">
                Cancel
            </button>
            <button type="submit" form="editForm"
                    class="px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                Save Changes
            </button>
        </div>
    </div>
</div>




<script>
    function toggleAll(source) {
        document.querySelectorAll('input[name="selected_events[]"]').forEach(cb => cb.checked = source.checked);
    }

    document.addEventListener('click', function(e) {
    if(e.target.closest('.delete-form button')) {
        e.preventDefault();
        const form = e.target.closest('form');
        const eventTitle = form.dataset.title || 'this event';
        
        Swal.fire({
            title: `Delete "${eventTitle}"?`,
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    }
});

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.resubmit-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // stop default submission

            const eventTitle = this.dataset.title; // get data-title
            Swal.fire({
                title: `Resubmit "${eventTitle}"?`,
                text: 'This will send the event for approval again.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes, resubmit',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // submit the form if confirmed
                }
            });
        });
    });
});


function editEvent() {
    return {
        selectedFiles: [],
        init() {
            const imageInput = document.getElementById('eventImageInput');
            const previewContainer = document.getElementById('eventPreviewContainer');
  // siguraduhin isa lang yung listener
  imageInput.onchange = (e) => {
                let files = Array.from(e.target.files);

                // Limit to 3
                if (files.length > 3) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Limit Exceeded',
                        text: 'You can only upload up to 3 images.',
                    });
                    files = files.slice(0, 3);
                }

                // Reset palagi
                this.selectedFiles = files;

                // Render fresh previews
                this.renderPreviews(previewContainer, imageInput);
            };
        },
        renderPreviews(container, input) {
            container.innerHTML = ''; // clear previews

            this.selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded shadow-md">
                        <button type="button" data-index="${index}"
                            class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full shadow hover:bg-red-700 transition">
                            &times;
                        </button>
                    `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });

            // Update file input
            const dataTransfer = new DataTransfer();
            this.selectedFiles.forEach(f => dataTransfer.items.add(f));
            input.files = dataTransfer.files;
        },
        removeFile(index, container, input) {
            Swal.fire({
                title: 'Remove this image?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.selectedFiles.splice(index, 1);
                    this.renderPreviews(container, input);
                }
            });
        }
    }
}

// Handle remove click
document.addEventListener('click', function(e) {
    if(e.target.closest('#eventPreviewContainer button')) {
        const btn = e.target.closest('button');
        const index = parseInt(btn.getAttribute('data-index'));
        const input = document.getElementById('eventImageInput');
        const container = document.getElementById('eventPreviewContainer');
        const alpineComponent = Alpine.$data(document.querySelector('form[x-data="editEvent()"]'));
        alpineComponent.removeFile(index, container, input);
    }
});
function viewEvent() {
    return {
        selectedEvent: <?php echo json_encode($event ?? [], 15, 512) ?>,
        formatDate(dateStr) {
            if(!dateStr) return '';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString(undefined, options);
        },
        formatTime(timeStr) {
            if(!timeStr) return '';
            const [hour, minute] = timeStr.split(':');
            let h = parseInt(hour);
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            return `${h}:${minute} ${ampm}`;
        }
    }
}
function formatDateTime(datetime) {
    if (!datetime) return '';
    const date = new Date(datetime);
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    };
    return date.toLocaleString('en-US', options);
}

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/events/index.blade.php ENDPATH**/ ?>