<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Report</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            font-size: 14px;
        }

        .top-info {
            text-align: left;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f3f3f3;
        }

        tfoot input {
            border: none;
            background: transparent;
            font-weight: bold;
            text-align: center;
            width: 100%;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
        }

        .signature-block {
            text-align: center;
            width: 40%;
        }

        @media print {
            body {
                margin: 10px;
                zoom: 90%;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            tfoot input {
                border: none;
                background: transparent;
            }

            .signature-section {
                margin-top: 100px;
            }
        }
    </style>
</head>
<body onload="window.print()">

@php
    $headerTitle = match ($type) {
        'daily' => 'SUMMARY OF DAILY WASTE GENERATION AND DIVERSION MONITORING SHEET FOR ' . \Carbon\Carbon::parse(request('date'))->format('F d, Y'),
        'weekly' => 'SUMMARY OF WEEKLY WASTE GENERATION AND DIVERSION MONITORING SHEET<br>FROM ' . \Carbon\Carbon::parse(request('start_date'))->format('F d, Y') . ' TO ' . \Carbon\Carbon::parse(request('end_date'))->format('F d, Y'),
        'monthly' => 'SUMMARY OF MONTHLY WASTE GENERATION AND DIVERSION MONITORING SHEET<br>FOR THE MONTH OF ' . strtoupper($monthYear),
        default => 'SUMMARY OF WASTE GENERATION AND DIVERSION MONITORING SHEET',
    };

    $averageLabel = match ($type) {
        'daily' => 'Average Daily Waste Generated',
        'weekly' => 'Average Weekly Waste Generated',
        'monthly' => 'Average Monthly Waste Generated',
        default => 'Average Waste Generated',
    };
@endphp

<div class="header">
    <h3>{!! $headerTitle !!}</h3>
</div>

<div class="top-info">
    <div><strong>PROVINCE:</strong> PANGASINAN</div>
    <div><strong>MUNICIPALITY:</strong> {{ strtoupper($municipalityName) }}</div>
    <div><strong>BARANGAY:</strong> {{ strtoupper($barangay) }}</div>
</div>

@if($reports->isNotEmpty())
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Biodegradable</th>
            <th>Recyclable</th>
            <th>Residual (Recyclable)</th>
            <th>Residual (Disposal)</th>
            <th>Special</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
            <tr>
                <td>{{ \Carbon\Carbon::parse($report->date_collected)->format('F d, Y') }}</td>
                <td>{{ $report->biodegradable }}</td>
                <td>{{ $report->recyclable }}</td>
                <td>{{ $report->residual_recyclable }}</td>
                <td>{{ $report->residual_disposal }}</td>
                <td>{{ $report->special }}</td>
                <td>{{ $report->remarks }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th>{{ $averageLabel }}</th>
            <td><input type="text" name="avg_biodegradable" value=""></td>
            <td><input type="text" name="avg_recyclable" value=""></td>
            <td><input type="text" name="avg_residual_recyclable" value=""></td>
            <td><input type="text" name="avg_residual_disposal" value=""></td>
            <td><input type="text" name="avg_special" value=""></td>
            <td></td>
        </tr>
    </tfoot>
</table>
@else
    <p>No records found.</p>
@endif

<!-- Signatures -->
<div class="signature-section">
    <div class="signature-block">
        <div style="border-top: 1px solid #000; margin-bottom: 4px; width: 100%;"></div>
        <strong>Prepared by:</strong><br>
        {{ $secretaryName ?? '__________' }}<br>
        Barangay Secretary
    </div>

    <div class="signature-block">
        <div style="border-top: 1px solid #000; margin-bottom: 4px; width: 100%;"></div>
        <strong>Attested by:</strong><br>
        {{ $captainName ?? '__________' }}<br>
        Punong Barangay
    </div>
</div>

</body>
</html>
