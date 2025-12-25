

<?php $__env->startSection('content'); ?>
<div class="flex font-sans min-h-screen overflow-hidden">

    
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">

        
        <div class="sticky top-0 z-10 bg-white shadow">
            <?php echo $__env->make('partials.secretary-header', ['title' => '📄 Pending Document Requests'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="p-6">
            <h1 class="text-2xl font-bold text-red-800 mb-6">❌ Rejected Document Requests</h1>

            <?php if($rejectedDocuments->isEmpty()): ?>
                <div class="bg-white p-6 rounded shadow text-gray-600">
                    No rejected documents found.
                </div>
            <?php else: ?>
            <div class="bg-white shadow rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-300 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Reference Code</th>
                                <th class="px-4 py-3 text-left">Resident Name</th>
                                <th class="px-4 py-3 text-left">Document Type</th>
                               
                                <th class="px-4 py-3 text-left">Rejected At</th>
                                <th class="px-4 py-3 text-left">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php $__currentLoopData = $rejectedDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($loop->odd ? 'bg-white' : 'bg-gray-100'); ?> border-t">
                                    <td class="px-4 py-2"><?php echo e($index + 1); ?></td>
                                    <td class="px-4 py-2"><?php echo e($doc->reference_code ?? 'N/A'); ?></td>
                                    <td class="px-4 py-2"><?php echo e($doc->resident->full_name ?? 'N/A'); ?></td>
                                    <td class="px-4 py-2"><?php echo e($doc->document_type); ?></td>
                                    
                                    <td class="px-4 py-2"><?php echo e($doc->updated_at->format('M d, Y')); ?></td>
                                    <td class="px-4 py-2 text-red-700"><?php echo e($doc->rejection_reason ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/services/rejected.blade.php ENDPATH**/ ?>