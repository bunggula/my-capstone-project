<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Residents Print</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2, h3, h4, p { margin: 2px 0; }
        h2.title { margin-top: 20px; text-align: center; text-transform: uppercase; }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            line-height: 1.4;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #999; 
            padding: 8px; 
            text-align: left; 
            font-size: 14px; 
        }
        th { background-color: rgb(173, 176, 180); color: white; }
        tr:nth-child(even) { background: #f2f2f2; }

        /* Make Name and Birthdate single-line */
        td.name, td.bday {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info { 
            text-align: center; 
            font-size: 14px; 
            color: #555; 
            margin-top: 10px; 
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #333;
        }

        .footer .prepared {
            margin-top: 60px;
            text-align: left;
        }

        .footer strong {
            font-weight: bold;
        }

        @media print {
            #print-btn { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

{{-- 🏛 Government Header with Logo --}}
<div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo">
    <div class="header-text">
        <h2>Republic of the Philippines</h2>
        <h3>Province of Pangasinan</h3>
        <h3>Municipality of Laoac</h3>
        <h3><strong>Barangay {{ $barangayName ?? 'N/A' }}</strong></h3>
    </div>
    <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" style="opacity:0;"> {{-- for symmetry --}}
</div>

@php
    $categoryLabels = [
        'PWD' => 'Person With Disability',
        'Senior' => 'Senior Citizen',
        'Indigenous' => 'Indigenous People',
        'Single Parent' => 'Single Parent',
    ];

    $voterLabels = [
        'Yes' => 'Registered',
        'No' => 'Non-Registered',
    ];

    $categoryDisplay = $categoryLabels[$request->category] ?? null;
    $voterDisplay = $voterLabels[$request->voter] ?? null;

    $filters = [];
    if ($categoryDisplay) $filters[] = $categoryDisplay;
    if ($voterDisplay) $filters[] = $voterDisplay;
    $filtersText = count($filters) ? implode(' | ', $filters) : 'All Residents';
@endphp

{{-- 🧾 Filter Summary --}}
<p class="info" style="text-align:left; font-weight:600; margin-top:20px;">
    List of Residents of Barangay {{ $barangayName ?? 'All Barangays' }}{{ count($filters) ? ' | ' . $filtersText : '' }}
</p>

<a href="#" id="print-btn"
   onclick="window.print()"
   style="display:inline-block;background:#16a34a;color:#fff;padding:8px 16px;border-radius:6px;text-decoration:none;margin-top:10px;">
    Print Page
</a>

{{-- 📋 Table --}}
@if($residents->count())
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Barangay</th>
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
                    <td>{{ $resident->barangay->name ?? 'N/A' }}</td>
                    <td>{{ $resident->category ?? 'N/A' }}</td>
                    <td>{{ $resident->voter == 'Yes' ? 'Registered' : 'Non-Registered' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ✅ Footer --}}
    <div class="footer">
        <p style="text-align:right;"><strong>Total Residents:</strong> {{ $residents->count() }}</p>
        <p style="text-align:right;"><strong>Printed on:</strong> {{ now()->format('F d, Y h:i A') }}</p>

        @if($abcAdmin)
            @php
                $fullName = trim("{$abcAdmin->first_name} {$abcAdmin->middle_name} {$abcAdmin->last_name}");
            @endphp
            <div class="prepared" style="margin-top:60px; text-align:right;">
                <p><strong>Prepared by:</strong> {{ $fullName }}</p>
                <p>ABC President</p>
            </div>
        @else
            <div class="prepared" style="margin-top:60px; text-align:right;">
                <p><strong>Prepared by:</strong> ____________________</p>
                <p>ABC President</p>
            </div>
        @endif

@else
    <p style="text-align:center;color:#777;margin-top:40px;">No residents found for this filter.</p>
@endif

</body>
</html>
