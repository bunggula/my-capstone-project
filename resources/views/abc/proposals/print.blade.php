<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Proposal</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .indent-8 { text-indent: 2rem; }
        .text-justify { text-align: justify; }
        img { max-height: 80px; margin-bottom: 10px; display:block; margin-left:auto; margin-right:auto; }
        .logo-row { display:flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .logo-row img { max-height: 80px; width: auto; }
    </style>
</head>
<body onload="window.print()">

    {{-- 🔰 Letterhead with Dynamic Logos --}}
    <div class="logo-row">
        {{-- LEFT: Municipality Logo --}}
        @if($proposal->barangayInfo->municipality?->logo)
            <img src="{{ asset('storage/'.$proposal->barangayInfo->municipality->logo) }}" alt="Municipality Logo">
        @endif

        {{-- CENTER: Text --}}
        <div class="text-center" style="flex:1;">
            <p class="text-xs uppercase tracking-widest">Republika ng Pilipinas</p>
            <p class="text-sm font-semibold">Barangay {{ $proposal->barangayInfo->name ?? 'N/A' }}</p>
            <p class="text-sm">Municipality of {{ $proposal->barangayInfo->municipality?->name ?? 'N/A' }}</p>
        </div>

        {{-- RIGHT: Barangay Logo --}}
        @if($proposal->barangayInfo->logo)
            <img src="{{ asset('storage/'.$proposal->barangayInfo->logo) }}" alt="Barangay Logo">
        @endif
    </div>

    {{-- 📄 Letter Content --}}
    <div class="text-sm">
        <p class="text-right">{{ now()->format('F d, Y') }}</p>

        <p class="mt-8">To: <strong>Office of ABC President</strong></p>
        <p>From: Barangay {{ $proposal->barangayInfo->name ?? 'N/A' }}</p>
        <p>Barangay Captain: <strong>{{ optional($proposal->captain)->full_name ?? 'Unknown' }}</strong></p>

        <p class="mt-6">Dear Sir/Madam,</p>

        <p class="mt-4 text-justify indent-8">
            We, the officials of Barangay {{ $proposal->barangayInfo->name ?? 'N/A' }}, respectfully submit this project proposal entitled 
            <strong>{{ $proposal->title }}</strong>
            @if($proposal->target_date)
                with a target completion date of <strong>{{ \Carbon\Carbon::parse($proposal->target_date)->format('F d, Y') }}</strong>
            @endif.
        </p>

        <p class="mt-4 text-justify indent-8">{{ $proposal->description }}</p>

        @if($proposal->file)
            <p class="mt-5">
                📎 <a href="{{ asset('storage/'.$proposal->file) }}" target="_blank">View Attached Document</a>
            </p>
        @endif

        <p class="mt-6">Thank you for your consideration. We look forward to your support and favorable response.</p>

        <p class="mt-6">Respectfully yours,</p>

        <div class="mt-8">
            <p><strong>{{ optional($proposal->captain)->full_name ?? 'Barangay Captain' }}</strong></p>
            <p>Barangay Captain</p>
        </div>
    </div>
</body>
</html>
