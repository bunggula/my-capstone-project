@php
$barangay = $request->resident->barangay ?? null;
$municipality = $barangay ? $barangay->municipality : null;
$logo = $barangay && $barangay->logo
    ? asset('storage/' . $barangay->logo)
    : asset('assets/images/logo.png');
$resident = $request->resident ?? null;

$birth_place = $request->form_data['birth_place'] ?? '**********';
$father_name = $request->form_data['father_name'] ?? '**********';
$mother_name = $request->form_data['mother_name'] ?? '__________';

$issue_date = \Carbon\Carbon::parse($request->pickup_date ?? now());
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate of Indigency</title>
<style>
    body { font-family: 'Segoe UI', sans-serif; padding: 40px; font-size: 13px; line-height: 1.6; background: white; position: relative; }

    /* Watermark */
    body::before { 
        content: ""; 
        background: url('{{ $logo }}') no-repeat center; 
        background-size: 300px; 
        opacity: 0.05; 
        position: absolute; 
        top: 50%; left: 50%; 
        width: 100%; height: 100%; 
        transform: translate(-50%, -50%); 
        z-index: 0; pointer-events: none; 
    }

    /* Header */
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0;
    }

    .header .left-logo,
    .header .right-logo {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
    }

    .header .left-logo img,
    .header .right-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .header .text {
        text-align: center;
        flex: 1;
        line-height: 1.2;
    }

    /* Title */
    .title { font-weight: bold; text-align: center; margin: 25px 0 15px; font-size: 15px; letter-spacing: 2px; }

    /* Content */
    .content { text-align: justify; font-size: 13px; position: relative; z-index: 1; }

    .signature-row { margin-top: 60px; text-align: left; }
    .signature-row strong { text-transform: uppercase; display: block; }

    .no-print { margin-top: 30px; text-align: center; }

    @media print { .no-print { display: none; } @page { margin: 15mm; } }
</style>
</head>
<body onload="window.print()">

<div class="header">
    {{-- Left Logo --}}
    <div class="left-logo">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Left Logo">
    </div>

    <div class="text">
        <h2>Republic of the Philippines</h2>
        <h3>Province of Pangasinan</h3>
        <h3>Municipality of {{ $municipality->name ?? 'N/A' }}</h3>
        <h3><strong>Barangay {{ $barangay->name ?? 'N/A' }}</strong></h3>
    </div>

    {{-- Right Logo (Barangay Logo) --}}
    <div class="right-logo">
        <img src="{{ $logo }}" alt="Barangay Logo">
    </div>
</div>

<div class="content">
    <div class="title">C E R T I F I C A T I O N</div>

    <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

    <p>
        This is to certify that <strong>{{ $resident->full_name ?? 'N/A' }}</strong>,
        a <strong>{{ ucfirst($resident->gender ?? '____') }}</strong> resident of 
        Zone {{ $resident->zone ?? '____' }}, Barangay {{ $barangay->name ?? '____' }},
        {{ $municipality->name ?? '' }}, Pangasinan, who was born on 
        <strong>{{ \Carbon\Carbon::parse($resident->birthdate ?? '')->format('F d, Y') }}</strong>
        at <strong>{{ $birth_place }}</strong>, is the son/daughter of 
        <strong>{{ $father_name }}</strong> and <strong>{{ $mother_name }}</strong>.
    </p>

    <p>
        This certification is issued upon the request of 
        <strong>{{ $resident->full_name ?? 'the applicant' }}</strong> 
        for the purpose of his/her <strong>Late Registration of Birth</strong>.
    </p>

    <p>
        Issued this {{ $issue_date->format('jS') }} day of {{ $issue_date->format('F, Y') }},
        at Barangay {{ $barangay->name ?? '' }}, {{ $municipality->name ?? '' }}, Pangasinan.
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
