@php
use Carbon\Carbon;

$user = Auth::user();
$barangay = $user->barangay ?? null;

// 🔹 Barangay logo
$barangayLogo = $barangay && $barangay->logo 
    ? asset('storage/' . $barangay->logo) 
    : asset('assets/images/logo.png');

// 🔹 Municipality logo
$municipalityLogo = $barangay && $barangay->municipality && $barangay->municipality->logo
    ? asset('storage/' . $barangay->municipality->logo)
    : asset('assets/images/municipality.png'); // fallback

$municipalityName = optional($barangay?->municipality)->name ?? 'N/A';

$headerTitle = 'SERVICES REPORT' . ($documentType ? ' — ' . strtoupper($documentType) : '');

$filterLabel = '';
if ($dateFilter === 'daily' && !empty($dailyDate)) {
    $filterLabel = 'DAILY — ' . Carbon::parse($dailyDate)->format('F d, Y');
} elseif ($dateFilter === 'weekly' && !empty($startDate) && !empty($endDate)) {
    $filterLabel = 'WEEKLY — ' . Carbon::parse($startDate)->format('F d, Y') . ' to ' . Carbon::parse($endDate)->format('F d, Y');
} elseif ($dateFilter === 'monthly' && !empty($month)) {
    $filterLabel = 'MONTHLY — ' . Carbon::parse($month)->format('F Y');
}

// Collect unique form_data keys
$formDataKeys = [];
foreach ($completedRequests as $req) {
    if (is_array($req->form_data)) {
        $keys = array_keys($req->form_data);
        $keys = array_diff($keys, ['purpose', 'price']);
        $formDataKeys = array_unique(array_merge($formDataKeys, $keys));
    }
}
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Services Report</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 14px; }
        .header-container { display: flex; justify-content: space-between; align-items: center; }
        .header-container img { width: 80px; height: 80px; object-fit: contain; }
        .header-text { text-align: center; line-height: 1.4; flex-grow: 1; margin: 0 10px; }
        .header-text strong { font-size: 14px; letter-spacing: 0.5px; display: block; }
        .filter-label { text-align: center; font-size: 13px; margin-bottom: 20px; font-style: italic; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { border: 1px solid black; padding: 6px; text-align: center; }
        th { background-color: #f3f3f3; }
        .signature-section { display: flex; justify-content: space-between; margin-top: 80px; }
        .signature-block { text-align: center; width: 40%; }
        .bg-watermark { position: fixed; top:0; left:0; width:100%; height:100%; opacity:0.15; z-index:-1; background-size:60%; background-position:center; background-repeat:no-repeat; }
        @media print {
            body { margin: 10px; zoom: 90%; }
            @page { size: A4 portrait; margin: 10mm; }
            .signature-section { margin-top: 100px; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Watermark: Barangay logo -->
    <div class="bg-watermark" style="background-image: url('{{ $barangayLogo }}');"></div>

    <!-- Header: Municipality logo left, Barangay logo right -->
    <div class="header-container" style="margin-bottom: 10px;">
        <img src="{{ $municipalityLogo }}" alt="Municipality Logo">
        <div class="header-text">
            <strong>REPUBLIC OF THE PHILIPPINES</strong>
            <strong>PROVINCE OF PANGASINAN</strong>
            <strong>MUNICIPALITY OF {{ strtoupper($municipalityName) }}</strong>
            <strong>BARANGAY {{ strtoupper($barangay->name ?? 'N/A') }}</strong>
        </div>
        <img src="{{ $barangayLogo }}" alt="Barangay Logo">
    </div>

    <!-- Optional underline -->
    <div style="border-bottom:2px solid #000; width:80%; margin:0 auto 20px;"></div>

    <!-- Header title -->
    <div style="text-align:center; margin-bottom:10px;">
        <h3>{{ $headerTitle }}</h3>
    </div>

    <!-- Filter Label -->
    @if($filterLabel)
        <div class="filter-label">{{ $filterLabel }}</div>
    @endif

    <!-- Table -->
    @if($completedRequests->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Resident Name</th>
                    <th>Document Type</th>
                    <th>Purpose</th>
                    @foreach($formDataKeys as $key)
                        <th>{{ ucwords(str_replace('_',' ',$key)) }}</th>
                    @endforeach
                    <th>Price</th>
                    <th>Completed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedRequests as $index => $request)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $request->resident->full_name ?? 'N/A' }}</td>
                        <td>{{ $request->document_type ?? 'N/A' }}</td>
                        <td>{{ $request->purpose ?? 'N/A' }}</td>
                        @foreach($formDataKeys as $key)
                            @php
                                $value = $request->form_data[$key] ?? 'N/A';
                                try { if(!empty($value)) $value = Carbon::parse($value)->format('F d, Y'); } catch (\Exception $e) {}
                            @endphp
                            <td>{{ $value }}</td>
                        @endforeach
                        <td>{{ $request->price !== null ? '₱'.number_format($request->price,2) : 'N/A' }}</td>
                        <td>{{ $request->completed_at ? Carbon::parse($request->completed_at)->format('F d, Y') : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $totalRequests = $completedRequests->count();
            $totalPrice = $completedRequests->sum(fn($r) => $r->price ?? 0);
        @endphp

        <div style="margin-top: 15px; font-weight: bold;">
            <div>Total Requests: {{ $totalRequests }}</div>
            <div>Total Price: ₱{{ number_format($totalPrice,2) }}</div>
        </div>
    @else
        <p>No completed service requests found.</p>
    @endif

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-block">
            <div style="border-top:1px solid #000; margin-bottom:4px;"></div>
            <strong>Prepared by:</strong><br>
            {{ $secretaryName ?? '__________' }}<br>
            Barangay Secretary
        </div>

        <div class="signature-block">
            <div style="border-top:1px solid #000; margin-bottom:4px;"></div>
            <strong>Attested by:</strong><br>
            {{ $captainName ?? '__________' }}<br>
            Punong Barangay
        </div>
    </div>

</body>
</html>
