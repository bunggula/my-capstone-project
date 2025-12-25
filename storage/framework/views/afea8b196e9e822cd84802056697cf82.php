

<?php $__env->startSection('title', 'Captain Announcements'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{
        open: false,
        announcement: null,
        imageModal: false,
        imageSrc: '',
        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        },
        formatTime(time) {
            if (!time) return '-';
            return new Date('1970-01-01T' + time).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        }
    }"
    class="flex w-screen h-screen font-sans overflow-hidden">

    
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main class="flex-1 flex flex-col overflow-hidden">

        
        <?php echo $__env->make('partials.captain-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <section class="flex-1 overflow-y-auto p-6 bg-gray-50">

            
            <div class="flex flex-col gap-2 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">📢 Captain Announcements</h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">


<div class="p-4 rounded shadow border-l-4 text-sm bg-blue-100 border-blue-500 text-blue-700">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="font-semibold">All Announcements</h4>
            <p class="text-xl font-bold text-blue-900"><?php echo e($allAnnouncementsCount); ?></p>
        </div>
        <svg class="h-6 w-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405M19 13l-7-7-7 7" />
        </svg>
    </div>
</div>

</div>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">

    
    <form method="GET" action="<?php echo e(route('captain.announcements.index')); ?>" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

        
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                name="search" 
                value="<?php echo e(request('search')); ?>" 
                placeholder="Search announcements..." 
                class="w-full px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200 text-sm"
            >
        </div>

        
        <div class="w-full sm:w-48">
            <select 
                name="posted_by" 
                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring focus:ring-blue-200"
                onchange="this.form.submit()"
            >
                <option value="">Filter by Posted By</option>
                <option value="abc_president" <?php echo e(request('posted_by') === 'abc_president' ? 'selected' : ''); ?>>ABC President</option>
                <option value="sk_president" <?php echo e(request('posted_by') === 'sk_president' ? 'selected' : ''); ?>>SK President</option>
            </select>
        </div>

        
        <?php if(request('search') || request('posted_by')): ?>
            <a href="<?php echo e(route('captain.announcements.index')); ?>"
               class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow text-sm transition">
                Clear
            </a>
        <?php endif; ?>
    </form>
</div>


<?php if(request('search') || request('posted_by')): ?>
    <p class="text-sm text-gray-500 text-right mb-4">
        Showing results
        <?php if(request('search')): ?>
            for title: <strong><?php echo e(request('search')); ?></strong>
        <?php endif; ?>
        <?php if(request('posted_by')): ?>
            <?php if(request('search')): ?> and <?php endif; ?>
            posted by:
            <strong>
                <?php switch(request('posted_by')):
                    case ('abc_president'): ?> ABC President <?php break; ?>
                    <?php case ('sk_president'): ?> SK President <?php break; ?>
                    <?php default: ?> <?php echo e(ucfirst(request('posted_by'))); ?>

                <?php endswitch; ?>
            </strong>
        <?php endif; ?>
    </p>
<?php endif; ?>

            
            <?php if($announcements->count()): ?>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left">Announcement</th>
                                <th class="px-6 py-3 text-left">Date</th>
                              
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200 cursor-pointer">
                                <td class="px-6 py-4"><?php echo e($announcement->title); ?></td>
                                <td class="px-6 py-4">
                                    <?php echo e(\Carbon\Carbon::parse($announcement->date)->format('M d, Y')); ?><br>
                                    <span class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($announcement->time)->format('g:i A')); ?></span>
                                </td>
                              
                                <td class="px-6 py-4 text-center">
                                    <button @click.stop="announcement = <?php echo e($announcement->toJson()); ?>; open = true;"
                                        class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white w-9 h-9 rounded-md shadow transition"
                                        title="View Announcement">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <?php echo e($announcements->withQueryString()->links()); ?>

                </div>
            </div>
            <?php else: ?>
                <p class="text-gray-500 text-center mt-20 text-lg">No announcements available.</p>
            <?php endif; ?>

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
        <h3 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-6 border-b pb-4" 
            x-text="announcement?.title">
        </h3>

        <!-- 📅 Date, Time, Posted Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4 text-xs text-gray-700">

            <!-- 📅 Date & Time -->
            <div class="p-3 bg-gray-50 rounded shadow-sm">
                <p class="text-gray-500 font-semibold uppercase text-[10px]">Date & Time</p>
                <p class="mt-1 text-gray-900 text-xs font-medium"
                   x-text="(() => {
                        if (!announcement?.date && !announcement?.time) return '—';
                        const date = new Date(`${announcement.date} ${announcement.time}`);
                        return date.toLocaleString('en-US', {
                            month: 'short', day: 'numeric', year: 'numeric',
                            hour: 'numeric', minute: '2-digit', hour12: true
                        }).replace(',', '');
                   })()">
                </p>
            </div>

            <!-- 🧑‍💼 Posted By / On -->
            <div class="p-3 bg-gray-50 rounded shadow-sm">
                <p class="text-gray-500 font-semibold uppercase text-[10px]">Posted By / On</p>
                <p class="mt-1 text-gray-900 text-xs font-medium">
                    <span x-text="
                        announcement?.posted_by === 'abc_president' ? 'ABC President' :
                        announcement?.posted_by === 'sk_president' ? 'SK President' :
                        (announcement?.posted_by 
                            ? announcement.posted_by.charAt(0).toUpperCase() + announcement.posted_by.slice(1) 
                            : '—')
                    "></span>
                    &nbsp;•&nbsp;
                    <span x-text="
                        announcement?.created_at 
                            ? new Date(announcement.created_at).toLocaleString('en-US', {
                                month: 'short', day: 'numeric', year: 'numeric',
                                hour: 'numeric', minute: '2-digit', hour12: true
                            })
                            : '—'
                    "></span>
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    <template x-for="img in announcement.images" :key="img.id">
                        <div class="relative group overflow-hidden rounded-lg shadow-sm cursor-pointer bg-gray-100 flex items-center justify-center h-56"
                             @click="imageSrc = '/storage/' + img.path; imageModal = true">
                            <img :src="'/storage/' + img.path" alt="Announcement Image"
                                 class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105 group-hover:brightness-90">
                            <div class="absolute inset-0 bg-black bg-opacity-25 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- 🔍 Fullscreen Image Modal -->
        <div 
            x-show="imageModal" 
            x-transition.opacity 
            x-cloak
            @click.self="imageModal = false"
            class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50"
        >
            <button 
                @click="imageModal = false"
                class="absolute top-6 right-6 text-white text-4xl font-bold hover:text-red-400 transition z-50"
            >&times;</button>

            <img :src="imageSrc" class="w-full h-full object-contain">
        </div>

    </div>
</div>

        </section>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/announcements/index.blade.php ENDPATH**/ ?>