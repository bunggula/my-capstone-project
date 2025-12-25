

<?php $__env->startSection('title', 'All Barangay Officials'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-100 overflow-hidden">
    <?php echo $__env->make('partials.abc-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.abc-header', ['title' => 'All Barangay Officials'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        

        <div class="flex-1 overflow-y-auto p-6">
        <div x-data="{
    barangay: '<?php echo e($selectedBarangay); ?>',
    position: '<?php echo e($positionFilter); ?>',
    update() {
        const params = new URLSearchParams();
        if(this.barangay) params.set('barangay', this.barangay);
        if(this.position) params.set('position', this.position);
        window.location.href = '<?php echo e(route('abc.all.index')); ?>?' + params.toString();
    },
    clear() {
        this.barangay = '';
        this.position = '';
        window.location.href = '<?php echo e(route('abc.all.index')); ?>';
    },
    print() {
        window.print();
    }
}" class="mb-4 flex items-center space-x-4">

    <label for="barangay" class="font-semibold">Filter by Barangay:</label>
    <select id="barangay" x-model="barangay" @change="update()" class="border px-3 py-2 rounded">
        <option value="">All Barangays</option>
        <?php $__currentLoopData = $allBarangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($b->id); ?>"><?php echo e($b->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <!-- Clear button only shows if there is a selected filter -->
    <button 
        type="button" 
        x-show="barangay || position" 
        @click="clear()" 
        class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600"
    >
        Clear
    </button>

    <!-- Print button always visible -->
    <a href="<?php echo e(route('abc.all.print', ['barangay' => $selectedBarangay, 'position' => $positionFilter])); ?>" 
   target="_blank"
   class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">
    Print
</a>

</div>

            <div class="max-w-7xl mx-auto p-6 bg-white rounded shadow">
                <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <h2 class="mt-6 mb-2 text-xl font-bold"><?php echo e($barangay->name); ?></h2>

                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border text-sm text-left">
                            <thead class="bg-blue-600 text-white uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-2">Full Name</th>
                                    <th class="px-4 py-2">Position</th>
                                    <th class="px-4 py-2">Start Year</th>
                                    <th class="px-4 py-2">End Year</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                
                                <?php $captain = $barangay->users->firstWhere('role', 'brgy_captain'); ?>
                                <?php if($captain): ?>
                                    <tr x-data="{ showViewCaptain<?php echo e($captain->id); ?>: false }" class="bg-blue-50">
                                        <td class="border px-4 py-2 font-semibold">
                                            <?php echo e($captain->last_name); ?>, <?php echo e($captain->first_name); ?> <?php echo e($captain->middle_name ?? ''); ?>

                                        </td>
                                        <td class="border px-4 py-2">Barangay Captain</td>
                                        <td class="border px-4 py-2"><?php echo e($captain->start_year ?? '-'); ?></td>
                                        <td class="border px-4 py-2"><?php echo e($captain->end_year ?? '-'); ?></td>
                                        <td class="border px-4 py-2">
                                            <button @click="showViewCaptain<?php echo e($captain->id); ?> = true" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md">
                                                View
                                            </button>
                                            
                                            <div x-show="showViewCaptain<?php echo e($captain->id); ?>" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div @click.away="showViewCaptain<?php echo e($captain->id); ?> = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
                                                    <h2 class="text-xl font-bold mb-4">Barangay Captain Details</h2>
                                                    <p><strong>Full Name:</strong> <?php echo e($captain->first_name); ?> <?php echo e($captain->middle_name ?? ''); ?> <?php echo e($captain->last_name); ?></p>
                                                    <p><strong>Email:</strong> <?php echo e($captain->email ?? '-'); ?></p>
                                                    <p><strong>Gender:</strong> <?php echo e(ucfirst($captain->gender)); ?></p>
                                                    <p><strong>Start Year:</strong> <?php echo e($captain->start_year ?? '-'); ?></p>
                                                    <p><strong>End Year:</strong> <?php echo e($captain->end_year ?? '-'); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                
                                <?php $secretary = $barangay->users->firstWhere('role', 'secretary'); ?>
                                <?php if($secretary): ?>
                                    <tr x-data="{ showViewSecretary<?php echo e($secretary->id); ?>: false }" class="bg-green-50">
                                        <td class="border px-4 py-2 font-semibold">
                                            <?php echo e($secretary->last_name); ?>, <?php echo e($secretary->first_name); ?> <?php echo e($secretary->middle_name ?? ''); ?>

                                        </td>
                                        <td class="border px-4 py-2">Barangay Secretary</td>
                                        <td class="border px-4 py-2"><?php echo e($secretary->start_year ?? '-'); ?></td>
                                        <td class="border px-4 py-2"><?php echo e($secretary->end_year ?? '-'); ?></td>
                                        <td class="border px-4 py-2">
                                            <button @click="showViewSecretary<?php echo e($secretary->id); ?> = true" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-md">
                                                View
                                            </button>
                                            
                                            <div x-show="showViewSecretary<?php echo e($secretary->id); ?>" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div @click.away="showViewSecretary<?php echo e($secretary->id); ?> = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
                                                    <h2 class="text-xl font-bold mb-4">Barangay Secretary Details</h2>
                                                    <p><strong>Full Name:</strong> <?php echo e($secretary->first_name); ?> <?php echo e($secretary->middle_name ?? ''); ?> <?php echo e($secretary->last_name); ?></p>
                                                    <p><strong>Email:</strong> <?php echo e($secretary->email ?? '-'); ?></p>
                                                    <p><strong>Gender:</strong> <?php echo e(ucfirst($secretary->gender)); ?></p>
                                                    <p><strong>Start Year:</strong> <?php echo e($secretary->start_year ?? '-'); ?></p>
                                                    <p><strong>End Year:</strong> <?php echo e($secretary->end_year ?? '-'); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                
                                <?php $__currentLoopData = $barangay->barangayOfficials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $official): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr x-data="{ showViewOfficial<?php echo e($official->id); ?>: false }" class="bg-white">
                                        <td class="border px-4 py-2"><?php echo e($official->last_name); ?>, <?php echo e($official->first_name); ?> <?php echo e($official->middle_name ?? ''); ?></td>
                                        <td class="border px-4 py-2"><?php echo e($official->position); ?></td>
                                        <td class="border px-4 py-2"><?php echo e($official->start_year ?? '-'); ?></td>
                                        <td class="border px-4 py-2"><?php echo e($official->end_year ?? '-'); ?></td>
                                        <td class="border px-4 py-2">
                                            <button @click="showViewOfficial<?php echo e($official->id); ?> = true" class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-md">
                                                View
                                            </button>
                                            
                                            <div x-show="showViewOfficial<?php echo e($official->id); ?>" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div @click.away="showViewOfficial<?php echo e($official->id); ?> = false" class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
                                                    <h2 class="text-xl font-bold mb-4">Official Details</h2>
                                                    <p><strong>Full Name:</strong> <?php echo e($official->first_name); ?> <?php echo e($official->middle_name ?? ''); ?> <?php echo e($official->last_name); ?></p>
                                                    <p><strong>Position:</strong> <?php echo e($official->position); ?></p>
                                                    <p><strong>Email:</strong> <?php echo e($official->email ?? '-'); ?></p>
                                                    <p><strong>Start Year:</strong> <?php echo e($official->start_year ?? '-'); ?></p>
                                                    <p><strong>End Year:</strong> <?php echo e($official->end_year ?? '-'); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/accounts/all.blade.php ENDPATH**/ ?>