

<?php $__env->startSection('content'); ?>
<div class="flex font-sans min-h-screen overflow-hidden">

    
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">

        
        <div class="sticky top-0 z-10 bg-white shadow">
            <?php echo $__env->make('partials.secretary-header', ['title' => '✅ Completed Document Requests'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="p-6 overflow-y-auto flex-1">
            <h2 class="text-lg font-semibold mb-4">📁 Completed Requests in Your Barangay</h2>

            
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-300 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Reference Code</th>
                                <th class="px-4 py-3 text-left">Resident</th>
                                <th class="px-4 py-3 text-left">Document Type</th>
                                <th class="px-4 py-3 text-left">Pickup Date</th>
                                <th class="px-4 py-3 text-left">Completed At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="<?php echo e($loop->odd ? 'bg-white' : 'bg-gray-100'); ?> border-t">
                                    <td class="px-4 py-2"><?php echo e($index + 1); ?></td>
                                    <td class="px-4 py-2"><?php echo e($request->reference_code ?? 'N/A'); ?></td>
                                    <td class="px-4 py-2"><?php echo e($request->resident->full_name ?? 'N/A'); ?></td>
                                    <td class="px-4 py-2"><?php echo e($request->document_type); ?></td>
                                    <td class="px-4 py-2">
                                        <?php echo e($request->pickup_date ? \Carbon\Carbon::parse($request->pickup_date)->format('M d, Y') : 'Not Set'); ?>

                                    </td>
                                    <td class="px-4 py-2 text-green-600 font-semibold">
                                        <?php echo e(\Carbon\Carbon::parse($request->updated_at)->format('M d, Y h:i A')); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        No completed requests found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/document_requests/completed.blade.php ENDPATH**/ ?>