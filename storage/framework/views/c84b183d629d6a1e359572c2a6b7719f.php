

<?php $__env->startSection('title', 'Edit Format'); ?>
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

    <h1 class="text-2xl font-bold mb-6">Edit Document Format</h1>

    <form method="POST" action="<?php echo e(route('secretary.formats.update', $format->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-4">
            <label for="title" class="block font-semibold mb-2">Format Title</label>
            <input type="text" name="title" id="title" value="<?php echo e(old('title', $format->title)); ?>" required
                class="w-full border border-gray-300 rounded p-2">
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 mt-1 text-sm"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="content" class="block font-semibold mb-2">Format Content</label>
            <textarea name="content" id="content" rows="8" required
                class="w-full border border-gray-300 rounded p-2 font-mono"><?php echo e(old('content', $format->content)); ?></textarea>
            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 mt-1 text-sm"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="bg-yellow-400 text-blue-900 px-6 py-2 rounded font-semibold hover:bg-yellow-500 transition">
                Update Format
            </button>
            <a href="<?php echo e(route('secretary.formats.index')); ?>" class="text-gray-600 hover:underline">← Back</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/formats/edit.blade.php ENDPATH**/ ?>