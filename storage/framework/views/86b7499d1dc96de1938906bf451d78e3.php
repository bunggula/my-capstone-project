

<?php $__env->startSection('title', 'Captain - Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen font-sans overflow-hidden bg-gray-100">
    <!-- Sidebar -->
    <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col max-h-screen overflow-hidden">
        <!-- Header -->
        <?php echo $__env->make('partials.captain-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-blue-600"></i> Uploaded Reports
                </h1>

                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <?php if($reports->isEmpty()): ?>
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">📂 No reports available at the moment.</p>
                        </div>
                    <?php else: ?>
                        <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="bg-gray-50 border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-700 font-medium truncate w-4/5">
                                            📄 <?php echo e($report->filename); ?>

                                        </span>
                                        <a href="<?php echo e(asset('storage/' . $report->filepath)); ?>"
                                           target="_blank"
                                           class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm transition">
                                            View
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-400">Uploaded: <?php echo e($report->created_at->format('M d, Y')); ?></div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/reports/index.blade.php ENDPATH**/ ?>