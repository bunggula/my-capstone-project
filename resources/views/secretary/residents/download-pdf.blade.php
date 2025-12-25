<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Residents List</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 40px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f7f7f7;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    <h2>Residents of Barangay - {{ Auth::user()->barangay->name ?? 'N/A' }}</h2>

    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Barangay</th>
            </tr>
        </thead>
        <tbody>
            @foreach($residents as $resident)
            <tr>
                <td>{{ $resident->first_name }}</td>
                <td>{{ $resident->middle_name }}</td>
                <td>{{ $resident->last_name }}</td>
                <td>{{ $resident->gender }}</td>
                <td>{{ \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') }}</td>
                <td>{{ $resident->barangay->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}
    </div>

</body>
</html>
