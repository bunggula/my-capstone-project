<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Officials</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { margin-bottom: 10px; }
        .header { display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .header img { height: 80px; margin-right: 20px; }
        .header-text { text-align: center; }
        .header-text h1, .header-text h2, .header-text h3 { margin: 0; }
        .header-text h1 { font-size: 20px; font-weight: bold; }
        .header-text h2 { font-size: 18px; font-weight: bold; }
        .header-text h3 { font-size: 16px; }
    </style>
</head>
<body>

  <!-- Header with Logo -->
<div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
    <div class="header-text">
        <h1>Republika ng Pilipinas</h1>
        <h2>Municipality of Laoac</h2>
        <h3>Barangay Council of {{ $barangayName ?? 'All' }}</h3>
    </div>
</div>


    <!-- Filter / Position Info -->
    @if($positionFilter)
        <p><strong>Position:</strong> {{ $positionFilter }}</p>
    @endif

    <!-- Officials Table -->
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Position</th>
                <th>Start Year</th>
                <th>End Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangays as $barangay)
                {{-- Captain --}}
                @php $captain = $barangay->users->firstWhere('role', 'brgy_captain'); @endphp
                @if($captain)
                    <tr>
                        <td>{{ $captain->last_name }}, {{ $captain->first_name }} {{ $captain->middle_name ?? '' }}</td>
                        <td>Barangay Captain</td>
                        <td>{{ $captain->start_year ?? '-' }}</td>
                        <td>{{ $captain->end_year ?? '-' }}</td>
                    </tr>
                @endif

                {{-- Secretary --}}
                @php $secretary = $barangay->users->firstWhere('role', 'secretary'); @endphp
                @if($secretary)
                    <tr>
                        <td>{{ $secretary->last_name }}, {{ $secretary->first_name }} {{ $secretary->middle_name ?? '' }}</td>
                        <td>Barangay Secretary</td>
                        <td>{{ $secretary->start_year ?? '-' }}</td>
                        <td>{{ $secretary->end_year ?? '-' }}</td>
                    </tr>
                @endif

                {{-- Other Officials --}}
                @foreach($barangay->barangayOfficials as $official)
                    <tr>
                        <td>{{ $official->last_name }}, {{ $official->first_name }} {{ $official->middle_name ?? '' }}</td>
                        <td>{{ $official->position }}</td>
                        <td>{{ $official->start_year ?? '-' }}</td>
                        <td>{{ $official->end_year ?? '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>
