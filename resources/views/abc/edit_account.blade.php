<!-- ✅ Success Message -->
@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 rounded border border-green-200">
        {{ session('success') }}
    </div>
@endif


    <h3 class="text-2xl font-semibold mb-6 text-gray-800">Update Account</h3>

    <form method="POST" action="{{ route('abc.update_account', $user->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- First Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                    class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
            </div>

            {{-- Middle Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                    class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>

            {{-- Last Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                    class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
            </div>

            {{-- Suffix --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Suffix</label>
                <input type="text" name="suffix" value="{{ old('suffix', $user->suffix) }}"
                    class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
            </div>

            {{-- Birthday --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Birthday</label>
                <input type="date" name="birthday"
                    value="{{ old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}"
                    class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required />
            </div>

            {{-- Sex --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Sex</label>
                <select name="gender" class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            {{-- Role --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role" name="role" class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="secretary" {{ $user->role === 'secretary' ? 'selected' : '' }}>Secretary</option>
                    <option value="brgy_captain" {{ $user->role === 'brgy_captain' ? 'selected' : '' }}>Barangay Captain</option>
                </select>
            </div>

            {{-- Barangay --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Barangay</label>
                <select name="barangay_id" class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    @foreach($barangays as $brgy)
                        <option value="{{ $brgy->id }}" {{ $user->barangay_id == $brgy->id ? 'selected' : '' }}>
                            {{ $brgy->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Start Year --}}
            <div id="startYearField" style="{{ $user->role === 'brgy_captain' ? '' : 'display:none;' }}">
                <label class="block text-sm font-medium text-gray-700">Start Year</label>
                <select id="start_year" name="start_year" class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" {{ $user->role === 'brgy_captain' ? 'required' : '' }}>
                    <option value="" disabled>-- Select Year --</option>
                    @for ($year = date('Y'); $year >= 2000; $year--)
                        <option value="{{ $year }}" {{ $user->start_year == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- End Year --}}
            <div id="endYearField" style="{{ $user->role === 'brgy_captain' ? '' : 'display:none;' }}">
                <label class="block text-sm font-medium text-gray-700">End Year</label>
                <select id="end_year" name="end_year" class="mt-1 block w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" {{ $user->role === 'brgy_captain' ? 'required' : '' }}>
                    <option value="" disabled>-- Select Year --</option>
                    @for ($year = date('Y'); $year <= date('Y') + 10; $year++)
                        <option value="{{ $year }}" {{ $user->end_year == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

        </div>

        {{-- Submit Button --}}
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Update Account
            </button>
        </div>
    </form>
</div>

<script>
    function toggleYears() {
        const role = document.getElementById('role').value;
        const startField = document.getElementById('startYearField');
        const endField = document.getElementById('endYearField');
        const startYear = document.getElementById('start_year');
        const endYear = document.getElementById('end_year');

        if (role === 'brgy_captain') {
            startField.style.display = 'block';
            endField.style.display = 'block';
            startYear.setAttribute('required', 'required');
            endYear.setAttribute('required', 'required');
        } else {
            startField.style.display = 'none';
            endField.style.display = 'none';
            startYear.removeAttribute('required');
            endYear.removeAttribute('required');
        }
    }

    document.addEventListener("DOMContentLoaded", toggleYears);
    document.getElementById('role').addEventListener('change', toggleYears);
</script>
