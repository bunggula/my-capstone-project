@php
$user = auth()->user();
$barangay = $user->barangay ?? null;
$municipality = $barangay?->municipality;

// Logos
$barangayLogo = $barangay && $barangay->logo
    ? asset('storage/' . $barangay->logo)
    : asset('assets/images/logo.png');

$municipalityLogo = $municipality && $municipality->logo
    ? asset('storage/' . $municipality->logo)
    : asset('assets/images/municipality.png');

// Names
$barangayName = strtoupper($barangay->name ?? 'N/A');
$municipalityName = strtoupper($municipality?->name ?? 'N/A');

// Date range
$fromDate = request('from_date');
$toDate = request('to_date');

if ($fromDate && $toDate) {
    $start = \Carbon\Carbon::parse($fromDate);
    $end = \Carbon\Carbon::parse($toDate);

    if ($start->month === $end->month && $start->year === $end->year) {
        // Same month
        $dateRange = $start->format('F d') . ' - ' . $end->format('d, Y');
    } elseif ($start->year === $end->year) {
        // Different months, same year
        $dateRange = $start->format('F d') . ' - ' . $end->format('F d, Y');
    } else {
        // Different years
        $dateRange = $start->format('F d, Y') . ' - ' . $end->format('F d, Y');
    }
} else {
    $dateRange = 'N/A';
}

@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Monthly Monitoring Report - BaRCO</title>
    <style>
        @page { size: landscape; margin: 20px; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        h1,h2,h3,h4,h5 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
        .signature-page { margin-top: 30px; }
        .signature { display: flex; justify-content: space-between; margin-top: 40px; }
        .photo-page { page-break-before: always; margin-top: 20px; }
        .photo-grid { display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px; }
        .photo-item { display: flex; flex-direction: column; align-items: center; justify-content: flex-start; overflow: hidden; height: 180px; }
        .photo-item img { max-width: 100%; max-height: 140px; object-fit: cover; border: 1px solid #000; }
        .photo-caption { text-align: center; font-size: 11px; margin-top: 3px; }
    </style>
</head>
<body>

{{-- Header --}}
<div style="display: flex; justify-content: center; align-items: center; text-align: center; margin-bottom: 20px; gap:5px;">

    <!-- Left: Municipality Logo -->
    <img src="{{ $municipalityLogo }}" alt="{{ $municipalityName }} Logo" style="width: 60px; height: 60px; object-fit: contain; flex-shrink:0; margin-right:-5px;">
    
    <!-- Center: Header Text -->
    <div style="line-height:1.2;">
        <strong>MUNICIPALITY OF {{ $municipalityName }}</strong><br>
        <strong>BARANGAY {{ $barangayName }}</strong><br>
        <h3>Monthly Monitoring Report</h3>
        <h4>On Barangay Road Clearing Operations (BaRCO)</h4>
        <p>(Latest DILG Memorandum Circular No. 2022-085 dated June 20, 2022, <br> DILG MC No. 2023-017 dated January 25, 2023 and DILG MC No. 2024-053 dated April 16, 2024)</p>
        <p>For the Month of {{ $dateRange }}</p>
    </div>

    <!-- Right: Barangay Logo -->
    <img src="{{ $barangayLogo }}" alt="{{ $barangayName }} Logo" style="width: 60px; height: 60px; object-fit: contain; flex-shrink:0; margin-left:-5px;">
</div>

{{-- Report Table --}}
<table>
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Inventory of Barangay Roads, Streets, Alleys & Turned Over Road/s</th>
            <th rowspan="2">Road Length (in km)</th>
            <th rowspan="2">Date of Clearing Operation</th>
            <th rowspan="2">Action Taken</th>
            <th rowspan="2">Remarks</th>
            <th colspan="2">Conducted Road Clearing Operations</th>
        </tr>
        <tr>
            <th>YES</th>
            <th>NO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $index => $report)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $report->location }}</td>
            <td>{{ $report->length }}</td>
            <td>{{ \Carbon\Carbon::parse($report->date)->format('M d, Y') }}</td>
            <td>{{ $report->action_taken }}</td>
            <td>{{ $report->remarks ?? '-' }}</td>
            <td>@if($report->conducted) ✔ @endif</td>
            <td>@if(!$report->conducted) ✘ @endif</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td colspan="5"></td>
        </tr>
    </tbody>
</table>

{{-- Certification --}}
<div class="signature-page">
    <p>
        I hereby <strong>CERTIFY</strong> and <strong>DECLARE</strong> that the above information
        and activities are true and accurate to the best of my knowledge.
    </p>
    <div class="signature">
        <div class="text-center">
            <p>Prepared by:</p>
            <p><strong>{{ $secretaryName }}</strong><br>Barangay Secretary</p>
        </div>

        <div class="text-center">
            <p>Certified by:</p>
            <p><strong>{{ $captainName }}</strong><br>Barangay Captain</p>
        </div>
    </div>
</div>

{{-- Photo Pages --}}
@php
$photosPerPage = 6;
$allPhotos = $reports->flatMap(fn($r) => $r->photos);
@endphp

@foreach($allPhotos->chunk($photosPerPage) as $photoChunk)
<div class="photo-page">
 <div style="display: flex; justify-content: center; align-items: center; text-align: center; margin-bottom: 20px; gap:5px;">

    <!-- Left: Municipality Logo -->
    <img src="{{ $municipalityLogo }}" alt="{{ $municipalityName }} Logo" style="width: 60px; height: 60px; object-fit: contain; flex-shrink:0; margin-right:-5px;">
    
    <!-- Center: Header Text -->
    <div style="line-height:1.2;">
     <strong>BARANGAY {{ $barangayName }}, {{ $municipalityName }}, PANGASINAN</strong><br>
            <h3>Photo Documentation</h3>
            <h4>Conduct of Barangay Road Clearing Operations (BaRCO)</h4>
            <p>(Latest DILG Memorandum Circular No. 2022-085 dated June 20, 2022, <br> DILG MC No. 2023-017 dated January 25, 2023 and DILG MC No. 2024-053 dated April 16, 2024)</p>
            <p>Month of {{ $dateRange }}</p>
    </div>

    <!-- Right: Barangay Logo -->
    <img src="{{ $barangayLogo }}" alt="{{ $barangayName }} Logo" style="width: 60px; height: 60px; object-fit: contain; flex-shrink:0; margin-left:-5px;">
</div>

    <div class="photo-grid">
        @foreach($photoChunk as $photo)
        <div class="photo-item">
            <img src="{{ asset('storage/'.$photo->path) }}" alt="Photo">
            @if(!empty($photo->caption))
            <div class="photo-caption">{{ $photo->caption }}</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endforeach

<script>
    window.onload = function() { window.print(); }
</script>

</body>
</html>
