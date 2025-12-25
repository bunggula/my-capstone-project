

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <h2 class="text-xl font-bold text-blue-800 mb-4">📝 Concern Details</h2>

    <div class="bg-white rounded shadow p-6 text-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>ID:</strong> <?php echo e($concern->id); ?></div>
            <div><strong>Reported By:</strong> <?php echo e($concern->resident->name ?? 'N/A'); ?></div>
            <div><strong>Barangay ID:</strong> <?php echo e($concern->barangay_id); ?></div>
            <div><strong>Reported By:</strong> <?php echo e($concern->user->name ?? 'N/A'); ?></div>
            <div><strong>Zone:</strong> <?php echo e($concern->zone ?? 'N/A'); ?></div>
            <div><strong>Street:</strong> <?php echo e($concern->street ?? 'N/A'); ?></div>
            <div><strong>Status:</strong> 
                <span class="px-2 py-1 rounded <?php echo e($concern->status == 'resolved' ? 'bg-green-600 text-white' : 'bg-yellow-500 text-white'); ?>">
                    <?php echo e(ucfirst($concern->status)); ?>

                </span>
            </div>
            <div><strong>Created At:</strong> <?php echo e($concern->created_at); ?></div>
            <div><strong>Updated At:</strong> <?php echo e($concern->updated_at); ?></div>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold text-lg text-blue-800">📌 <?php echo e($concern->title); ?></h3>
            <p class="mt-2 text-gray-700"><?php echo e($concern->description); ?></p>

            <?php if($concern->image): ?>
                <div class="mt-4">
                    <strong>Attached Image:</strong>
                    <img src="<?php echo e(asset('storage/' . $concern->image)); ?>" alt="Concern Image" class="mt-2 rounded w-64">
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-6">
            <a href="<?php echo e(route('captain.concerns.index')); ?>" class="text-blue-700 hover:underline">← Back to concerns</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/concerns/show.blade.php ENDPATH**/ ?>