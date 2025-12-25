<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Municipality Residents PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #999;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <h2>Municipality Residents</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Name</th>
                <th style="width: 6%;">Gender</th>
                <th style="width: 10%;">Birthdate</th>
                <th style="width: 5%;">Age</th>
                <th style="width: 8%;">Civil Status</th>
                <th style="width: 10%;">Category</th>
                <th style="width: 12%;">Email</th>
                <th style="width: 10%;">Phone</th>
                <th style="width: 10%;">Barangay</th>
                <th style="width: 14%;">Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($residents as $resident)
            <tr>
                <td>
                    {{ $resident->last_name }}, {{ $resident->first_name }}
                    {{ $resident->middle_name }} {{ $resident->suffix }}
                </td>
                <td>{{ $resident->gender }}</td>
                <td>{{ $resident->birthdate }}</td>
                <td>{{ $resident->age }}</td>
                <td>{{ $resident->civil_status }}</td>
                <td>{{ $resident->category ?? 'N/A' }}</td>
                <td>{{ $resident->email ?? 'N/A' }}</td>
                <td>{{ $resident->phone ?? 'N/A' }}</td>
                <td>{{ $resident->barangay->name ?? 'N/A' }}</td>
                <td>{{ $resident->address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
