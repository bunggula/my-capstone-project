@php
    $barangay = $request->resident->barangay ?? null;
    $municipality = $barangay ? $barangay->municipality : null;
    $barangayLogo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : null; // kung walang barangay logo, puwede rin blank

    $defaultLogo = asset('images/logo.png'); // default logo sa kaliwa
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
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

        .officials-section {
            width: 32%;
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
            padding-left: 18px;
        }

        .content-section {
            width: 68%;
            padding-left: 25px;
        }

        .content-section .content {
            text-align: justify;
            font-size: 13px;
        }

        .signature {
            margin-top: 40px;
            text-align: left;
        }

        .no-print {
            margin-top: 30px;
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
            @if($barangayLogo)
                <img src="{{ $barangayLogo }}" alt="Barangay Logo">
            @else
                <img src="{{ $defaultLogo }}" alt="Default Logo">
            @endif
        </div>
    </div>

    {{-- Title --}}
    <div class="title">CERTIFICATE OF RESIDENCY</div>

    {{-- Main section --}}
    <div class="main-section">
        {{-- Officials --}}
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Barangay Captain:</strong><br>
                    {{ isset($captain) ?  $captain->first_name . ' ' . $captain->middle_name . ' ' . $captain->last_name : '-' }}
                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    {{ isset($secretary) ?  $secretary->first_name . ' ' . $secretary->middle_name . ' ' . $secretary->last_name : '-' }}
                </p>

                <p><strong>Kagawads:</strong></p>
                <ul>
                    @forelse ($kagawads as $kagawad)
                        <li>
                            {{ $kagawad->first_name }} {{ $kagawad->middle_name }} {{ $kagawad->last_name }}{{ $kagawad->suffix ? ', ' . $kagawad->suffix : '' }}
                        </li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ul>
                <p><strong>SK Chairman:</strong><br>
    {{ isset($skChairman) ?   $skChairman->first_name . ' ' . $skChairman->middle_name . ' ' . $skChairman->last_name : '-' }}
</p>
            </div>
        </div>

        {{-- Letter content --}}
        <div class="content-section">
            <div class="content">
                <p>TO WHOM IT MAY CONCERN:</p>

                <p>
                    This is to certify that Mr./Ms. <strong>{{ $request->resident->full_name ?? 'N/A' }}</strong>,
                    born on <strong>{{ \Carbon\Carbon::parse($request->resident->birthdate)->format('F d, Y') }}</strong>,
                    {{ $request->resident->age }} years old, {{ strtolower($request->resident->gender) }},
                    {{ strtolower($request->resident->civil_status) }},
                    and a resident of Barangay {{ $barangay->name ?? 'N/A' }}, {{ $municipality->name ?? '' }}, Pangasinan,
                    has been residing here for almost
                    <strong>
                    @if($request->form_data['years_of_residency'] ?? false || $request->form_data['months_of_residency'] ?? false)
                        {{ $request->form_data['years_of_residency'] ?? '' }} year(s)
                        {{ $request->form_data['months_of_residency'] ?? '' }} month(s)
                    @else
                        N/A
                    @endif
                    </strong>
                    as per record filed and kept in this office.
                </p>

                <p>
                    This Certificate of Residency is issued upon the request of the above-named person
                    for any legal purpose it may serve.
                </p>

                <p>
                    Given this <strong>{{ now()->format('jS') }}</strong> day of <strong>{{ now()->format('F, Y') }}</strong>
                    at Barangay {{ $barangay->name ?? 'N/A' }}, {{ $municipality->name ?? '' }}, Pangasinan.
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
</div>

{{-- Print button --}}
<div class="no-print">
    <button onclick="window.print()" class="btn btn-success" title="Print">🖨️ Print</button>
</div>

</body>
</html>
