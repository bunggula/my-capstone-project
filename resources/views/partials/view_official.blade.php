<div>
    <h2 class="text-lg font-semibold text-gray-800 mb-2">
        {{ $official->first_name }} {{ $official->middle_name ? strtoupper(substr($official->middle_name, 0, 1)) . '.' : '' }} {{ $official->last_name }}
    </h2>

    <p><strong>Position:</strong> {{ $official->position }}</p>
    <p><strong>Barangay:</strong> {{ $official->barangay->name ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $official->email ?? 'N/A' }}</p>
    <p><strong>Phone:</strong> {{ $official->phone ?? 'N/A' }}</p>

    <p><strong>Gender:</strong> {{ ucfirst($official->gender ?? 'N/A') }}</p>
    <p><strong>Birthday:</strong> {{ $official->birthday ? \Carbon\Carbon::parse($official->birthday)->format('F d, Y') : 'N/A' }}</p>
    <p><strong>Status:</strong> {{ $official->civil_status ?? 'N/A' }}</p>

    @if(!empty($official->categories))
        <div class="mt-3">
            <strong>Categories:</strong>
            <div class="flex flex-wrap gap-1 mt-1">
                @foreach($official->categories as $cat)
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full">
                        {{ $cat }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif
</div>
