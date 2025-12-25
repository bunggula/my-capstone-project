<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Residents Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2, h3, p { margin: 2px 0; }

        /* Header */
        .header { display: flex; align-items: center; justify-content: space-between; gap: 15px; line-height: 1.4; margin-bottom: 20px; }
        .header img { width: 80px; height: 80px; object-fit: contain; }
        .header-center { text-align: center; flex:1; }

        /* Filter info */
        .info { text-align: left; font-weight: 600; margin-top: 20px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; font-size: 14px; }
        th { background-color: rgb(173,176,180); color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        td.name, td.bday { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Footer */
        .footer { margin-top: 40px; font-size: 14px; color: #333; }
        .footer .prepared { margin-top: 60px; text-align: left; }

        /* Print */
        @media print {
            #print-btn { display: none !important; }
            body { margin: 0; }
        }

        /* Print button */
        #print-btn {
            display:inline-block; background:#16a34a; color:#fff; padding:8px 16px; border-radius:6px; text-decoration:none; margin-top:10px;
        }
        #print-btn:hover { background:#15803d; }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header">
    {{-- LEFT: Municipality Logo --}}
    @if($barangay?->municipality?->logo)
        <img src="{{ asset('storage/'.$barangay->municipality->logo) }}" alt="Municipality Logo">
    @else
        <div style="width:80px;"></div>
    @endif

    {{-- CENTER: Text --}}
    <div class="header-center">
        <h2>Republic of the Philippines</h2>
        <h3>Province of {{ $barangay?->municipality?->province ?? 'Pangasinan' }}</h3>
        <h3>Municipality of {{ $barangay?->municipality?->name ?? 'Laoac' }}</h3>
        <h3><strong>Barangay {{ $barangay?->name ?? 'N/A' }}</strong></h3>
    </div>

    {{-- RIGHT: Barangay Logo --}}
    @if($barangay?->logo)
        <img src="{{ asset('storage/'.$barangay->logo) }}" alt="Barangay Logo">
    @else
        <div style="width:80px;"></div>
    @endif
</div>
{{-- Print button --}}
<a href="#" id="print-btn" onclick="window.print()" style="display:inline-block;background:#16a34a;color:#fff;padding:8px 16px;border-radius:6px;text-decoration:none;margin-top:10px;">
    Print
</a>
@php
$categoryLabels = [
    'PWD' => 'Person With Disability',
    'Senior' => 'Senior Citizen',
    'Indigenous' => 'Indigenous People',
    'Single Parent' => 'Single Parent',
    'Minor' => 'Minor (Below 18)',
    'Adult' => 'Adult (18+)',
];

$voterLabels = [
    'Yes' => 'Registered',
    'No' => 'Non-Registered',
];

$categoryDisplay = $categoryLabels[$selectedCategory] ?? null;
$voterDisplay = $voterLabels[$selectedVoter] ?? null;

$filters = [];
if ($categoryDisplay) $filters[] = $categoryDisplay;
if ($voterDisplay) $filters[] = $voterDisplay;
$filtersText = count($filters) ? implode(' | ', $filters) : 'All Residents';
@endphp

{{-- Filter summary --}}
<p class="info">List of Residents of Barangay {{ $barangayName ?? 'All Barangays' }}{{ count($filters) ? ' | '.$filtersText : '' }}</p>



{{-- Table --}}
@if($residents->count())
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                
                <th>Category</th>
                <th>Voter</th>
            </tr>
        </thead>
        <tbody>
            @foreach($residents as $index => $resident)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="name">{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</td>
                    <td>{{ ucfirst($resident->gender) }}</td>
                    <td class="bday">{{ \Carbon\Carbon::parse($resident->birthdate)->format('M d, Y') }}</td>
                   
                    <td>{{ $resident->category ?? 'N/A' }}</td>
                    <td>{{ $resident->voter == 'Yes' ? 'Registered' : 'Non-Registered' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Footer --}}
<div class="footer">
    <p style="text-align:right;"><strong>Total Residents:</strong> {{ $residents->count() }}</p>
    <p style="text-align:right;"><strong>Printed on:</strong> {{ now()->format('F d, Y h:i A') }}</p>

    @php
        $user = Auth::user();
        $fullName = trim("{$user->first_name} {$user->middle_name} {$user->last_name}");
    @endphp

    <div class="prepared" style="text-align:left; margin-top:60px;">
        <p><strong>Prepared by:</strong> {{ $fullName ?: '__________________' }}</p>
        <p>{{ $user->role == 'secretary' ? 'Barangay Secretary' : 'User' }}</p>
    </div>
</div>

@else
    <p style="text-align:center;color:#777;margin-top:40px;">No residents found for this filter.</p>
@endif

</body>
</html>
