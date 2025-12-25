<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        padding: 30px 40px;
        font-size: 13px;
        line-height: 1.5;
        position: relative;
        background: white;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .header img {
        width: 55px;
        height: 55px;
        object-fit: contain;
    }

    .header-text {
        text-align: center;
    }

    .header-text h2 {
        margin: 0;
        font-size: 16px;
        text-transform: uppercase;
    }

    .header-text p {
        margin: 0;
        font-size: 11px;
    }

    .title {
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        margin: 20px 0 10px;
        text-transform: uppercase;
    }

    .main-section {
        display: flex;
        margin-top: 20px;
        border-top: 1px solid #ccc;
        padding-top: 15px;
    }

    .officials-section {
        width: 35%;
        border-right: 1px solid #ccc;
        padding-right: 15px;
    }

    .officials-title {
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 8px;
        text-align: left;
        text-transform: uppercase;
    }

    .officials-list {
        font-size: 13px;
    }

    .officials-list p,
    .officials-list li {
        margin: 3px 0;
    }

    .officials-list ul {
        padding-left: 20px;
        margin-top: 5px;
    }

    .content-section {
        width: 65%;
        padding-left: 20px;
    }

    .content-section .content {
        font-size: 13px;
        white-space: pre-wrap;
        text-align: justify;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.05;
        width: 250px;
        z-index: 0;
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

        body {
            background: white;
        }
    }
    </style>
</head>
<body>

    {{-- Watermark --}}
    @php
        $barangay = $request->resident->barangay ?? null;
        $logo = $barangay && $barangay->logo ? asset('storage/' . $barangay->logo) : asset('assets/images/logo.png');
    @endphp

    <img src="{{ $logo }}" alt="Watermark" class="watermark">

    {{-- Header --}}
    <div class="header">
        <img src="{{ $logo }}" alt="Barangay Logo">
        <div class="header-text">
            <h2>Barangay {{ $barangay->name ?? 'N/A' }}</h2>
            <p>Municipality / City</p>
            <p>Province</p>
        </div>
    </div>

    <hr>

    {{-- Title --}}
    <div class="title">
        {{ $request->title }}
    </div>

    {{-- Main Layout --}}
    <div class="main-section">
        {{-- Officials --}}
        <div class="officials-section">
            <div class="officials-title">Barangay Officials</div>
            <div class="officials-list">
                <p><strong>Barangay Captain:</strong><br>
                    {{ isset($captain) && is_object($captain)
                        ?'HON.'. $captain->first_name . ' ' . $captain->middle_name . ' ' . $captain->last_name
                        : '-' }}
                </p>

                <p><strong>Barangay Secretary:</strong><br>
                    {{ isset($secretary) && is_object($secretary)
                        ? 'HON.'.$secretary->first_name . ' ' . $secretary->middle_name . ' ' . $secretary->last_name
                        : '-' }}
                </p>

                <p><strong>Kagawads:</strong></p>
                <ul>
                    @forelse ($kagawads as $kagawad)
                        <li>HON.{{ $kagawad->first_name }} {{ $kagawad->middle_name }} {{ $kagawad->last_name }}{{ $kagawad->suffix ? ', ' . $kagawad->suffix : '' }}</li>
                    @empty
                        <li>-</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Content --}}
        <div class="content-section">
            <div class="content">
                {!! nl2br(e($request->content)) !!}
            </div>
        </div>
    </div>

    {{-- Print Button --}}
    <div class="no-print">
        <button onclick="window.print()">🖨️ I-print ang Dokumento</button>
        <a href="{{ route('secretary.document_requests.show', $request->id) }}">⬅ Bumalik</a>
    </div>

</body>
</html>
