<!-- resources/views/services/view.blade.php -->
<div 
    x-show="modalOpen" 
    x-cloak 
    class="fixed inset-0 z-50 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center p-4"
>
    <div 
        @click.away="modalOpen = false" 
        class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-8 relative border border-gray-200 transform transition-all"
    >
        <!-- Close Button -->
        <button 
            @click="modalOpen = false" 
            class="absolute top-4 right-4 text-gray-500 hover:text-red-500 transition text-3xl font-bold leading-none"
            title="Close"
        >
            &times;
        </button>

        <!-- Modal Header -->
        <div class="mb-6 border-b pb-4 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-2xl font-bold text-blue-800">Document Request Details</h2>
        </div>

        <!-- Modal Content -->
        <template x-if="modalData">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700 text-sm sm:text-base">
                
                <!-- Reference Code -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-semibold">Reference Code</p>
                    <p x-text="modalData.reference_code" class="text-base font-bold text-gray-900"></p>
                </div>

                <!-- Resident Name -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-semibold">Resident Name</p>
                    <p x-text="modalData.resident_name" class="text-base font-bold text-gray-900"></p>
                </div>

                <!-- Barangay -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-semibold">Barangay</p>
                    <p x-text="modalData.barangay" class="text-base font-bold text-gray-900"></p>
                </div>

                <!-- Document Type -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-semibold">Document Type</p>
                    <p x-text="modalData.document_type" class="text-base font-bold text-gray-900"></p>
                </div>

                <!-- Purpose -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-semibold">Purpose</p>
                    <p x-text="modalData.purpose" class="text-base font-bold text-gray-900"></p>
                </div>
<!-- Price -->
<div class="p-4 bg-gray-50 rounded-lg shadow-sm">
    <p class="text-gray-500 font-semibold">Price</p>
    <p class="text-lg font-extrabold text-green-600 flex items-center">
        ₱<span x-text="parseFloat(modalData.price || 0).toFixed(2)"></span>
        
        <!-- Badge -->
        <span 
            x-show="modalData.status === 'completed'" 
            class="ml-2 text-sm font-bold bg-green-600 text-white px-2 py-1 rounded">
            Paid
        </span>
        
       
    </p>
</div>


                <!-- Status -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                    <p class="text-gray-500 font-semibold">Status</p>
                    <span 
                        x-text="modalData.status" 
                        class="inline-block px-3 py-1 rounded-full text-sm font-medium mt-1 capitalize"
                        :class="{
                            'bg-yellow-100 text-yellow-800': modalData.status === 'pending',
                            'bg-green-100 text-green-800': modalData.status === 'approved',
                            'bg-blue-100 text-blue-800': modalData.status === 'completed',
                            'bg-gray-400 text-black-100': modalData.status === 'ready_for_pickup',
                            'bg-gray-100 text-gray-700': !['pending', 'approved', 'completed', 'ready_for_pickup'].includes(modalData.status)
                        }"
                    ></span>
                </div>

                <!-- Requested At -->
                <div class="p-4 bg-gray-50 rounded-lg shadow-sm col-span-2">
                    <p class="text-gray-500 font-semibold">Requested On</p>
                    <p x-text="modalData.created_at" class="text-base font-bold text-gray-900"></p>
                </div>

            </div>
        </template>
    </div>
</div>
