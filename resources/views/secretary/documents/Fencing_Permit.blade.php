@php
$barangay = $request->resident->barangay ?? null;
$municipality = $barangay ? $barangay->municipality : null;
$logo = $barangay && $barangay->logo
? asset('storage/' . $barangay->logo)
: asset('assets/images/logo.png');
$defaultLogo = asset('images/logo.png');
$resident = $request->resident ?? null;
$purpose = $request->form_data['purpose'] ?? 'Construction of Perimeter Fence';
$permit_type = $request->form_data['permit_type'] ?? 'Fencing Permit';
$issue_date = \Carbon\Carbon::parse($request->pickup_date ?? now());
@endphp

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', sans-serif; padding: 40px; font-size: 13px; line-height: 1.6; background: white; position: relative; }
    body::before { content: ""; background: url('{{ $logo }}') no-repeat center; background-size: 300px; opacity: 0.05; position: absolute; top: 50%; left: 50%; width: 100%; height: 100%; transform: translate(-50%, -50%); z-index: 0; pointer-events: none; }


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
.content-section { width: 100%; }
.content-section .content { text-align: justify; font-size: 13px; }
.signature-row { display: flex; justify-content: space-between; margin-top: 60px; }
.signature-row div { width: 45%; }
.signature-row p { margin: 4px 0; }
.footer { margin-top: 30px; font-size: 12px; }
.no-print { margin-top: 30px; }

@media print { 
    .no-print { display: none; } 
    @page { margin: 15mm; } 
    .main-section { page-break-after: always; }
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
<div class="main-section">
    <div class="content-section">
        <div class="content">


        <div class="title">Barangay Clearance<br>(Fencing Permit)</div>

        <p><strong>Date:</strong> {{ $issue_date->format('F d, Y') }}</p>

        <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

        <p>
            Pursuant to the provisions of the Local Government Code, CLEARANCE IS HEREBY GRANTED to
            <strong>{{ $resident->full_name ?? 'N/A' }}</strong>,
            a resident of Barangay {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? '' }}, Pangasinan,
            to undertake <strong>{{ $purpose }}</strong> in Barangay {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? '' }}, Pangasinan.
        </p>

        <p>
            This clearance is being issued upon the request of {{ $resident->full_name ?? 'the applicant' }}
            in connection with his/her application to secure <strong>{{ $permit_type }}</strong>.
        </p>

        <p>
            Issued this {{ $issue_date->format('jS') }} day of {{ $issue_date->format('F, Y') }} 
            at Barangay {{ $barangay->name ?? '' }}, {{ $municipality->name ?? '' }}, Pangasinan.
        </p>

        <div class="signature-row">
            <div style="text-align: left;">
                <p><strong>{{ $captain->full_name ?? 'HON. ARNEL R. ICO' }}</strong><br>Punong Barangay</p>
            </div>
        </div>

    </div>
</div>


</div>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
</div>

</body>
</html>
