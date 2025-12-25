

<?php $__env->startSection('title', 'Pending Events'); ?>

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
            <a href="<?php echo e(url()->previous()); ?>" class="text-sm text-blue-600 hover:underline inline-block">
                ← Back
            </a>
        </div>

        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">⏳ Pending Events for Approval</h1>
            <a href="<?php echo e(route('secretary.events.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                ➕ Add Event
            </a>
        </div>

        
        <form action="<?php echo e(route('secretary.events.destroy-multiple')); ?>" method="POST" onsubmit="return confirm('Are you sure?');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <div class="mb-4">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    🗑️ Delete Selected
                </button>
            </div>

            
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-200 p-4 mb-6 rounded-lg bg-gray-50 shadow-sm">
                    <div class="flex justify-between items-start mb-2">

                        
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="selected_events[]" value="<?php echo e($event->id); ?>" class="mt-1">
                            <h2 class="font-semibold text-xl"><?php echo e($event->title); ?></h2>
                        </div>

                        <div class="space-x-2">
                            <a href="<?php echo e(route('secretary.events.edit', $event->id)); ?>"
                               class="text-blue-600 hover:underline text-sm">
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
                        </div>
                    </div>

                    <p class="mb-1"><strong>Date:</strong> <?php echo e($event->date); ?></p>
                    <p class="mb-1"><strong>Time:</strong> <?php echo e($event->time); ?></p>
                    <p class="mb-1"><strong>Venue:</strong> <?php echo e($event->venue); ?></p>
                    <p class="mb-2"><?php echo e($event->details); ?></p>

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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </form>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/events/pending.blade.php ENDPATH**/ ?>