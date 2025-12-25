

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-100">
    
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main class="flex-1 flex flex-col">
        
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Rejected Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-md p-6">

                
                <div class="mb-4">
                    <a href="<?php echo e(route('secretary.residents.pending')); ?>"
                        class="inline-flex items-center text-blue-600 hover:underline font-semibold transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 stroke-current" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Residents List
                    </a>
                </div>

                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold mb-2 text-gray-700">🚫 Rejected Resident Accounts</h2>
                        <p class="text-sm text-gray-500">
                            List of residents whose registrations have been rejected.
                        </p>
                    </div>

                    <form method="GET" action="<?php echo e(route('secretary.residents.rejected')); ?>" class="w-full md:w-1/3">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="Search by name, email, or barangay..."
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" />
                    </form>
                </div>

                <?php if($rejectedResidents->isEmpty()): ?>
                    <div class="bg-red-100 text-red-800 text-sm p-4 rounded-md">
                        No rejected resident accounts found.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm text-center">
                            <thead class="bg-red-600 text-white sticky top-0 z-10">
                                <tr>
                                    <th class="px-3 py-2">Full Name</th>
                                    <th class="px-3 py-2">Birthdate</th>
                                    <th class="px-3 py-2">Email</th>
                                    <th class="px-3 py-2">Barangay</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <?php $__currentLoopData = $rejectedResidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-2 py-2">
                                            <?php echo e(trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name} {$resident->suffix}")); ?>

                                        </td>
                                        <td class="px-2 py-2"><?php echo e(\Carbon\Carbon::parse($resident->birthdate)->format('M d, Y')); ?></td>
                                        <td class="px-2 py-2"><?php echo e($resident->email); ?></td>
                                        <td class="px-2 py-2"><?php echo e($resident->barangay->name ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/residents/rejected.blade.php ENDPATH**/ ?>