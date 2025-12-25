<?php
    $inputBase = 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 placeholder-gray-400';
?>

<div 
    x-data="{
        birthdate: '',
        selectedCategories: [],
        checkSenior() {
            if (!this.birthdate) return;

            const bday = new Date(this.birthdate);
            const today = new Date();
            let age = today.getFullYear() - bday.getFullYear();
            const m = today.getMonth() - bday.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < bday.getDate())) {
                age--;
            }

            if (age >= 60 && !this.selectedCategories.includes('Senior Citizen')) {
                this.selectedCategories.push('Senior Citizen');
            } else if (age < 60) {
                this.selectedCategories = this.selectedCategories.filter(cat => cat !== 'Senior Citizen');
            }
        }
    }" 
    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
>
    
    <div>
        <label class="block text-sm font-medium text-gray-700">First Name</label>
        <input type="text" name="first_name" class="<?php echo e($inputBase); ?>" placeholder="Juan" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Middle Name</label>
        <input type="text" name="middle_name" class="<?php echo e($inputBase); ?>" placeholder="Dela Cruz">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Last Name</label>
        <input type="text" name="last_name" class="<?php echo e($inputBase); ?>" placeholder="Santos" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Suffix</label>
        <input type="text" name="suffix" class="<?php echo e($inputBase); ?>" placeholder="Jr., III, etc.">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Birthdate</label>
        <input 
            type="date" 
            name="birthdate" 
            x-model="birthdate" 
            @change="checkSenior"
            class="<?php echo e($inputBase); ?>" 
            required
        >
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Gender</label>
        <select name="gender" class="<?php echo e($inputBase); ?>" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Civil Status</label>
        <select name="civil_status" class="<?php echo e($inputBase); ?>" required>
            <option value="">Select Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Separated">Separated</option>
            <option value="Divorced">Divorced</option>
        </select>
    </div>

    
    <div class="col-span-1 md:col-span-2 lg:col-span-3">
        <label class="block text-sm font-medium text-gray-700">Category</label>
        <div class="flex flex-wrap gap-4 mt-2">
            <template x-for="category in ['PWD', 'Senior Citizen', 'Indigent', 'Single Parent', 'None']" :key="category">
                <label class="inline-flex items-center space-x-2">
                    <input 
                        type="checkbox" 
                        :value="category" 
                        x-model="selectedCategories"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    >
                    <span x-text="category" class="text-gray-700"></span>
                </label>
            </template>
        </div>

        <!-- This hidden input submits the selected categories as comma-separated string -->
        <input type="hidden" name="category" :value="selectedCategories.join(',')">
    </div>

    
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" class="<?php echo e($inputBase); ?>" placeholder="juan@example.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="text" name="phone" class="<?php echo e($inputBase); ?>" placeholder="09XXXXXXXXX">
    </div>

    
    <div>
        <label class="block text-sm font-medium text-gray-700">Barangay</label>
        <select name="barangay_id" class="<?php echo e($inputBase); ?>" required>
            <option value="">Select Barangay</option>
            <?php $__currentLoopData = $barangays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barangay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($barangay->id); ?>"><?php echo e($barangay->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-span-1 md:col-span-2 lg:col-span-3">
        <label class="block text-sm font-medium text-gray-700">Address</label>
        <textarea name="address" rows="3"
                  class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 resize-none placeholder-gray-400"
                  placeholder="Street, Purok, Sitio..." required></textarea>
    </div>

    
    <input type="hidden" name="status" value="approved">
</div>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/abc/residents/_form.blade.php ENDPATH**/ ?>