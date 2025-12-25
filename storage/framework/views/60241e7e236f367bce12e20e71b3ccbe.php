<!-- resources/views/secretary/residents/create.blade.php -->
<div id="addResidentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-3xl relative overflow-y-auto max-h-screen">

        
        <h3 class="text-2xl font-semibold mb-4 text-gray-800">Add New Resident</h3>

        
        <button onclick="closeModal()" 
                class="absolute top-3 right-3 text-gray-500 hover:text-black text-2xl font-bold">
            &times;
        </button>

        
        <form id="residentForm" action="<?php echo e(route('secretary.residents.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input type="text" name="first_name" placeholder="First Name" 
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="text" name="middle_name" placeholder="Middle Name"
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <input type="text" name="last_name" placeholder="Last Name" 
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div>
                    <input type="text" name="suffix" placeholder="Suffix"
                           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <select name="gender" required
                            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Sex</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>

             <div>
    <input type="date" name="birthdate" placeholder="Birthdate"
           class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
           max="<?php echo e(date('Y-m-d')); ?>"
           required>
</div>


                <div>
                    <select name="civil_status" required
                            class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Civil Status</option>
                        <option>Single</option>
                        <option>Married</option>
                        <option>Widowed</option>
                        <option>Separated</option>
                    </select>
                </div>

                
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-2">Categories:</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="PWD" class="w-5 h-5">
                            PWD
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="Senior" class="w-5 h-5">
                            Senior Citizen
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="Indigenous" class="w-5 h-5">
                            Indigenous People
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="categories[]" value="Single Parent" class="w-5 h-5">
                            Single Parent
                        </label>
                    </div>
                </div>
            </div>

            
            <h3 class="text-xl font-semibold text-gray-700">Contact & Address</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="email" name="email" placeholder="Email Address"
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <input type="text" name="phone" placeholder="Phone Number"
       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
       maxlength="11"
       pattern="\d{11}"
       title="Please enter exactly 11 digits"
       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
<!-- 'required' removed so walk-in residents without phone can be added -->



                <input type="number" name="zone" id="zone" placeholder="Zone"
                       class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" min="1" required>
              <select name="voter"
        class="border rounded-lg px-4 py-3 w-full text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
    <option value="">Voter Status</option>
    <option value="Yes" <?php echo e(request('voter') == 'Yes' ? 'selected' : ''); ?>>Registered</option>
    <option value="No" <?php echo e(request('voter') == 'No' ? 'selected' : ''); ?>>Non-Registered</option>
</select>

            </div>

            
            <div class="flex justify-end gap-4">
                <a href="<?php echo e(route('secretary.residents.index')); ?>"
                   class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Auto-check "Senior Citizen" if age is 60+ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const birthdateInput = document.querySelector('input[name="birthdate"]');
    const seniorCheckbox = document.querySelector('input[type="checkbox"][value="Senior"]');

    function updateSeniorCheckbox() {
        if (!birthdateInput.value) return;

        const birthdate = new Date(birthdateInput.value);
        const today = new Date();

        let age = today.getFullYear() - birthdate.getFullYear();
        const monthDiff = today.getMonth() - birthdate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }

        if (age >= 60) {
            seniorCheckbox.checked = true;
            seniorCheckbox.disabled = true; // lock for seniors
        } else {
            seniorCheckbox.checked = false;
            seniorCheckbox.disabled = true; // cannot check if under 60
        }
    }

    birthdateInput.addEventListener('change', updateSeniorCheckbox);
    updateSeniorCheckbox(); // initialize on page load
});
</script>


<?php if($errors->has('duplicate')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Duplicate Resident',
            text: "<?php echo e($errors->first('duplicate')); ?>",
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    });
</script>
<?php endif; ?>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/residents/create.blade.php ENDPATH**/ ?>