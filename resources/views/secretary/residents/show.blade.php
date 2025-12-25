<div id="viewResidentModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-xl relative">
        <button onclick="closeViewModal()" class="absolute top-2 right-4 text-gray-500 hover:text-black text-2xl">&times;</button>
        <h2 class="text-xl font-bold text-center mb-4">Resident Details</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div><strong>Full Name:</strong> <span id="viewFullName"></span></div>
            <div><strong>Gender:</strong> <span id="viewGender"></span></div>
            <div><strong>Birthdate:</strong> <span id="viewBirthdate"></span></div>
            <div><strong>Age:</strong> <span id="viewAge"></span></div>
            <div><strong>Civil Status:</strong> <span id="viewCivilStatus"></span></div>
            <div><strong>Category:</strong> <span id="viewCategory"></span></div>
            <div><strong>Email:</strong> <span id="viewEmail"></span></div>
            <div><strong>Phone:</strong> <span id="viewPhone"></span></div>
            <div class="sm:col-span-2"><strong>Address:</strong> <span id="viewAddress"></span></div>
        </div>
    </div>
</div>
