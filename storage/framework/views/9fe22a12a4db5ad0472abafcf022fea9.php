

<?php $__env->startSection('title', 'Reset Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Set New Password</h2>

    <form method="POST" action="<?php echo e(route('password.update')); ?>" id="resetForm">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="token" value="<?php echo e($token); ?>">

        <div class="mb-4">
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" value="<?php echo e($email ?? old('email')); ?>" required class="w-full border p-2 rounded">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="mb-4 relative">
            <label for="password" class="block font-semibold">New Password</label>
            <input type="password" name="password" id="password" required class="w-full border p-2 rounded pr-10">
            <span onclick="togglePassword('password')" class="absolute right-3 top-9 cursor-pointer text-gray-600">👁️</span>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        
        <div class="mb-4 relative">
            <label for="password_confirmation" class="block font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border p-2 rounded pr-10">
            <span onclick="togglePassword('password_confirmation')" class="absolute right-3 top-9 cursor-pointer text-gray-600">👁️</span>
            <p id="mismatchWarning" class="text-red-600 text-sm mt-1 hidden">Passwords do not match</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
            Reset Password
        </button>
    </form>
</div>


<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

const password = document.getElementById('password');
const confirmPassword = document.getElementById('password_confirmation');
const mismatchWarning = document.getElementById('mismatchWarning');

function checkPasswordMatch() {
    if (confirmPassword.value !== password.value) {
        mismatchWarning.classList.remove('hidden');
    } else {
        mismatchWarning.classList.add('hidden');
    }
}

password.addEventListener('input', checkPasswordMatch);
confirmPassword.addEventListener('input', checkPasswordMatch);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/auth/reset-password.blade.php ENDPATH**/ ?>