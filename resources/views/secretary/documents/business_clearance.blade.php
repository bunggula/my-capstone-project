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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            font-size: 13px;
            line-height: 1.6;
            background: white;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px; /* Increased spacing after header */
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
            line-height: 1.3;
        }

        .header .text p {
            margin: 4px 0 0;
        }

       .title {
            font-weight: bold;
            text-align: center;
            margin: 10px 0 10px;
            font-size: 16px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .main-section {
            display: flex;
            margin-top: 10px; /* Less space between header and main content */
            border-top: 1px solid #ccc;
            padding-top: 25px;
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
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
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
            @if($logo)
                <img src="{{ $logo }}" alt="Barangay Logo">
            @else
                <img src="{{ $defaultLogo }}" alt="Default Logo">
            @endif
        </div>
    </div>


    {{-- Main Layout --}}
    <div class="main-section">
        {{-- Left: Barangay Officials --}}
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Punong Barangay:</strong><br>
                    {{ isset($captain) ? $captain->full_name : '-' }}
                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    {{ isset($secretary) ? $secretary->full_name : '-' }}
                </p>

                <p><strong>Kagawads:</strong></p>
                <ul>
                    @forelse ($kagawads as $kagawad)
                        <li>{{ $kagawad->first_name }} {{ $kagawad->middle_name }} {{ $kagawad->last_name }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ul>
                <p><strong>SK Chairman:</strong><br>
    {{ isset($skChairman) ?   $skChairman->first_name . ' ' . $skChairman->middle_name . ' ' . $skChairman->last_name : '-' }}
</p>
            </div>
        </div>

        {{-- Right: Content --}}
        <div class="content-section">
            <div class="content">
                {{-- Title --}}
                <div class="title">Barangay Business Clearance</div>

                
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($request->pickup_date)->format('F d, Y') }}</p>

                <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

                <p>
                    CLEARANCE IS GRANTED to <strong>{{ $request->resident->full_name ?? 'N/A' }}</strong>,
                    of Barangay  {{ $barangay->name ?? '__________' }}, Laoac, Pangasinan,,
                    to hold their BUSINESS TO OPERATE <strong>({{ strtoupper($request->form_data['business_name'] ?? '__________') }})</strong>
                    from <strong>{{ \Carbon\Carbon::parse($request->form_data['start_date'] ?? now())->format('F d, Y') }}</strong>
                    to <strong>{{ \Carbon\Carbon::parse($request->form_data['end_date'] ?? now())->format('F d, Y') }}</strong> 
                    at Barangay {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? 'N/A' }}, Pangasinan,
                    subject to all existing decrees, laws, and ordinances governing the same.
                </p>

                <p>
                    Given this {{ \Carbon\Carbon::parse($request->pickup_date)->format('jS') }} day of
                    {{ \Carbon\Carbon::parse($request->pickup_date)->format('F, Y') }}
                    at Barangay {{ $barangay->name ?? '' }}, {{ $municipality->name ?? 'N/A' }}, Pangasinan.
                </p>
                <div class="signature">
                <p><strong>{{ $request->resident->full_name ?? 'N/A' }}</strong><br>
                Signature Over Printed name</p>
            </div>
                {{-- Signature --}}
                <div class="signature">
                    <p><strong>{{ $captain->full_name ?? '          ikjm, hu5w3V  ' }}</strong><br>
                    Punong Barangay</p>              </div>

                {{-- Footer Info --}}
               
            </div>
        </div>
    </div>

    {{-- Print Controls --}}
    <div class="no-print">
       
        <button onclick="window.print()" class="btn btn-success" title="Print">🖨️</button>
        
    </div>

</body>
</html>
