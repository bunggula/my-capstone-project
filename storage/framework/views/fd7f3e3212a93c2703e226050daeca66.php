
<?php $__env->startSection('title', 'Services Reports '); ?>
<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-y-auto bg-gray-100 text-gray-800">
    <!-- Sidebar -->
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Panel -->
   <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <?php echo $__env->make('partials.secretary-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
     <div class="flex-1 overflow-y-auto p-6">
            <h2 class="text-2xl font-bold text-blue-900 mb-6">Services Reports</h2>

            <form method="GET" action="<?php echo e(route('secretary.reports.services')); ?>" 
      class="mb-4 flex flex-wrap items-center gap-4" id="filterForm">
    <!-- Document Type Filter -->
  
    <select name="document_type" id="document_type" 
            class="border rounded px-2 py-1" onchange="this.form.submit()">
        <option value="">-- Select document type --</option>
        <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($type); ?>" <?php echo e(($documentType ?? '') == $type ? 'selected' : ''); ?>>
                <?php echo e($type); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <!-- Date Filter Type -->
   
    <select name="date_filter" id="date_filter" 
            class="border rounded px-2 py-1" onchange="toggleDateInputs(); this.form.submit();">
        <option value="">-- Select type --</option>
        <option value="daily" <?php echo e(request('date_filter') == 'daily' ? 'selected' : ''); ?>>Daily</option>
        <option value="weekly" <?php echo e(request('date_filter') == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
        <option value="monthly" <?php echo e(request('date_filter') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
    </select>

  <!-- Daily Input -->
<input type="date" name="daily_date" id="daily_date" 
       class="border rounded px-2 py-1 hidden" 
       value="<?php echo e(request('daily_date')); ?>" 
       max="<?php echo e(date('Y-m-d')); ?>"
       onchange="this.form.submit()">

<!-- Weekly Inputs -->
<div id="weekly_inputs" class="hidden flex gap-2">

    <input type="date" name="start_date" 
           class="border rounded px-2 py-1" 
           value="<?php echo e(request('start_date')); ?>" 
           max="<?php echo e(date('Y-m-d')); ?>"
           onchange="this.form.submit()">

    <input type="date" name="end_date" 
           class="border rounded px-2 py-1" 
           value="<?php echo e(request('end_date')); ?>" 
           max="<?php echo e(date('Y-m-d')); ?>"
           onchange="this.form.submit()">
</div>


    <!-- Monthly Input -->
    <input type="month" name="month" id="month_date"
           class="border rounded px-2 py-1 hidden"
           value="<?php echo e(request('month')); ?>" 
           onchange="this.form.submit()">

    <!-- Clear Filters (lalabas lang kung may filter) -->
    <?php if(request()->hasAny(['document_type','date_filter','daily_date','start_date','end_date','month']) 
        && collect(request()->only(['document_type','date_filter','daily_date','start_date','end_date','month']))->filter()->isNotEmpty()): ?>
        <a href="<?php echo e(route('secretary.reports.services')); ?>"
           class="px-3 py-1 bg-red-500 text-white rounded hover:bg-gray-600 text-sm">
           Clear
        </a>
    <?php endif; ?>
    <!-- Print button (lalabas lang kung may filter) -->
<?php if(request()->hasAny(['document_type','date_filter','daily_date','start_date','end_date','month']) 
    && collect(request()->only(['document_type','date_filter','daily_date','start_date','end_date','month']))->filter()->isNotEmpty()): ?>
    <a href="<?php echo e(route('secretary.reports.services.print', [
            'document_type' => request('document_type'),
            'date_filter'   => request('date_filter'),
            'daily_date'    => request('daily_date'),
            'start_date'    => request('start_date'),
            'end_date'      => request('end_date'),
            'month'         => request('month'),
        ])); ?>" 
       target="_blank"
       class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 print:hidden text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
            </svg>
        Print
    </a>
<?php endif; ?>

</form>


<script>
function toggleDateInputs() {
    let filter = document.getElementById('date_filter').value;

    document.getElementById('daily_date').classList.add('hidden');
    document.getElementById('weekly_inputs').classList.add('hidden');
    document.getElementById('month_date').classList.add('hidden');

    if (filter === 'daily') {
        document.getElementById('daily_date').classList.remove('hidden');
    } else if (filter === 'weekly') {
        document.getElementById('weekly_inputs').classList.remove('hidden');
    } else if (filter === 'monthly') {
        document.getElementById('month_date').classList.remove('hidden');
    }
}
document.addEventListener("DOMContentLoaded", toggleDateInputs);
</script>


<?php if($completedRequests->isEmpty()): ?>
    <div class="text-gray-600">No completed service requests found.</div>
<?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full table-auto border text-sm">
            <thead class="bg-blue-600 text-white">
  <tr>
        <th class="border px-3 py-2 w-12 text-center">#</th>
        <th class="border px-3 py-2 text-left">Resident Name</th>
        <th class="border px-3 py-2 text-left">Document Type</th>
        <th class="border px-3 py-2 text-left">Purpose</th>
        <th class="border px-3 py-2 text-right">Price</th>
        <th class="border px-3 py-2 text-left">Completed At</th>
    </tr>
</thead>
<tbody>
    <?php $__currentLoopData = $completedRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr class="<?php echo e($loop->even ? 'bg-gray-100' : 'bg-white'); ?> hover:bg-gray-200">
            <td class="border px-3 py-2 text-center">
                <?php echo e($completedRequests->firstItem() ? $completedRequests->firstItem() + $loop->index : $loop->iteration); ?>

            </td>
            <td class="border px-3 py-2"><?php echo e($request->resident->fullname ?? 'N/A'); ?></td>
            <td class="border px-3 py-2"><?php echo e(ucfirst(str_replace('_', ' ', $request->document_type))); ?></td>
            <td class="border px-3 py-2"><?php echo e($request->purpose ?? 'N/A'); ?></td>
            <td class="border px-3 py-2 text-right">
                <?php echo e($request->price ? '₱' . number_format($request->price, 2) : 'N/A'); ?>

            </td>
            <td class="border px-3 py-2">
                <?php echo e($request->completed_at ? \Carbon\Carbon::parse($request->completed_at)->format('F d, Y') : '-'); ?>

            </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="mt-4">
    <?php echo e($completedRequests->links()); ?>

</div>

    </div>
<?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/reports/services.blade.php ENDPATH**/ ?>