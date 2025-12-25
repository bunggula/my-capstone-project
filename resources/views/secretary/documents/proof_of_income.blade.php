@php
    $barangay = $request->resident->barangay ?? null;
        $municipality = $barangay ? $barangay->municipality : null;
    $logo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : asset('assets/images/logo.png');
        $defaultLogo = asset('images/logo.png');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proof of Income Certificate</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 35px;
            font-size: 13px;
            line-height: 1.6;
            background: white;
        }

        /* Header */
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

        .header .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header .text {
            flex: 1;
            text-align: center;
        }

        .header .text h2,
        .header .text h3 {
            margin: 0;
            line-height: 1.2;
        }

        .header .text p {
            margin: 4px 0 0;
        }

        /* Title */
        .title {
            font-weight: bold;
            text-align: center;
            margin: 10px 0 10px;
            font-size: 16px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        /* Main layout */
        .main-section {
            display: flex;
            margin-top: 20px;
        }
        /* Officials */
        .officials-section {
            width: 30%;
            border-right: 1px solid #ccc;
            padding-right: 15px;
        }

        .officials-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .officials-list {
            font-size: 13px;
        }

        .officials-list p,
        .officials-list li {
            margin: 4px 0;
        }

        .officials-list ul {
            padding-left: 20px;
        }

        /* Letter content */
        .content-section {
            width: 70%;
            padding-left: 25px;
        }

        .content-section .content {
            text-align: justify;
            font-size: 13px;
        }

        /* Signature */
        .signature {
            margin-top: 40px;
            text-align: left;
        }

        /* Print Button */
        .no-print {
            margin-top: 30px;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }

            @page {
                margin: 15mm;
            }
        }
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

{{-- Main Section --}}
<div class="main-section">
    {{-- Officials --}}
    <div class="officials-section">
        <div class="officials-title">Barangay Officials</div>
        <div class="officials-list">
            <p><strong>Punong Barangay:</strong><br>
                {{ isset($captain) ?  $captain->full_name : '-' }}
            </p>

            <p><strong>Barangay Secretary:</strong><br>
                {{ isset($secretary) ?  $secretary->full_name : '-' }}
            </p>

            <p><strong>Kagawads:</strong></p>
            <ul>
                @forelse ($kagawads as $kagawad)
                    <li> {{ $kagawad->first_name }} {{ $kagawad->middle_name }} {{ $kagawad->last_name }}</li>
                @empty
                    <li>-</li>
                @endforelse
            </ul>
            <p><strong>SK Chairman:</strong><br>
    {{ isset($skChairman) ?   $skChairman->first_name . ' ' . $skChairman->middle_name . ' ' . $skChairman->last_name : '-' }}
</p>
        </div>
    </div>

    {{-- Content --}}
    <div class="content-section">
        <div class="content">
            <div class="title">CERTIFICATION OF PROOF OF INCOME</div>

            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($request->pickup_date)->format('F d, Y') }}</p>

            <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

            <p>
                This is to certify that <strong>{{ $request->resident->full_name ?? '__________' }}</strong>, 
                {{ $request->form_data['civil_status'] ?? 'single' }}, a bonafide resident of Barangay 
                {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? '' }}, Pangasinan, born on 
                {{ \Carbon\Carbon::parse($request->resident->birth_date)->format('F d, Y') }},
                generates minimal income enough for their family's sustenance and is not gainfully employed.
            </p>

            <p>
                Furthermore, they are currently working as <strong>{{ $request->form_data['occupation'] ?? '__________' }}</strong> 
                and earn a monthly income of Php 
                <strong>{{ isset($request->form_data['monthly_income']) ? number_format((float) $request->form_data['monthly_income'], 2) : '__________' }}</strong>.
            </p>

            <p>
                This certification is issued upon request of the above-mentioned for any legal purpose it may serve.
            </p>

            <p>
                Given this {{ \Carbon\Carbon::parse($request->pickup_date)->format('jS') }} day of 
                {{ \Carbon\Carbon::parse($request->pickup_date)->format('F Y') }}, at Barangay {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? '' }}, Pangasinan.
            </p>
            <div class="signature">
                <p><strong>{{ $request->resident->full_name ?? 'N/A' }}</strong><br>
                Signature Over Printed name</p>
            </div> 
            {{-- Signature --}}
            <div class="signature">
                <p><strong>{{ $captain->full_name ?? '' }}</strong><br>
                Punong Barangay</p>
            </div>
        </div>
    </div>
</div>

{{-- Print Button --}}
<div class="no-print">
    <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
</div>

</body>
</html>
