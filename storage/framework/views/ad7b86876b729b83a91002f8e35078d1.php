<div class="space-y-6 p-6 bg-white rounded-2xl shadow-lg max-w-3xl mx-auto">
    <!-- Header -->
    <h3 class="text-2xl font-bold text-blue-800 flex items-center gap-2 border-b pb-3 mb-6">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        Account Details
    </h3>

    <!-- Grid Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php
    // Determine if it's a user or an official
    $data = $user ?? $official; // $user for users, $official for officials
    $isUser = isset($user);

    $fields = [
        'First Name' => $data->first_name,
        'Middle Name' => $data->middle_name ?? '—',
        'Last Name' => $data->last_name,
        'Suffix' => $data->suffix ?? '—',
        'Email' => $data->email,
        'Birthday' => $isUser && $data->birthday ? \Carbon\Carbon::parse($data->birthday)->toFormattedDateString() : '—',
        'Gender' => $isUser ? ucfirst($data->gender) : '—',
        'Role' => $isUser 
                    ? ucfirst(str_replace('_', ' ', $data->role)) 
                    : $data->position,
        'Barangay' => $isUser ? ($data->barangay->name ?? 'N/A') : ($data->barangay_name ?? 'N/A'),
    ];

    if($isUser && $data->role === 'brgy_captain') {
        $fields['Start Year'] = $data->start_year;
        $fields['End Year'] = $data->end_year;
    }
?>


        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs"><?php echo e($label); ?></p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($value); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/view_account.blade.php ENDPATH**/ ?>