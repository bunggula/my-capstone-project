<!-- Modal -->
<div 
    x-cloak 
    x-show="showModal" 
    x-transition 
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    @keydown.escape.window="showModal = false"
>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 relative border border-gray-200 max-h-[90vh] overflow-y-auto">
        
        <!-- Close Button -->
        <button 
            @click="showModal = false" 
            class="absolute top-4 right-5 text-gray-400 hover:text-red-500 text-2xl font-bold transition"
        >
            &times;
        </button>

        <!-- Header -->
        <h2 class="text-2xl font-bold text-blue-800 mb-6 flex items-center gap-2 border-b pb-3">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Resident Details
        </h2>

        <!-- Resident Info -->
        <template x-if="modalData">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <!-- Name -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Name</p>
                    <p class="mt-1 font-semibold text-gray-900" 
                        x-text="modalData.first_name + ' ' + (modalData.middle_name ?? '') + ' ' + modalData.last_name + ' ' + (modalData.suffix ?? '')">
                    </p>
                </div>

                <!-- Gender -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Gender</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.gender"></p>
                </div>

                <!-- Age -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Age</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.age"></p>
                </div>

                <!-- Birthdate -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Birthdate</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.birthdate"></p>
                </div>

                <!-- Civil Status -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Civil Status</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.civil_status"></p>
                </div>

                <!-- Category -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Category</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.category"></p>
                </div>

                <!-- Zone -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Zone</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.zone ?? '-'"></p>
                </div>

                <!-- Phone -->
                <div class="p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Phone</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.phone"></p>
                </div>

                <!-- Email -->
                <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-medium text-xs uppercase">Email</p>
                    <p class="mt-1 font-semibold text-gray-900" x-text="modalData.email ?? '-'"></p>
                </div>

               <!-- Voter Status -->
<div class="md:col-span-2 p-3 bg-gray-50 rounded-lg shadow-sm">
    <p class="text-gray-500 font-medium text-xs uppercase">Voter Status</p>
    <p class="mt-1 font-semibold"
       :class="(modalData.voter && modalData.voter.toLowerCase() === 'yes') ? 'text-green-600' : 'text-red-600'"
       x-text="(modalData.voter && modalData.voter.toLowerCase() === 'yes') ? 'Registered' : 'Not Registered'">
    </p>
</div>


            </div>
        </template>
    </div>
</div>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/partials/resident-modal.blade.php ENDPATH**/ ?>