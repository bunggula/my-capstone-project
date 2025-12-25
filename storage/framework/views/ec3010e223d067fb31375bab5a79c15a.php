<div x-data="{ imageModal: false, imageSrc: '' }" class="space-y-6 relative">

    <!-- Title -->
    <h3 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-6 border-b pb-4">
        <?php echo e($announcement->title); ?>

    </h3>

    <!-- 📅 Date & Time + 🧑‍💼 Posted By / On -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-700">

        <!-- 📅 Date & Time -->
        <div class="p-3 bg-gray-50 rounded shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-[9px]">Date & Time</p>
            <p class="mt-0.5 text-gray-900 text-xs font-medium">
                <?php if($announcement->date && $announcement->time): ?>
                    <?php echo e(\Carbon\Carbon::parse($announcement->date . ' ' . $announcement->time)->format('F j, Y , g:i A')); ?>

                <?php else: ?>
                    —
                <?php endif; ?>
            </p>
        </div>

        <!-- 🧑‍💼 Posted By / On -->
        <div class="p-3 bg-gray-50 rounded shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-[9px]">Posted By / Posted On</p>
            <p class="mt-0.5 text-gray-900 text-xs font-medium">
                <?php if($announcement->posted_by === 'abc_president'): ?>
                    ABC President
                <?php elseif($announcement->posted_by === 'sk_president'): ?>
                    SK President
                <?php else: ?>
                    <?php echo e(ucfirst($announcement->posted_by)); ?>

                <?php endif; ?>
                &nbsp;•&nbsp;
                <?php echo e($announcement->created_at->format('M j, Y , g:i A')); ?>

            </p>
        </div>
    </div>

    <?php
    $roles = [
        'brgy_captain' => 'Barangay Captain',
        'secretary' => 'Secretary',
    ];
?>

<!-- Target Audience -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
    <div class="p-2 bg-gray-50 rounded shadow-sm">
        <p class="text-gray-500 font-semibold uppercase text-[9px]">Target Audience</p>
        <p class="mt-0.5 text-gray-900 text-xs font-medium">
            <?php if($announcement->target === 'all'): ?>
                All Barangays
                <?php if($announcement->target_role): ?>
                    (<?php echo e($roles[$announcement->target_role] ?? $announcement->target_role); ?>)
                <?php endif; ?>
            <?php else: ?>
                <?php echo e($announcement->barangay->name ?? '—'); ?>

                <?php if($announcement->target_role): ?>
                    (<?php echo e($roles[$announcement->target_role] ?? $announcement->target_role); ?>)
                <?php endif; ?>
            <?php endif; ?>
        </p>
    </div>
</div>


    <!-- Details Section -->
    <div class="p-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm mb-4">
        <p class="text-gray-600 font-semibold uppercase text-xs mb-1">Details</p>
        <div class="max-h-36 overflow-y-auto whitespace-pre-line px-3 py-2 bg-white rounded-md border text-gray-700 text-sm leading-relaxed">
            <?php echo e($announcement->details); ?>

        </div>
    </div>

    <!-- Images Grid -->
    <?php if($announcement->images->count()): ?>
        <div>
            <p class="text-gray-500 font-semibold uppercase text-xs mb-3">Images</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <?php $__currentLoopData = $announcement->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative group overflow-hidden rounded-lg shadow-sm cursor-pointer bg-gray-100 flex items-center justify-center"
                         style="width:100%; height:14rem;"
                         @click="imageSrc = '<?php echo e(asset('storage/' . $img->path)); ?>'; imageModal = true">
                        <img src="<?php echo e(asset('storage/' . $img->path)); ?>"
                             alt="Announcement Image"
                             class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105 group-hover:brightness-90">
                        <div class="absolute inset-0 bg-black bg-opacity-25 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Fullscreen Image Modal -->
    <div x-show="imageModal" x-transition.opacity x-cloak
         @click.self="imageModal = false"
         class="fixed inset-0 bg-black bg-opacity-95 flex items-center justify-center z-50">
        
        <!-- Close Button -->
        <button @click="imageModal = false"
                class="absolute top-6 right-6 text-white text-4xl font-bold hover:text-red-400 transition z-50">
            &times;
        </button>

        <!-- Modal Image -->
        <img :src="imageSrc" class="w-full h-full object-contain">
    </div>

</div>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/announcements/_view.blade.php ENDPATH**/ ?>