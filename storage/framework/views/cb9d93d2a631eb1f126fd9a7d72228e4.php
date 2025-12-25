

<?php $__env->startSection('title', 'Rejected Events'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden font-sans">

    <!-- Sidebar -->
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main content area -->
    <div class="flex flex-col flex-1 max-h-screen">

        <!-- Header -->
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Resident Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Scrollable content -->
        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">

    
    <main class="flex-1 p-6 bg-white rounded shadow max-w-5xl mx-auto my-10">

        
        <div class="mb-6">
            <a href="<?php echo e(route('secretary.events.index')); ?>"
               class="inline-flex items-center bg-yellow-300 text-blue-900 px-4 py-2 rounded shadow hover:bg-yellow-400 transition font-semibold">
                ← Back
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-6">❌ Rejected Events</h1>

        
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border border-gray-200 p-4 mb-6 rounded-lg bg-gray-50 shadow-sm">
                <div class="flex justify-between items-start mb-2">
                    <h2 class="font-semibold text-xl"><?php echo e($event->title); ?></h2>

                    
                    <div class="space-x-2">
                        <a href="<?php echo e(route('secretary.events.edit', $event->id)); ?>" class="text-blue-600 hover:underline text-sm">
                            ✏️ Edit
                        </a>
                        <form action="<?php echo e(route('secretary.events.destroy', $event->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                    onclick="return confirm('Delete this event?')"
                                    class="text-red-600 hover:underline text-sm">
                                🗑️ Delete
                            </button>
                        </form>
                        <form action="<?php echo e(route('secretary.events.resubmit', $event->id)); ?>" method="POST" class="inline-block">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit"
                                    onclick="return confirm('Resubmit this event for captain approval?')"
                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                                🔁 Resubmit
                            </button>
                        </form>
                    </div>
                </div>

                <p class="mb-1"><strong>Date:</strong> <?php echo e($event->date); ?></p>
                <p class="mb-1"><strong>Time:</strong> <?php echo e($event->time); ?></p>
                <p class="mb-1"><strong>Venue:</strong> <?php echo e($event->venue); ?></p>
                <p class="mb-2"><?php echo e($event->details); ?></p>
                <div class="mt-4 bg-red-100 border border-red-300 text-red-800 p-4 rounded">
    <p><strong>Rejection Reason:</strong> <?php echo e($event->rejection_reason); ?></p>
    <?php if($event->rejection_notes): ?>
        <p><strong>Notes:</strong> <?php echo e($event->rejection_notes); ?></p>
    <?php endif; ?>
</div>

                
                <?php if($event->images && $event->images->count()): ?>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 mt-3">
                        <?php $__currentLoopData = $event->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="w-full h-24 bg-white border rounded flex items-center justify-center overflow-hidden">
                                <img src="<?php echo e(asset('storage/' . $image->path)); ?>"
                                     alt="Event Image"
                                     class="max-h-full max-w-full object-contain" />
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-600 text-center">No rejected events found.</p>
        <?php endif; ?>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/events/rejected.blade.php ENDPATH**/ ?>