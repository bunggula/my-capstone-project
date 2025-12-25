

<?php $__env->startSection('title', 'Edit Profile'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $role = Auth::user()->role;

    $partialPrefix = match($role) {
        'admin' => 'abc',
        'secretary' => 'secretary',
        'brgy_captain' => 'captain',
        default => 'abc'
    };
?>

<div class="flex min-h-screen bg-gray-100">

    
    <?php echo $__env->make("partials.{$partialPrefix}-sidebar", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 flex flex-col">

        
        <?php echo $__env->make("partials.{$partialPrefix}-header", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <main class="flex-1 p-6">
            <div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow-lg">

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

                <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Profile</h2>

                
                <?php if(!$user->hasVerifiedEmail()): ?>
                    <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4 text-sm">
                        Your email is not verified.
                        <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="underline text-blue-600 hover:text-blue-800 ml-1">
                                Resend verification email
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                
                <?php if($errors->any()): ?>
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                        <ul class="list-disc ml-4">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <form action="<?php echo e(route('settings.profile.update')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        
                        <div>
                            <label for="first_name" class="block font-semibold mb-1">First Name</label>
                            <input type="text" name="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                        
                        <div>
                            <label for="middle_name" class="block font-semibold mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="<?php echo e(old('middle_name', $user->middle_name)); ?>" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        
                        <div>
                            <label for="last_name" class="block font-semibold mb-1">Last Name</label>
                            <input type="text" name="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                        
                        <div>
                            <label for="suffix" class="block font-semibold mb-1">Suffix</label>
                            <input type="text" name="suffix" value="<?php echo e(old('suffix', $user->suffix)); ?>" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        
                        <div>
                            <label for="email" class="block font-semibold mb-1">Email</label>
                            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                        
                        <div>
                            <label for="gender" class="block font-semibold mb-1">Gender</label>
                            <select name="gender" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                <option value="">-- Select Gender --</option>
                                <option value="male" <?php echo e(old('gender', $user->gender) === 'male' ? 'selected' : ''); ?>>Male</option>
                                <option value="female" <?php echo e(old('gender', $user->gender) === 'female' ? 'selected' : ''); ?>>Female</option>
                            </select>
                        </div>

                        
                        <div>
                            <label for="birthday" class="block font-semibold mb-1">Birthday</label>
                            <input type="date" name="birthday" value="<?php echo e(old('birthday', $user->birthday)); ?>" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        </div>

                    </div>

                    
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-3 rounded-lg transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/settings/profile.blade.php ENDPATH**/ ?>