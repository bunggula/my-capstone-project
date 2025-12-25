

<?php $__env->startSection('title', 'Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex w-screen h-screen font-sans overflow-hidden">
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col relative max-h-screen overflow-hidden">
        <?php echo $__env->make('partials.secretary-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 overflow-y-auto relative bg-gray-50">
            <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('/images/back.jpg');"></div>
            <div class="absolute inset-0 bg-white bg-opacity-80 z-10"></div>

            <div class="relative z-20 p-8">
                <div class="max-w-7xl mx-auto">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">📋 Reports</h1>

                    <?php if(session('success')): ?>
                        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded">
                            ✅ <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="bg-white rounded-xl shadow p-6 mb-8">
                        <form action="<?php echo e(route('secretary.reports.upload')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <label for="report_file" class="block text-gray-700 font-medium mb-2">📁 Upload Report File</label>
                            <input type="file" name="report_file" id="report_file" required class="block w-full border border-gray-300 rounded-lg py-2 px-3 mb-4">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                                ⬆️ Upload
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">📂 Uploaded Files</h2>

                        <?php if($reports->isEmpty()): ?>
                            <p class="text-gray-500">No reports uploaded yet.</p>
                        <?php else: ?>
                            <table class="min-w-full table-auto">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">File Name</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Uploaded At</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="border-t">
                                            <td class="px-4 py-2"><?php echo e($report->filename); ?></td>
                                            <td class="px-4 py-2"><?php echo e($report->created_at->format('M d, Y H:i')); ?></td>
                                            <td class="px-4 py-2">
                                                <a href="<?php echo e(asset('storage/' . $report->filepath)); ?>" target="_blank" class="text-blue-600 hover:underline">📥 Download</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/reports/reports.blade.php ENDPATH**/ ?>