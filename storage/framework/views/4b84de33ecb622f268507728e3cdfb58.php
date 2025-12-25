<form action="<?php echo e(route('secretary.officials.store')); ?>" method="POST" x-data="officialForm()" @submit.prevent="validateAndSubmit($event)">
    <?php echo csrf_field(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
     <!-- Name Fields -->
<input type="text" name="first_name" placeholder="First Name" required
       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
<input type="text" name="middle_name" placeholder="Middle Name"
       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
<input type="text" name="last_name" placeholder="Last Name" required
       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
<input type="text" name="suffix" placeholder="Suffix"
       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">



        <!-- Birthday + Age -->
        <div>
            <label class="block text-sm font-medium mb-1">Birthday</label>
            <input type="date" name="birthday" x-model="birthday" @change="calculateAge" required
                   class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Age</label>
            <input type="text" x-model="age" readonly
                   class="border rounded-lg px-4 py-3 w-full text-sm bg-gray-100 focus:outline-none">
        </div>

        <!-- Civil Status -->
        <select name="civil_status" required
                class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Civil Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widow">Widow</option>
            <option value="Separated">Separated</option>
        </select>
<!-- Gender -->
<select name="gender" required
class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
    <option value="">Sex</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
</select>

        <!-- Phone -->
        <input type="text" name="phone" placeholder="Phone Number" maxlength="11" required
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

        <!-- Email -->
        <input type="email" name="email" placeholder="Email Address"
               class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">

        <!-- Position -->
        <select name="position" x-model="position" required
                class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">Select Position</option>
            <option value="Kagawad">Kagawad</option>
            <option value="Vaw Desk Officer">Vaw Desk Officer</option>
            <option value="Barangay Tanod">Barangay Tanod</option>
            <option value="SK Chairman">SK Chairman</option>
        </select>

        <!-- Start Year -->
        <div x-show="position === 'Kagawad' || position === 'SK Chairman'">
            <label class="block text-sm font-medium mb-1">Start Year</label>
            <select name="start_year"
                    class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <?php for($year = date('Y'); $year >= 2000; $year--): ?>
                    <option value="<?php echo e($year); ?>" <?php echo e($year == date('Y') ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- End Year -->
        <div x-show="position === 'Kagawad' || position === 'SK Chairman'">
            <label class="block text-sm font-medium mb-1">End Year</label>
            <select name="end_year"
                    class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                <?php for($year = date('Y'); $year <= date('Y') + 6; $year++): ?>
                    <option value="<?php echo e($year); ?>" <?php echo e($year == date('Y') ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Categories -->
        <div class="col-span-2">
            <label class="block font-medium mb-2">Categories:</label>
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="categories[]" value="PWD" class="w-5 h-5">
                    PWD
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="categories[]" value="Senior" x-model="isSenior" class="w-5 h-5">
                    Senior
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="categories[]" value="IP" class="w-5 h-5">
                    Indigenous People
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="categories[]" value="Solo Parent" class="w-5 h-5">
                    Solo Parent
                </label>
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="mt-6 text-right">
        <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
            Save
        </button>
    </div>
</form>

<script>
function officialForm() {
    return {
        birthday: '',
        age: '',
        position: '',
        isSenior: false, // <-- auto check ng Senior

        calculateAge() {
            if (!this.birthday) return;

            const birthDate = new Date(this.birthday);
            const today = new Date();
            let computedAge = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                computedAge--;
            }

            this.age = computedAge;
            this.isSenior = computedAge >= 60; // <-- auto check Senior
        },

        validateAndSubmit(e) {
            const phone = e.target.querySelector('input[name="phone"]');
            if (phone.value.length !== 11) {
                alert('Phone number must be exactly 11 digits.');
                phone.focus();
                return false;
            }

            e.target.submit();
        }
    }
}

</script>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/officials/create.blade.php ENDPATH**/ ?>