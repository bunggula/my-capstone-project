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
    <title>Certificate of Indigency</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
            font-size: 13px;
            background: white;
        }

        /* Header */
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

       .text { 
    text-align: center; 
    flex: 1; 
}

.text h2, .text h3 { 
    margin: 0; 
    line-height: 1.2; 
}

.text p { 
    margin: 2px 0; 
}

        /* Title */
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0 10px;
            position: relative;
        }

        /* Line under title */
        .title::after {
            content: '';
            display: block;
            width: 100%;
            height: 2px;
            background: black;
            margin-top: 8px;
        }

        /* Content: Officials + Letter */
        .content-container {
            display: flex;
            gap: 40px;
            margin-top: 30px;
        }

        .officials-section {
            width: 30%;
            line-height: 1.6;
            padding-right: 20px;
            border-right: 2px solid #000;
        }

        .officials-section p, .officials-section ul {
            margin: 5px 0;
        }

        .officials-section ul {
            padding-left: 20px;
        }

        .letter-section {
            width: 70%;
            text-align: justify;
            line-height: 1.8;
            padding-left: 20px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: center;
        }

        .no-print {
            margin-top: 20px;
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

    {{-- Title --}}
    <div class="title">
        <u>CERTIFICATE OF INDIGENCY</u>
    </div>

    {{-- Content: Officials + Letter --}}
    <div class="content-container">
        {{-- Officials Section --}}
        <div class="officials-section">
            <p><strong>Punong Barangay:</strong><br>
                {{ isset($captain) ?  $captain->full_name : '-' }}
            </p>

            <p><strong>Barangay Secretary:</strong><br>
                {{ isset($secretary) ? $secretary->full_name : '-' }}
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
                {{ isset($skChairman) ? $skChairman->first_name . ' ' . $skChairman->middle_name . ' ' . $skChairman->last_name : '-' }}
            </p>
        </div>

        {{-- Letter Section --}}
        <div class="letter-section">
            <p>TO WHOM IT MAY CONCERN:</p>

            <p>
                This is to certify that Mr./Ms. <strong>{{ $request->resident->full_name ?? 'N/A' }}</strong>,
                of legal age, <strong>{{ strtolower($request->resident->civil_status ?? 'N/A') }}</strong>,
                a resident and living in Barangay {{ $barangay->name ?? 'N/A' }}, {{ $municipality->name ?? '' }}, Pangasinan,
                belongs to the list of identified indigent families of this barangay and is deserving of
                any assistance from any government office or institution.
            </p>

            <p>
                This Certificate of Indigency is being issued upon the request of the said person
                for whatever legal purpose it may serve.
            </p>

            <p>
                Given this <strong>{{ now()->format('jS') }}</strong> day of <strong>{{ now()->format('F, Y') }}</strong>,
                at Barangay {{ $barangay->name ?? 'N/A' }}, {{ $municipality->name ?? '' }}, Pangasinan.
            </p>

            <div class="signature">
                <p><strong>{{ $request->resident->full_name ?? 'N/A' }}</strong><br>
                Signature Over Printed name</p>
            </div>

            <div class="signature">
                <p><strong>{{ $captain->full_name ?? '' }}</strong><br>
                Punong Barangay</p>
            </div>
        </div>
    </div>

    {{-- Print Button --}}
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-success" title="Print">🖨️ Print</button>
    </div>

</body>
</html>
