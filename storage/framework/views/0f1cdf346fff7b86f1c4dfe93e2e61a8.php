

<?php $__env->startSection('title', 'Add Document Format'); ?>
<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-hidden font-sans">

    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-col flex-1 max-h-screen">

        <?php echo $__env->make('partials.secretary-header', ['title' => 'Resident Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50">

            <main class="flex-1 p-6 bg-white rounded shadow max-w-5xl mx-auto my-10">

                <a href="<?php echo e(route('secretary.formats.index')); ?>" class="inline-block mb-6 text-blue-600 hover:underline">
                    &larr; Back to Formats
                </a>

                <h1 class="text-2xl font-bold mb-6">Add Document Format</h1>

                <form method="POST" action="<?php echo e(route('secretary.formats.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="block font-semibold mb-2">Format Title</label>
                        <input type="text" name="title" id="title" value="<?php echo e(old('title')); ?>" required
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

                    <!-- Content -->
                    <div class="mb-4">
                        <label for="content" class="block font-semibold mb-2">
                            Format Content <small class="text-gray-500">(use placeholders like <code>{{name}}</code>, <code>{{birthdate}}</code>, etc.)</small>
                        </label>
                        <textarea name="content" id="content" rows="8" required
                            class="w-full border border-gray-300 rounded p-2 font-mono"><?php echo e(old('content')); ?></textarea>
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

                    <!-- Price -->
                    <div class="mb-4">
                        <label for="price" class="block font-semibold mb-2">Price (₱)</label>
                        <input type="number" step="0.01" name="price" id="price" value="<?php echo e(old('price', '0.00')); ?>" required
                            class="w-full border border-gray-300 rounded p-2">
                        <?php $__errorArgs = ['price'];
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

                    <!-- Submit -->
                    <button type="submit"
                        class="bg-yellow-400 text-blue-900 px-6 py-2 rounded font-semibold hover:bg-yellow-500 transition">
                        Save Format
                    </button>
                </form>

            </main>
        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/formats/create.blade.php ENDPATH**/ ?>