@php
    $barangay = auth()->user()->barangay;
  $municipality = $barangay ? $barangay->municipality : null;
    $defaultLogo = asset('images/logo.png');

    $barangayLogo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : $defaultLogo;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blotter Record - {{ $blotter->id }}</title>

    <style>
        /* Letter page layout */
        @page { size: Letter; margin: 40px; }
        body { 
            font-family: "Times New Roman", Times, serif; 
            margin: 0; padding: 0;
            color: #000;
        }
        .container { width: 100%; padding: 30px 40px; box-sizing: border-box; }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header img {
            height: 85px;
            object-fit: contain;
        }
        .header-text {
            flex: 1;
            text-align: center;
            padding: 0 15px;
        }
        .header-text h2,
        .header-text h3 {
            margin: 0;
            line-height: 1.2;
        }

        /* Title */
        h1 { 
            text-align: center; 
            margin: 25px 0 15px; 
            font-size: 22px;
            text-transform: uppercase;
        }

        /* Content */
        .content { margin-top: 25px; line-height: 1.7; font-size: 16px; }
        ul { margin: 0; padding-left: 20px; }

        .section { margin-bottom: 20px; }

        /* Footer */
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-block {
            width: 45%;
        }
        .signature-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .print-btn { 
            margin-top: 20px; 
            padding: 10px 20px; 
            background: #007bff; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
        }
        @media print { .print-btn { display: none; } }
    </style>
</head>

<body>
    <div class="container">

        {{-- HEADER --}}
        <div class="header">
            <img src="{{ $defaultLogo }}" alt="Left Logo">

            <div class="header-text">
                <h2>Republic of the Philippines</h2>
                <h3>Province of Pangasinan</h3>
                <h3>Municipality of {{ $municipality->name ?? 'N/A' }}</h3>
                <h3><strong>Barangay {{ $barangayName ?? 'N/A' }}</strong></h3>
            </div>

            {{-- Invisible logo to balance spacing --}}
          <img src="{{ $barangayLogo }}" alt="Barangay Logo">

        </div>

        {{-- TITLE --}}
        <h1>Barangay Blotter Record</h1>

        {{-- CONTENT --}}
        <div class="content">

            <div class="section">
                <p><strong>Date & Time:</strong> 
                    {{ \Carbon\Carbon::parse($blotter->date.' '.$blotter->time)->format('F d, Y g:i A') }}
                </p>
            </div>

            <div class="section">
                <p><strong>Complainants:</strong></p>
                <ul>
                    @foreach($blotter->complainants as $c)
                        <li>{{ $c->first_name }} {{ $c->middle_name ?? '' }} {{ $c->last_name }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="section">
                <p><strong>Respondents:</strong></p>
                <ul>
                    @foreach($blotter->respondents as $r)
                        <li>{{ $r->first_name }} {{ $r->middle_name ?? '' }} {{ $r->last_name }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="section">
                <p><strong>Description:</strong></p>
                <p>{{ $blotter->description }}</p>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="footer">

            {{-- Complainants SIDE --}}
            <div class="signature-block">
                @foreach($blotter->complainants as $c)
                    <div class="signature-name">
                        {{ $c->first_name }} {{ $c->middle_name ?? '' }} {{ $c->last_name }}
                    </div>
                @endforeach
                <div>Complainant{{ $blotter->complainants->count() > 1 ? 's' : '' }}</div>
            </div>

            {{-- CAPTAIN --}}
            <div class="signature-block" style="text-align:right;">
                <div class="signature-name">
                    {{ $captain->first_name }} {{ $captain->middle_name ?? '' }} {{ $captain->last_name }}
                </div>
                <div>Barangay Captain</div>
            </div>

        </div>

        {{-- PRINT BUTTON --}}
        <button onclick="window.print()" class="print-btn">Print</button>

    </div>
</body>
</html>
