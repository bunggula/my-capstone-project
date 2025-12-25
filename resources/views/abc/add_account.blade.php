{{-- Success message --}}
@if (session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded font-semibold border border-green-400 text-center">
        {{ session('success') }}
    </div>
@endif


    <h3 class="text-2xl font-semibold mb-6 text-gray-800">Create Account</h3>

    <form method="POST" action="{{ route('store.account') }}" autocomplete="off" 
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @csrf

        <input type="text" name="fake_email" style="display:none">
        <input type="password" name="fake_password" style="display:none">

        {{-- Name Fields --}}
        <div>
            <label for="first_name" class="block font-medium mb-1">First Name</label>
            <input type="text" id="first_name" name="first_name" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" />
        </div>

        <div>
            <label for="middle_name" class="block font-medium mb-1">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" />
        </div>

        <div>
            <label for="last_name" class="block font-medium mb-1">Last Name</label>
            <input type="text" id="last_name" name="last_name" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" />
        </div>

        <div>
            <label for="suffix" class="block font-medium mb-1">Suffix</label>
            <input type="text" id="suffix" name="suffix" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" />
        </div>

        {{-- Barangay --}}
        <div>
            <label for="barangay" class="block font-medium mb-1">Barangay</label>
            <select id="barangay" name="barangay_id" required 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                <option value="" disabled selected>-- Select Barangay --</option>
                @foreach ($barangays as $barangay)
                    <option value="{{ $barangay->id }}">{{ $barangay->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Role --}}
       <div>
    <label for="role" class="block font-medium mb-1">Role</label>
    <select id="role" name="role" required 
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" 
        onchange="toggleYears()">

        <option value="" disabled>-- Select Role --</option>
        <option value="brgy_captain" selected>Barangay Captain</option>
    </select>
</div>


        {{-- Email --}}
        <div>
            <label for="email" class="block font-medium mb-1">Email</label>
            <input type="email" id="email" name="email" required autocomplete="off" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" />
        </div>

       

        {{-- Birthday --}}
        <div>
            <label for="birthday" class="block font-medium mb-1">Birthday</label>
            <input type="date" id="birthday" name="birthday" required 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50" />
        </div>

        {{-- Gender --}}
        <div>
            <label for="gender" class="block font-medium mb-1">Sex</label>
            <select id="gender" name="gender" required 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                <option value="" disabled selected>-- Select Gender --</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>

        {{-- Start Year --}}
        <div id="startYearField" style="display:none;">
            <label for="start_year" class="block font-medium mb-1">Start Year</label>
            <select id="start_year" name="start_year" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                <option value="" disabled>-- Select Year --</option>
                @for ($y = date('Y'); $y >= 2000; $y--)
                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- End Year --}}
        <div id="endYearField" style="display:none;">
            <label for="end_year" class="block font-medium mb-1">End Year</label>
            <select id="end_year" name="end_year" 
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50">
                <option value="" disabled>-- Select Year --</option>
                @for ($y = date('Y') + 6; $y >= 2000; $y--)
                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- Submit --}}
        <div class="col-span-full pt-4">
            <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg mx-auto block w-48">
                Create Account
            </button>
        </div>
    </form>
</div>

{{-- Scripts --}}
<script>
    function generatePassword(length = 10) {
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
        let password = '';
        for (let i = 0; i < length; i++) {
            password += charset[Math.floor(Math.random() * charset.length)];
        }
        document.getElementById('password').value = password;
    }

    function toggleYears() {
        const role = document.getElementById('role').value;
        const startField = document.getElementById('startYearField');
        const endField = document.getElementById('endYearField');

        if (role === 'brgy_captain') {
            startField.style.display = 'block';
            endField.style.display = 'block';
        } else {
            startField.style.display = 'none';
            endField.style.display = 'none';
        }
    }
    toggleYears();

</script>
