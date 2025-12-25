

<?php $__env->startSection('title', 'Document Formats'); ?>

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

  

    
    <main class="flex-1 max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow">

        <a href="<?php echo e(route('secretary.dashboard')); ?>" 
           class="inline-block mb-6 text-blue-600 hover:underline font-semibold">
            ← Back to Dashboard
        </a>

        <script src="//unpkg.com/alpinejs" defer></script>

        <h1 class="text-2xl font-bold mb-6">Document Formats for Barangay <?php echo e(Auth::user()->barangay->name ?? ''); ?></h1>

        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <a href="<?php echo e(route('secretary.formats.create')); ?>" 
           class="bg-yellow-400 text-blue-900 px-4 py-2 rounded font-semibold hover:bg-yellow-500 mb-6 inline-block">
            + Add New Format
        </a>

        <?php if($formats->isEmpty()): ?>
            <p>No formats added yet.</p>
        <?php else: ?>
            <ul>
                <?php $__currentLoopData = $formats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $format): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="border rounded shadow mb-3" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="w-full text-left px-4 py-3 bg-gray-100 hover:bg-gray-200 flex justify-between items-center font-semibold">
                            <?php echo e($format->title); ?>

                            <svg :class="{'rotate-180': open}" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition 
                             class="px-4 py-3 whitespace-pre-line bg-white text-gray-800 leading-relaxed">
                            <?php echo e($format->content); ?>

                            <div class="flex justify-end space-x-4 mt-4 items-center">
                                <a href="<?php echo e(route('secretary.formats.edit', $format->id)); ?>" 
                                   class="text-blue-600 hover:underline font-medium text-sm flex items-center h-6">
                                   ✏️ Edit
                                </a>

                                <form action="<?php echo e(route('secretary.formats.destroy', $format->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this format?');" class="flex items-center h-6">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button"
        class="text-red-600 hover:underline text-sm delete-btn">
    🗑️ Delete
</button>

                                </form>
                            </div>
                        </div>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>

    </main>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = button.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/formats/index.blade.php ENDPATH**/ ?>