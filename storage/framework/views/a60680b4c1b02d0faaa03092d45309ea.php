<!-- WRAPPER FOR ALPINEJS -->
<div x-data="{ openSecretaryModal: false }">

    
    <?php if (! ($secretary)): ?>
    <div class="mb-4 text-right">
        <button 
            @click="openSecretaryModal = true"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Add Secretary
        </button>
    </div>
    <?php endif; ?>


    <!-- YOUR EXISTING FORM (UNCHANGED) -->
    <form action="<?php echo e(route('secretary.barangay-info.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label class="font-semibold block mb-1">Barangay Name</label>
                <input type="text" name="name" 
                       value="<?php echo e(old('name', $barangay->name)); ?>"
                       class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            
            <div>
                <label class="font-semibold block mb-1">Chairperson Name</label>
                <input type="text" name="chairperson" 
                       value="<?php echo e(old('chairperson', $barangay->chairperson ?? ($chairperson->full_name ?? ''))); ?>"
                       class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            
            <div>
                <label class="font-semibold block mb-1">Secretary Name</label>
                <input type="text" name="secretary" 
                       value="<?php echo e(old('secretary', $barangay->secretary ?? ($secretary->full_name ?? ''))); ?>"
                       class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            
            <div>
                <label class="font-semibold block mb-1">Contact Number</label>
                <input type="text" name="contact_number"
                       value="<?php echo e(old('contact_number', $barangay->contact_number)); ?>"
                       class="w-full border px-3 py-2 rounded">
            </div>

            
            <div>
                <label class="font-semibold block mb-1">Email Address</label>
                <input type="email" name="email"
                       value="<?php echo e(old('email', auth()->user()->email)); ?>"
                       class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
            </div>

            
            <div>
                <label class="font-semibold block mb-1">Office Hours</label>
                <input type="text" name="office_hours"
                       value="<?php echo e(old('office_hours', $barangay->office_hours)); ?>"
                       class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        
        <div class="mt-6">
            <label class="font-semibold block mb-1">Barangay Logo</label>
            <input type="file" name="logo" class="w-full">
            <?php if($barangay->logo): ?>
                <img src="<?php echo e(asset('storage/' . $barangay->logo)); ?>" class="h-20 mt-2">
            <?php endif; ?>
        </div>

        <div class="mt-8 text-right">
            <button type="submit" 
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Update Info
            </button>
        </div>
    </form>


    <!-- ========================== -->
    <!--      ADD SECRETARY MODAL   -->
    <!-- ========================== -->
    <div 
        x-show="openSecretaryModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-cloak
    >
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

            <h2 class="text-xl font-semibold mb-4">Add Barangay Secretary</h2>

            <form action="<?php echo e(route('captain.secretary.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">First Name</label>
                    <input type="text" name="first_name"
                           class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Middle Name</label>
                    <input type="text" name="middle_name"
                           class="w-full border px-3 py-2 rounded">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Last Name</label>
                    <input type="text" name="last_name"
                           class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email"
                           class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Gender</label>
                    <select name="gender" 
                            class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select…</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Birthday</label>
                    <input type="date" name="birthday"
                           class="w-full border px-3 py-2 rounded" required>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button"
                        @click="openSecretaryModal = false"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Add Secretary
                    </button>
                </div>

            </form>

        </div>
    </div>

</div> <!-- END ALPINE WRAPPER -->
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/barangay-info/edit.blade.php ENDPATH**/ ?>