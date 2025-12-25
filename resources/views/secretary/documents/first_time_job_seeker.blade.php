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
    <title>RA 11261 Certification</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            font-size: 13px;
            line-height: 1.6;
            background: white;
            position: relative;
        }

        body::before {
            content: "";
            background: url('{{ $logo }}') no-repeat center;
            background-size: 300px;
            opacity: 0.05;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0;
        }

        .logo {
            width: 75px;
            height: 75px;
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .text {
            flex: 1;
            text-align: center;
        }

        .text h2, .text h3 {
            margin: 0;
            line-height: 1.2;
        }

        .text p {
            margin: 2px 0;
        }

        .title {
            font-weight: bold;
            text-align: center;
            margin: 25px 0 15px;
            font-size: 15px;
            text-transform: uppercase;
        }

        .main-section {
            display: flex;
            margin-top: 15px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
            position: relative;
            z-index: 1;
        }

        .content-section {
            width: 100%;
        }

        .content-section .content {
            text-align: justify;
            font-size: 13px;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }

        .signature-row div {
            width: 45%;
        }

        .signature-row p {
            margin: 4px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
        }

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

    {{-- Top Header --}}
    <div class="header">
        {{-- Left Logo --}}
        <div class="logo">
            <img src="{{ $defaultLogo }}" alt="Default Logo">
        </div>

        <div class="text">
            <h2>Republic of the Philippines</h2>
            <h3>Province of Pangasinan</h3>
            <h3>Municipality of {{ $municipality->name ?? 'N/A' }}</h3>

            <h3><strong>Barangay {{ $barangay->name ?? 'N/A' }}</strong></h3>
            <p><strong>BAGONG PILIPINAS</strong></p>
        </div>

        {{-- Right Logo (Barangay Logo) --}}
        <div class="logo">
            <img src="{{ $logo }}" alt="Barangay Logo">
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="main-section">
        <div class="content-section">
            <div class="content">

                {{-- Title --}}
                <div class="title">Certification<br>(RA 11261 - First Time Jobseeker)</div>

                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($request->pickup_date)->format('F d, Y') }}</p>

                <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

                <p>
                    This is to certify that <strong>{{ $request->resident->full_name ?? 'N/A' }}</strong>,
                    born on <strong>{{ \Carbon\Carbon::parse($request->resident->birth_date)->format('F d, Y') }}</strong>,
                    {{ $request->resident->age ?? '___' }} years old, {{ $request->resident->civil_status ?? 'married' }},
                    a resident of Barangay {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? '' }}, Pangasinan
                    for almost {{ $request->form_data['years_of_living'] ?? '___' }} year(s),
                    is qualified and has availed of RA 11261 – the First Time Jobseeker Assistance Act of 2019.
                </p>

                <p>
                    This is to CERTIFY further that the holder/bearer was informed of his/her rights,
                    including the duties and responsibilities accorded by RA 11261 through the Oath of Undertaking
                    signed and executed in the presence of Barangay Officials.
                </p>

                <p>
                    Given this {{ \Carbon\Carbon::parse($request->pickup_date)->format('jS') }} day of
                    {{ \Carbon\Carbon::parse($request->pickup_date)->format('F, Y') }}
                    at Barangay {{ $barangay->name ?? '' }}, {{ $municipality->name ?? '' }}, Pangasinan.
                </p>

                <div class="signature">
                    <p><strong>{{ $request->resident->full_name ?? 'N/A' }}</strong><br>
                    Signature Over Printed name</p>
                </div>

                {{-- Signatures --}}
                <div class="signature-row">
                    {{-- Captain --}}
                    <div style="text-align: left;">
                        <p><strong>{{ $captain->full_name ?? 'HON. ARNEL R. ICO' }}</strong><br>
                        Punong Barangay</p>
                    </div>

                    {{-- Secretary --}}
                    <div style="text-align: right;">
                        <p><strong>Verified and Witnessed by:</strong><br>
                            {{ $secretary->full_name ?? '' }}<br>
                            Barangay Secretary</p>

                        <p style="margin-top: 15px;">
                            <small><i>Not valid without seal</i></small><br>
                            <small><i>This Certification is valid for one (1) year from the date of issuance.</i></small>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Print Controls --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
    </div>

</body>
</html>
