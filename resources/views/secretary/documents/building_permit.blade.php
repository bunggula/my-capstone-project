@php
$barangay = $request->resident->barangay ?? null;
 $municipality = $barangay ? $barangay->municipality : null;
$barangayLogo = $barangay && $barangay->logo

    ? asset('storage/' . $barangay->logo)
    : null;
$defaultLogo = asset('assets/images/logo.png');
$resident = $request->resident ?? null;
$purpose = $request->form_data['purpose'] ?? 'Construction of One-Storey Residential Building';
$permit_type = $request->form_data['permit_type'] ?? 'Building Permit';
$issue_date = \Carbon\Carbon::parse($request->pickup_date ?? now());
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
    position: relative; 
}
body::before { 
    content: ""; 
    background: url('{{ $barangayLogo ?? $defaultLogo }}') no-repeat center; 
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
    margin-bottom: 10px; 
}

.logo { 
    width: 75px; 
    height: 75px; 
    display: flex; 
    align-items: center; 
}

.logo img { 
    height: 75px; 
    width: auto; 
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
    page-break-after: always; 
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
    text-align: left; 
}

.footer { 
    margin-top: 30px; 
    font-size: 12px; 
}

.no-print { 
    margin-top: 30px; 
}

@media print { 
    .no-print { display: none; } 
    @page { margin: 15mm; } 
    .main-section { page-break-after: always; }
}
</style>
</head>
<body onload="window.print()">

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
        <img src="{{ $barangayLogo ?? $defaultLogo }}" alt="Barangay Logo">
    </div>
</div>

<div class="main-section">
    <div class="content-section">
        <div class="content">

            <div class="title">Barangay Clearance<br>({{ $permit_type }})</div>

            <p><strong>Date:</strong> {{ $issue_date->format('F d, Y') }}</p>

            <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

            <p>
                Pursuant to the provisions of the Local Government Code, CLEARANCE IS HEREBY GRANTED to
                <strong>{{ $resident->full_name ?? 'REINALYN S. TENORIO' }}</strong>,
                a resident of this barangay, to undertake Construction of <strong>{{ $purpose }}</strong> 
                in Barangay {{ $barangay->name ?? '__________' }}, {{ $municipality->name ?? '' }}, Pangasinan.
            </p>

            <p>
                This clearance is being issued upon the request of Mr/Ms.
                <strong>{{ $resident->last_name ?? 'Ms. Tenorio' }}</strong> 
                in connection with his/her application to secure 
                <strong>{{ strtoupper($permit_type) }}</strong>.
            </p>

            <p>
                Issued this {{ $issue_date->format('jS') }} day of {{ $issue_date->format('F, Y') }} 
                at Barangay {{ $barangay->name ?? '' }}, {{ $municipality->name ?? '' }}, Pangasinan..
            </p>

            <div class="signature-row">
                <div>
                    <p><strong>{{ $captain->full_name ?? 'HON. JOEL R. SORIANO' }}</strong><br>Punong Barangay</p>
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
