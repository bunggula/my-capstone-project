<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barangay Residents PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>

    <h2>Residents of Barangay {{ Auth::user()->barangay->name ?? 'N/A' }}</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Age</th>
                <th>Civil Status</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($residents as $resident)
            <tr>
                <td>{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</td>
                <td>{{ $resident->gender }}</td>
                <td>{{ $resident->birthdate }}</td>
                <td>{{ $resident->age }}</td>
                <td>{{ $resident->civil_status }}</td>
                <td>{{ $resident->category ?? 'N/A' }}</td>
                <td>{{ $resident->phone ?? 'N/A' }}</td>
                <td>{{ $resident->address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
