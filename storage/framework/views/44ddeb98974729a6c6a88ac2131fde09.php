

<?php $__env->startSection('title', 'Edit Event'); ?>

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
<a href="<?php echo e(url()->previous()); ?>" class="text-sm text-blue-600 hover:underline mb-4 inline-block">
        ← Back
    </a>
    <h2 class="text-2xl font-bold mb-4">Edit Event</h2>
    
    <form method="POST" action="<?php echo e(route('secretary.events.update', $event->id)); ?>" enctype="multipart/form-data">

        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <label class="block mb-2 font-semibold">Event Title</label>
        <input type="text" name="title" value="<?php echo e($event->title); ?>" required class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2 font-semibold">Date</label>
        <input type="date" name="date" value="<?php echo e($event->date); ?>" required class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2 font-semibold">Time</label>
        <input type="time" name="time" value="<?php echo e($event->time); ?>" required class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2 font-semibold">Venue</label>
        <input type="text" name="venue" value="<?php echo e($event->venue); ?>" required class="w-full p-2 border rounded mb-4" />

        <label class="block mb-2 font-semibold">Details</label>
        <textarea name="details" rows="4" required class="w-full p-2 border rounded mb-4"><?php echo e($event->details); ?></textarea>
        
<?php if($event->images && $event->images->count()): ?>
    <label class="block mb-2 font-semibold">Current Images</label>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
        <?php $__currentLoopData = $event->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative">
                <img src="<?php echo e(asset('storage/' . $image->path)); ?>" alt="Event Image" class="rounded shadow max-h-32 w-full object-cover">
                
                <label class="absolute top-0 right-0 bg-white bg-opacity-80 text-xs text-red-600 px-1">
                    <input type="checkbox" name="delete_images[]" value="<?php echo e($image->id); ?>">
                    🗑️
                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>


<label class="block mb-2 font-semibold">Upload New Images</label>
<input type="file" name="images[]" multiple accept="image/*" class="mb-4 w-full border p-2 rounded">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Update Event
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/events/edit.blade.php ENDPATH**/ ?>