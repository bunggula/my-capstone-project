

<?php $__env->startSection('title', 'Approved Events'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex w-screen h-screen font-sans overflow-hidden">

    <!-- Sidebar -->
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main content area -->
    <main class="flex-1 flex flex-col relative max-h-screen overflow-hidden">

        
        <?php echo $__env->make('partials.captain-header', ['title' => 'Resident Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">



    <div class="relative container mx-auto p-6 bg-white bg-opacity-90 rounded-lg shadow-md mt-12 max-w-5xl">
        
    <div class="mb-6">
        <a href="<?php echo e(route('captain.events.index')); ?>"
           class="inline-flex items-center bg-yellow-300 text-blue-900 px-4 py-2 rounded shadow hover:bg-yellow-400 transition font-semibold">
            ← Back
        </a>
    </div>

        <h1 class="text-2xl font-bold text-blue-900 mb-6">📌 Approved Events / Announcements</h1>

        
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-blue-50 border border-blue-200 p-4 mb-6 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold text-blue-800 mb-2"><?php echo e($event->title ?? 'Untitled Event'); ?></h2>

                <ul class="text-sm text-gray-800 space-y-1 mb-3">
                    <li><strong>Date:</strong> <?php echo e($event->date); ?></li>
                    <li><strong>Time:</strong> <?php echo e($event->time); ?></li>
                    <li><strong>Venue:</strong> <?php echo e($event->venue); ?></li>
                </ul>

                <p class="text-sm text-gray-700 mb-3 whitespace-pre-line"><?php echo e($event->details); ?></p>

                
                        <div x-data="{ open: false, image: '' }" @keydown.escape.window="open = false">
                            <?php if($event->images && $event->images->count()): ?>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4">
                                    <?php $__currentLoopData = $event->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img
                                            src="<?php echo e(asset('storage/' . $img->path)); ?>"
                                            class="cursor-pointer w-full h-36 object-cover rounded-lg shadow hover:shadow-lg transition"
                                            @click="open = true; image = '<?php echo e(asset('storage/' . $img->path)); ?>'"
                                        >
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <!-- Fullscreen Image Modal -->
                                <div
                                    x-show="open"
                                    x-transition
                                    class="fixed inset-0 z-50 bg-black bg-opacity-80 flex items-center justify-center"
                                    style="display: none;"
                                >
                                    <div class="relative">
                                        <button
                                            @click="open = false"
                                            class="absolute top-2 right-2 text-white text-3xl font-bold hover:text-red-500 focus:outline-none"
                                            aria-label="Close"
                                        >
                                            &times;
                                        </button>
                                        <img :src="image" class="max-h-[70vh] max-w-[90vw] rounded shadow-lg">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
           
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-gray-500 italic">
                No approved events or announcements available.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/events/approved.blade.php ENDPATH**/ ?>