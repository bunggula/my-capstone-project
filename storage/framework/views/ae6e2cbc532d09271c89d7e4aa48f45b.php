

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-white text-gray-800">
    <!-- Sidebar -->
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Panel -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <?php echo $__env->make('partials.secretary-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    



        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto p-6" x-data="{ type: '<?php echo e(request('type', 'daily')); ?>' }">
            <h2 class="text-2xl font-bold mb-4">📄 Generate Waste Report</h2>

            <!-- Report Filter Form -->
            <div class="bg-white shadow rounded p-4 max-w-md w-full text-sm" x-data="{ type: '<?php echo e(request('type', 'daily')); ?>' }">
    <form method="GET" action="<?php echo e(route('secretary.reports.generate')); ?>" class="space-y-2">
        <!-- Type Selector -->
        <div>
            <label class="block font-medium mb-1">Report Type:</label>
            <select name="type" x-model="type" class="border w-full rounded px-2 py-1.5">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>

        <!-- DAILY -->
        <div x-show="type === 'daily'" class="transition-all">
            <label class="block font-medium mb-1">Date:</label>
            <input type="date" name="date" class="border w-full rounded px-2 py-1.5" :disabled="type !== 'daily'">
        </div>

        <!-- WEEKLY -->
        <div x-show="type === 'weekly'" class="transition-all">
            <label class="block font-medium mb-1">Start of Week:</label>
            <input type="date" name="start_date" class="border w-full rounded px-2 py-1.5" :disabled="type !== 'weekly'">

            <label class="block font-medium mt-2 mb-1">End of Week:</label>
            <input type="date" name="end_date" class="border w-full rounded px-2 py-1.5" :disabled="type !== 'weekly'">
        </div>

        <!-- MONTHLY -->
        <div x-show="type === 'monthly'" class="transition-all">
            <label class="block font-medium mb-1">Month:</label>
            <input type="month" name="month" class="border w-full rounded px-2 py-1.5" :disabled="type !== 'monthly'">
        </div>

        <!-- Submit -->
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded w-full mt-2">
            Generate Report
        </button>
    </form>
</div>

<?php if($reports->isNotEmpty()): ?>
    <div class="mt-4 flex justify-start">
        <a 
            href="<?php echo e(route('secretary.reports.print', request()->all())); ?>" 
            target="_blank"
            class="bg-green-800 hover:bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2"
        >
            🖶 <span>Print</span>
        </a>
    </div>
<?php endif; ?>


            <!-- Report Results -->
            <?php if($reports->isNotEmpty()): ?>
                <h3 class="text-xl font-bold mb-3">Generated Report (<?php echo e(ucfirst($type)); ?>)</h3>

                <div class="overflow-x-auto">
            <table class="w-full table-auto border text-sm">
    <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="border px-4 py-2">Date</th>
                                <th class="border px-4 py-2">Biodegradable</th>
                                <th class="border px-4 py-2">Recyclable</th>
                                <th class="border px-4 py-2">Residual (Recyclable)</th>
                                <th class="border px-4 py-2">Residual (Disposal)</th>
                                <th class="border px-4 py-2">Special</th>
                                <th class="border px-4 py-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($loop->odd ? 'bg-white' : 'bg-gray-100'); ?> border-t">
                                    <td class="border px-4 py-2"><?php echo e($report->date_collected); ?></td>
                                    <td class="border px-4 py-2"><?php echo e($report->biodegradable); ?> kg</td>
                                    <td class="border px-4 py-2"><?php echo e($report->recyclable); ?> kg</td>
                                    <td class="border px-4 py-2"><?php echo e($report->residual_recyclable); ?> kg</td>
                                    <td class="border px-4 py-2"><?php echo e($report->residual_disposal); ?> kg</td>
                                    <td class="border px-4 py-2"><?php echo e($report->special); ?> kg</td>
                                    <td class="border px-4 py-2"><?php echo e($report->remarks); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No records found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/reports/generate.blade.php ENDPATH**/ ?>