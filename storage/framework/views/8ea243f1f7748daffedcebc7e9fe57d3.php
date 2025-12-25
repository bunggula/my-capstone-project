

<?php $__env->startSection('title', 'Add Announcement'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-100 overflow-hidden">
    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.abc-header', ['title' => 'Add Announcement'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
                <a href="<?php echo e(route('abc.announcements.index')); ?>" class="text-gray-600 hover:text-gray-900 font-medium inline-block mb-4">
                    ← Back to Announcements
                </a>

                <h1 class="text-2xl font-bold mb-6">➕ New Announcement</h1>

                
                <?php echo $__env->make('abc.announcements._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const targetRadios = document.querySelectorAll('input[name="target"]');
        const targetFilters = document.getElementById('target-filters');

        function toggleTargetFields() {
            const selected = document.querySelector('input[name="target"]:checked').value;
            if (selected === 'specific') {
                targetFilters.style.display = 'block';
            } else {
                targetFilters.style.display = 'none';
            }
        }

        toggleTargetFields();
        targetRadios.forEach(radio => {
            radio.addEventListener('change', toggleTargetFields);
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/announcements/create.blade.php ENDPATH**/ ?>