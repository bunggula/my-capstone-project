

<?php $__env->startSection('title', 'Residents'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-100 overflow-hidden">
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        <?php echo $__env->make('partials.secretary-header', ['title' => 'Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-7xl mx-auto p-6 bg-white rounded shadow" x-data="{ showModal: false }">
                <h1 class="text-2xl font-bold mb-4">Barangay Officials</h1>


                <?php if(session('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '<?php echo e(session('success')); ?>',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: '<?php echo e(session('error')); ?>',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
<?php endif; ?>
               <!-- Add Official Button aligned right -->
<div class="flex justify-end mb-4">
<!-- Add Official Button on the right -->
<button @click="showModal = true"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
    + Add Official
</button>
</div>




                <!-- Add Official Modal -->
<div x-cloak x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
<div @click.away="showModal = false"
     class="bg-white rounded-2xl w-full max-w-3xl p-8 shadow-xl overflow-y-auto max-h-[90vh] transition-transform transform scale-95 duration-200 ease-out"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 border-b pb-3">
        <h2 class="text-2xl font-semibold text-gray-800">Add Barangay Official</h2>
        <button @click="showModal = false" 
                class="text-gray-400 hover:text-red-600 text-2xl font-bold transition-colors">&times;</button>
    </div>

    <!-- Modal Content -->
    <div class="space-y-6">
        <?php echo $__env->make('secretary.officials.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
</div>


                <!-- Officials Table -->
                 
                <div class="overflow-x-auto">
<table class="w-full table-auto border text-sm text-left">
    <thead class="bg-blue-600 text-white uppercase text-xs">
        <tr>
            <th class="px-4 py-2">Full Name</th>
            <th class="px-4 py-2">Position</th>
        
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        
        <?php if($captain): ?>
            <tr x-data="{ showViewCaptain: false }" class="bg-blue-50">
                <td class="border px-4 py-2 font-semibold">
                    <?php echo e($captain->last_name); ?>, <?php echo e($captain->first_name); ?> <?php echo e($captain->middle_name); ?><?php echo e($captain->suffix ? ' ' . $captain->suffix : ''); ?>

                </td>
                <td class="border px-4 py-2">Barangay Captain</td>
              

                <td class="border px-4 py-2">
                <button @click="showViewCaptain = true"
class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition">
<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>


<div x-show="showViewCaptain" 
 x-transition.opacity
 x-cloak
 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 py-12 px-4">

<div @click.away="showViewCaptain = false"
     class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh] transform transition-all scale-95 duration-200 ease-out"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
        <h2 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Barangay Captain Details
        </h2>
        <button @click="showViewCaptain = false" 
                class="text-gray-400 hover:text-red-600 text-2xl font-bold transition-colors">&times;</button>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 gap-y-6 text-gray-700 text-sm sm:text-base">

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Full Name</p>
            <p class="mt-1 text-gray-900 font-medium">
                <?php echo e($captain->first_name); ?> <?php echo e($captain->middle_name); ?> <?php echo e($captain->last_name); ?><?php echo e($captain->suffix ? ' ' . $captain->suffix : ''); ?>

            </p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Gender</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(ucfirst($captain->gender)); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Birthday</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(\Carbon\Carbon::parse($captain->birthday)->format('F d, Y')); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Age</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(\Carbon\Carbon::parse($captain->birthday)->age); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm sm:col-span-2">
            <p class="text-gray-500 font-semibold uppercase text-xs">Email</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($captain->email ?? '-'); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Start Year</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($captain->start_year ?? '-'); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">End Year</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($captain->end_year ?? '-'); ?></p>
        </div>

    </div>

</div>
</div>


                </td>
            </tr>
        <?php endif; ?>

        
        <?php if($secretary): ?>
            <tr x-data="{ showViewSecretary: false }" class="bg-green-50">
                <td class="border px-4 py-2 font-semibold">
                    <?php echo e($secretary->last_name); ?>, <?php echo e($secretary->first_name); ?> <?php echo e($secretary->middle_name); ?><?php echo e($secretary->suffix ? ' ' . $secretary->suffix : ''); ?>

                </td>
                <td class="border px-4 py-2">Barangay Secretary</td>
             
                <td class="border px-4 py-2">
                <button @click="showViewSecretary = true"
                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>


<div x-show="showViewSecretary" 
 x-transition.opacity 
 x-cloak
 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 py-12 px-4">

<div @click.away="showViewSecretary = false"
     class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh] transform transition-all scale-95 duration-200 ease-out"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
        <h2 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Barangay Secretary Details
        </h2>
        <button @click="showViewSecretary = false" 
                class="text-gray-400 hover:text-red-600 text-2xl font-bold transition-colors">&times;</button>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 gap-y-6 text-gray-700 text-sm sm:text-base">

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Name</p>
            <p class="mt-1 text-gray-900 font-medium">
                <?php echo e($secretary->first_name); ?> <?php echo e($secretary->middle_name); ?> <?php echo e($secretary->last_name); ?><?php echo e($secretary->suffix ? ' ' . $secretary->suffix : ''); ?>

            </p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Sex</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(ucfirst($secretary->gender)); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Birthday</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(\Carbon\Carbon::parse($secretary->birthday)->format('F d, Y')); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Age</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(\Carbon\Carbon::parse($secretary->birthday)->age); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm sm:col-span-2">
            <p class="text-gray-500 font-semibold uppercase text-xs">Email</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($secretary->email ?? '-'); ?></p>
        </div>

    </div>

</div>
</div>


                </td>
            </tr>
        <?php endif; ?>

        
        <?php $__empty_1 = true; $__currentLoopData = $officials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $official): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr x-data="{ showView<?php echo e($official->id); ?>: false, showEdit<?php echo e($official->id); ?>: false }" class="bg-white">
                <td class="border px-4 py-2">
                    <?php echo e($official->last_name); ?>, <?php echo e($official->first_name); ?> <?php echo e($official->middle_name); ?><?php echo e($official->suffix ? ' ' . $official->suffix : ''); ?>

                </td>
                <td class="border px-4 py-2"><?php echo e($official->position); ?></td>
               

      
        <td class="border px-4 py-2">
        <!-- View Icon Button -->
<button @click="showView<?php echo e($official->id); ?> = true"
    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>

<!-- Edit Icon Button -->
<button @click="showEdit<?php echo e($official->id); ?> = true"
    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-lg">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
    </svg>
</button>


       <!-- View Modal -->
<div x-show="showView<?php echo e($official->id); ?>" 
 x-transition.opacity
 x-cloak
 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 py-12 px-4">

<div @click.away="showView<?php echo e($official->id); ?> = false"
     class="bg-white rounded-3xl w-full max-w-lg p-8 shadow-2xl overflow-y-auto max-h-[90vh] transform transition-all scale-95 duration-200 ease-out"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
        <h2 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Barangay Officials Details
        </h2>
        <button @click="showView<?php echo e($official->id); ?> = false"
                class="text-gray-400 hover:text-red-600 text-2xl font-bold transition-colors">&times;</button>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 gap-y-6 text-gray-700 text-sm sm:text-base">

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Full Name</p>
            <p class="mt-1 text-gray-900 font-medium">
                <?php echo e($official->first_name); ?> <?php echo e($official->middle_name); ?> <?php echo e($official->last_name); ?><?php echo e($official->suffix ? ' ' . $official->suffix : ''); ?>

            </p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Sex</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(ucfirst($official->gender)); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Birthday</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e(\Carbon\Carbon::parse($official->birthday)->format('F d, Y')); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Age</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($official->age); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Status</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($official->civil_status ?? '-'); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Phone Number</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($official->phone ?? '-'); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Email</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($official->email ?? '-'); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Position</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($official->position); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Year Start</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($official->start_year ?? '-'); ?></p>
        </div>

        <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 font-semibold uppercase text-xs">Year End</p>
            <p class="mt-1 text-gray-900 font-medium"><?php echo e($official->end_year ?? '-'); ?></p>
        </div>

    </div>

</div>
</div>

<!-- Edit Official Modal -->
<div x-cloak x-show="showEdit<?php echo e($official->id); ?>" 
 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
<div @click.away="showEdit<?php echo e($official->id); ?> = false"
     class="bg-white rounded-3xl w-full max-w-3xl p-8 shadow-2xl overflow-y-auto max-h-[85vh]">

    <!-- Header -->
    <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Edit Official</h2>
        <button @click="showEdit<?php echo e($official->id); ?> = false" 
                class="text-gray-400 hover:text-red-600 text-4xl font-bold transition-colors">&times;</button>
    </div>

    <!-- Form -->
    <form action="<?php echo e(route('secretary.officials.update', $official->id)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <input type="text" name="first_name" value="<?php echo e($official->first_name); ?>" placeholder="First Name" required
                   class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <input type="text" name="middle_name" value="<?php echo e($official->middle_name ?? ''); ?>" placeholder="Middle Name"
                   class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <input type="text" name="last_name" value="<?php echo e($official->last_name); ?>" placeholder="Last Name" required
                   class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <input type="text" name="suffix" value="<?php echo e($official->suffix ?? ''); ?>" placeholder="Suffix"
                   class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Gender & Birthday -->
        <?php $gender = ucfirst(strtolower(trim($official->gender))); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <select name="gender" required class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="">-- Select Gender --</option>
                <option value="Male" <?php echo e($gender == 'Male' ? 'selected' : ''); ?>>Male</option>
                <option value="Female" <?php echo e($gender == 'Female' ? 'selected' : ''); ?>>Female</option>
            </select>

            <input type="date" name="birthday" id="editBirthdate<?php echo e($official->id); ?>" value="<?php echo e($official->birthday->format('Y-m-d')); ?>" required
                   class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <!-- Civil Status, Phone & Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <select name="civil_status" required class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="">-- Select Civil Status --</option>
                <option value="Single" <?php echo e($official->civil_status == 'Single' ? 'selected' : ''); ?>>Single</option>
                <option value="Married" <?php echo e($official->civil_status == 'Married' ? 'selected' : ''); ?>>Married</option>
                <option value="Widow" <?php echo e($official->civil_status == 'Widow' ? 'selected' : ''); ?>>Widow</option>
                <option value="Separated" <?php echo e($official->civil_status == 'Separated' ? 'selected' : ''); ?>>Separated</option>
            </select>

            <input type="text" name="phone" value="<?php echo e($official->phone); ?>" placeholder="Phone Number" maxlength="11"
                   class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

            <input type="email" name="email" value="<?php echo e($official->email); ?>" placeholder="Email Address"
                   class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none md:col-span-2">
        </div>

  <!-- Position & Years -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
    <select name="position" id="editPosition<?php echo e($official->id); ?>" required
            class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        <option value="">Select Position</option>
        <option value="Kagawad" <?php echo e($official->position == 'Kagawad' ? 'selected' : ''); ?>>Kagawad</option>
        <option value="Vaw Desk Officer" <?php echo e($official->position == 'Vaw Desk Officer' ? 'selected' : ''); ?>>Vaw Desk Officer</option>
        <option value="Barangay Treasurer" <?php echo e($official->position == 'Barangay Treasurer' ? 'selected' : ''); ?>>Barangay Treasurer</option>
        <option value="SK Chairman" <?php echo e($official->position == 'SK Chairman' ? 'selected' : ''); ?>>SK Chairman</option>
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Start Year</label>
    <select name="start_year" id="startYearDiv<?php echo e($official->id); ?>"
            class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        <?php for($y = date('Y'); $y >= 2000; $y--): ?>
            <option value="<?php echo e($y); ?>" <?php echo e($official->start_year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
        <?php endfor; ?>
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">End Year</label>
    <select name="end_year" id="endYearDiv<?php echo e($official->id); ?>"
            class="border rounded-xl px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        <?php for($y = date('Y') - 6; $y <= date('Y') + 6; $y++): ?>
            <option value="<?php echo e($y); ?>" <?php echo e($official->end_year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
        <?php endfor; ?>
    </select>
</div>
</div>


        <!-- Categories -->
        <div>
            <label class="block font-medium mb-2">Categories</label>
            <div class="flex flex-wrap gap-4">
                <?php $savedCategories = $official->categories ?? []; ?>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="categories[]" value="PWD" <?php echo e(in_array('PWD', $savedCategories) ? 'checked' : ''); ?>>
                    PWD
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" id="editSenior<?php echo e($official->id); ?>" name="categories[]" value="Senior" <?php echo e(in_array('Senior', $savedCategories) ? 'checked' : ''); ?>>
                    Senior
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="categories[]" value="Indigenous" <?php echo e(in_array('Indigenous', $savedCategories) ? 'checked' : ''); ?>>
                    Indigenous People
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="categories[]" value="Solo Parent" <?php echo e(in_array('Solo Parent', $savedCategories) ? 'checked' : ''); ?>>
                    Solo Parent
                </label>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-right">
            <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors">
                Update Official
            </button>
        </div>
    </form>
</div>
</div>
<style>
[x-cloak] {
display: none !important;
visibility: hidden !important;
opacity: 0 !important;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
const positionSelect = document.getElementById('editPosition<?php echo e($official->id); ?>');
const startYearDiv = document.getElementById('startYearDiv<?php echo e($official->id); ?>');
const endYearDiv = document.getElementById('endYearDiv<?php echo e($official->id); ?>');

function toggleYearFields() {
    const pos = positionSelect.value;
    const shouldEnable = pos === 'Kagawad' || pos === 'SK Chairman';

    startYearDiv.disabled = !shouldEnable;
    endYearDiv.disabled = !shouldEnable;
}

toggleYearFields();
positionSelect.addEventListener('change', toggleYearFields);

// Birthday → Senior checkbox logic
const birthInput = document.getElementById('editBirthdate<?php echo e($official->id); ?>');
const seniorCheckbox = document.getElementById('editSenior<?php echo e($official->id); ?>');

function computeAge(dateString) {
    if (!dateString) return 0;
    const birthDate = new Date(dateString);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
    return age;
}

function updateSeniorCheckbox() {
    const age = computeAge(birthInput.value);

    if (age >= 60) {
        seniorCheckbox.checked = true;
        seniorCheckbox.disabled = true; // lock for seniors
    } else {
        seniorCheckbox.checked = false;
        seniorCheckbox.disabled = true; // allow edit if not senior
    }
}

birthInput.addEventListener('change', updateSeniorCheckbox);
updateSeniorCheckbox(); // initialize on modal open
});
</script>




                </div>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="6" class="text-center py-4">No officials found.</td>
    </tr>
<?php endif; ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/officials/index.blade.php ENDPATH**/ ?>