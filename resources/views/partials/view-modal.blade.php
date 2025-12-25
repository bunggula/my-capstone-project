<div class="p-4">
    <h3 class="text-lg font-semibold mb-2">👤 Resident Information</h3>
    <p><strong>Name:</strong> {{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</p>
    <p><strong>Birthdate:</strong> {{ $resident->birthdate }}</p>
    <p><strong>Gender:</strong> {{ $resident->gender }}</p>
    <p><strong>Civil Status:</strong> {{ $resident->civil_status }}</p>
    <p><strong>Email:</strong> {{ $resident->email ?? 'N/A' }}</p>
    <p><strong>Phone:</strong> {{ $resident->phone ?? 'N/A' }}</p>
    <p><strong>Address:</strong> {{ $resident->address }}</p>
    <p><strong>Status:</strong> {{ ucfirst($resident->status) }}</p>
</div>
