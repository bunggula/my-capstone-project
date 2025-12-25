@php
$barangay = $request->resident->barangay ?? null;
$municipality = $barangay ? $barangay->municipality : null;
$logo = $barangay && $barangay->logo
   ? asset('storage/' . $barangay->logo)
        : null;

    $defaultLogo = asset('images/logo.png');
$resident = $request->resident ?? null;
$child_name = $request->form_data['child_name'] ?? '__________';
$since_date = \Carbon\Carbon::parse($request->form_data['since_date'] ?? now())->format('F d, Y');
$issue_date = \Carbon\Carbon::parse($request->pickup_date ?? now());
@endphp

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', sans-serif; padding: 40px; font-size: 13px; line-height: 1.6; background: white; position: relative; }
    body::before { content: ""; background: url('{{ $logo }}') no-repeat center; background-size: 300px; opacity: 0.05; position: absolute; top: 50%; left: 50%; width: 100%; height: 100%; transform: translate(-50%, -50%); z-index: 0; pointer-events: none; }
    .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .header .logo {
            width: 80px;
            height: 80px;
        }
    .header img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    .header h2, .header h3 {
            margin: 0;
            line-height: 1.2;
        }
         .header .text {
            flex: 1;
            text-align: center;
        }
    .title { font-weight: bold; text-align: center; margin: 25px 0 15px; font-size: 15px; letter-spacing: 2px; }
    .content { text-align: justify; font-size: 13px; position: relative; z-index: 1; }
    .signature-row { margin-top: 60px; text-align: left; }
    .signature-row strong { text-transform: uppercase; display: block; }
    .no-print { margin-top: 30px; }
    @media print { .no-print { display: none; } @page { margin: 15mm; } }
</style>
</head>
<body onload="window.print()">
    {{-- Header --}}
    <div class="header">
        {{-- Left logo --}}
        <div class="logo">
            <img src="{{ $defaultLogo }}" alt="Default Logo">
        </div>

        {{-- Center text --}}
        <div class="text">
            <h2>Republic of the Philippines</h2>
            <h3>Province of Pangasinan</h3>
            <h3>Municipality of {{ $municipality->name ?? 'N/A' }}</h3>

            <h3><strong>Barangay {{ $barangay->name ?? 'N/A' }}</strong></h3>
            <p><strong>OFFICE OF THE PUNONG BARANGAY</strong></p>
        </div>

        {{-- Right logo --}}
        <div class="logo">
            @if($logo)
                <img src="{{ $logo }}" alt="Barangay Logo">
            @else
                <img src="{{ $defaultLogo }}" alt="Default Logo">
            @endif
        </div>
    </div>

<div class="content">
    <div class="title">C E R T I F I C A T I O N</div>


<p><strong>TO WHOM IT MAY CONCERN:</strong></p>

<p>
    This is to certify that <strong>{{ $resident->full_name ?? 'N/A' }}</strong>,
    of legal age, a natural born Filipino, is a bona fide resident of 
    Barangay {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? '' }}, Pangasinan.
</p>

<p>
    This further certifies that 
   <strong>{{ strcasecmp($resident->gender, 'male') === 0 ? 'he' : 'she' }}</strong>
    is a <strong>SOLO PARENT</strong> to 
    <strong>{{ $child_name }}</strong> since <strong>{{ $since_date }}</strong>.
</p>

<p>
    This certification is being issued upon the request of the above-named person 
    for whatever legal purpose it may serve.
</p>

<p>
    Issued this {{ $issue_date->format('jS') }} day of {{ $issue_date->format('F, Y') }},
    at Barangay {{ $barangay->name ?? '' }},{{ $municipality->name ?? '' }}, Pangasinan.
</p>

<div class="signature-row">
    <p><strong>{{ $captain->full_name ?? 'HON. JOEL R. SORIANO' }}</strong><br>Punong Barangay</p>
</div>


</div>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
</div>

</body>
</html>
