<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VAWC Report Print</title>
    <style>
    body { font-family: "Times New Roman", serif; font-size: 11pt; margin: 15px; color: #000; }
    .header { text-align: center; margin-bottom: 12px; }
    .header h3 { font-size: 16pt; margin: 0; }
    .sub-header { text-align: center; font-size: 10pt; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; page-break-inside: avoid; font-size: 10pt; }
    table, th, td { border: 1px solid #000; }
    th { background: #f2f2f2; padding: 4px; }
    td { padding: 4px; text-align: center; }
    .section-title { font-weight: bold; margin: 10px 0 6px 0; text-decoration: underline; font-size: 11pt; }
    .signature-block { margin-top: 35px; width: 48%; text-align: center; float: left; font-size: 10pt; }
    .signature-block.right { float: right; }
    .signature-line { border-top: 1px solid #000; margin-top: 35px; padding-top: 4px; }
    .clearfix { clear: both; }

    /* Compact Barangay Info - two columns */
    .barangay-info { display: flex; flex-wrap: wrap; font-size: 10pt; line-height: 1.3; margin-bottom: 12px; }
    .barangay-info div { width: 50%; padding: 3px 0; }

    @media print {
        table, tr, td, th, div, p, h3, h4, h5, h6 { page-break-inside: avoid; }
        @page { size: A4 portrait; margin: 15mm; }
    }
</style>

</head>
<body>

    <!-- Header -->
   @php
$periodStart = $vawcReport->period_start;
$periodEnd = $vawcReport->period_end;
@endphp

<!-- Header -->
<div class="header">
    <h3><b>BARANGAY QUARTERLY ACCOMPLISHMENT REPORT</b></h3>
    <div class="sub-header">
      Covered Period: 
      (
      @if($periodStart && $periodEnd)
          {{ \Carbon\Carbon::parse($periodStart)->format('F d, Y') }} 
          - {{ \Carbon\Carbon::parse($periodEnd)->format('F d, Y') }}
      @else
          N/A
      @endif
      )
    </div>
</div>


    <!-- Barangay Info (Compact Two Columns) -->
    @php
        $barangay = $vawcReport->barangay;
        $municipalityName = $barangay?->municipality?->name ?? 'N/A';
        $residents = $barangay->residents ?? collect();
        $totalPopulation = $residents->count();
        $noMales = $residents->where('gender','Male')->count();
        $noFemales = $residents->where('gender','Female')->count();
        $noAdults = $residents->filter(fn($r) => $r->birthdate <= now()->subYears(18))->count();
        $noMinors = $residents->filter(fn($r) => $r->birthdate > now()->subYears(18))->count();
    
    @endphp
    <div class="barangay-info">
        <div><strong>Barangay:</strong> {{ $barangay->name ?? '-' }}</div>
         <div><strong>Municipality:</strong> {{ $municipalityName }}</div>
        <div><strong>Province:</strong> Pangasinan</div>
        <div><strong>Region:</strong> Ilocos Region</div>
        <div><strong>Total Population:</strong> {{ $totalPopulation }}</div>
        <div><strong>No. of Males:</strong> {{ $noMales }}</div>
        <div><strong>No. of Females:</strong> {{ $noFemales }}</div>
        <div><strong>No. of Adults (18 years old and above):</strong> {{ $noAdults }}</div>
        <div><strong>No. of Minors(below 18 years old):</strong> {{ $noMinors }}</div>
    </div>

    <!-- Summary of Cases -->
    <div class="section-title">Summary of Cases Received and Action Taken</div>
    <table>
        <tr>
            <td style="text-align: left;"><b>Total No. of Clients Served</b></td>
            <td style="text-align: left;">{{ $vawcReport->total_clients_served ?? 0 }}</td>
        </tr>
        <tr>
            <td style="text-align: left;"><b>Total No. of Cases Received by the Barangay</b></td>
            <td style="text-align: left;">{{ $vawcReport->total_cases_received ?? 0 }}</td>
        </tr>
        <tr>
            <td style="text-align: left;"><b>Total No. of Cases Acted Upon</b></td>
            <td style="text-align: left;">{{ $vawcReport->total_cases_acted ?? 0 }}</td>
        </tr>
    </table>

    <!-- Cases Table -->
    <div class="section-title">Nature of Case</div>
    <table>
        <thead>
            <tr>
                <th>Nature of Case</th>
                <th>Total No. of Victims</th>
                <th>C/MSWDO</th>
                <th>PNP</th>
                <th>Court</th>
                <th>Hospital</th>
                <th>Others</th>
                <th>Total No. Applied for BPO</th>
                <th>Total No. BPOs Issued</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vawcReport->cases as $case)
            <tr>
                <td>{{ $case->nature_of_case ?? '-' }}</td>
                <td>{{ $case->num_victims ?? 0 }}</td>
                <td>{{ $case->ref_cmswdo ?? 0 }}</td>
                <td>{{ $case->ref_pnp ?? 0 }}</td>
                <td>{{ $case->ref_court ?? 0 }}</td>
                <td>{{ $case->ref_hospital ?? 0 }}</td>
                <td>{{ $case->ref_others ?? 0 }}</td>
                <td>{{ $case->num_applied_bpo ?? 0 }}</td>
                <td>{{ $case->num_bpo_issued ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Programs -->
    <div class="section-title">Programs / Projects / Activities Implemented</div>
    <table>
        <thead>
            <tr>
                <th>PPAs</th>
                <th>Titles</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vawcReport->programs as $program)
            <tr>
                <td>{{ $program->ppa_type ?? '-' }}</td>
                <td>{{ $program->title ?? '-' }}</td>
                <td>{{ $program->remarks ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="3">No programs recorded.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signatures -->
    <div class="signature-block">
        <div class="signature-line">
            Prepared/Submitted By:<br>
            {{ $deskOfficer ? $deskOfficer->first_name.' '.($deskOfficer->middle_name ?? '').' '.$deskOfficer->last_name : 'VAW Desk Officer' }}<br>
            ({{ $deskOfficer->position ?? 'VAW Desk Officer' }})<br>
            Date: {{ \Carbon\Carbon::parse($vawcReport->created_at)->format('m/d/Y') }}
        </div>
    </div>

    <div class="signature-block right">
        <div class="signature-line">
            Noted By:<br>
            {{ $chairperson ? $chairperson->first_name.' '.($chairperson->middle_name ?? '').' '.$chairperson->last_name : 'Barangay Captain' }}<br>
            ({{ $chairperson->position ?? 'Barangay Captain' }})<br>
            Date: {{ \Carbon\Carbon::parse($vawcReport->created_at)->format('m/d/Y') }}
        </div>
    </div>

    <div class="clearfix"></div>

    <script>window.print();</script>
</body>
</html>
