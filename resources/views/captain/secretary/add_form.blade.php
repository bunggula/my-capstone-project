{{-- Add Secretary Modal --}}
<div id="addSecretaryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg overflow-y-auto max-h-[90vh] relative">
        <button onclick="toggleAddModal(false)" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl">&times;</button>
        <h2 class="text-lg font-semibold mb-4">Add Secretary</h2>
        <form action="{{ route('captain.secretary.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">First Name</label>
                    <input type="text" name="first_name" class="form-control w-full border px-2 py-1 rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control w-full border px-2 py-1 rounded">
                </div>
                <div>
                    <label class="block font-medium">Last Name</label>
                    <input type="text" name="last_name" class="form-control w-full border px-2 py-1 rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Email</label>
                    <input type="email" name="email" class="form-control w-full border px-2 py-1 rounded" required>
                </div>
                <div>
                    <label class="block font-medium">Gender</label>
                    <select name="gender" class="form-control w-full border px-2 py-1 rounded" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium">Birthday</label>
                    <input type="date" name="birthday" class="form-control w-full border px-2 py-1 rounded" required>
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="toggleAddModal(false)" class="btn btn-secondary px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="btn btn-primary px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Add Secretary</button>
            </div>
        </form>
    </div>
</div>
