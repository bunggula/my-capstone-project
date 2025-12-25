<?php
    $barangay = $request->resident->barangay ?? null;
    $logo = $barangay && $barangay->logo
        ? asset('storage/' . $barangay->logo)
        : asset('assets/images/logo.png');
?>

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
            background: url('<?php echo e($logo); ?>') no-repeat center;
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
            margin-bottom: 0;
        }

        .logo {
            width: 75px;
            height: 75px;
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .text {
            flex: 1;
            text-align: center;
            margin-left: -75px;
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
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
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
        }
    </style>
</head>
<body>

    
    <div class="header">
        <div class="logo">
            <img src="<?php echo e($logo); ?>" alt="Barangay Logo">
        </div>
        <div class="text">
            <h2>Republic of the Philippines</h2>
            <h3>Province of Pangasinan</h3>
            <h3>Municipality of Laoac</h3>
            <h3><strong>Barangay <?php echo e($barangay->name ?? 'N/A'); ?></strong></h3>
            <p><strong>BAGONG PILIPINAS</strong></p>
        </div>
        <div style="width: 75px;"></div>
    </div>

    
    <div class="main-section">
        <div class="content-section">
            <div class="content">

                
                <div class="title">Certification<br>(RA 11261 - First Time Jobseeker)</div>

                <p><strong>Control No:</strong> <?php echo e($request->control_number ?? '__________'); ?></p>
                <p><strong>Date:</strong> <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('F d, Y')); ?></p>

                <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

                <p>
                    This is to certify that <strong><?php echo e($request->resident->full_name ?? 'N/A'); ?></strong>,
                    born on <strong><?php echo e(\Carbon\Carbon::parse($request->resident->birth_date)->format('F d, Y')); ?></strong>,
                    <?php echo e($request->resident->age ?? '___'); ?> years old, <?php echo e($request->resident->civil_status ?? 'married'); ?>,
                    a resident of Barangay <?php echo e($barangay->name ?? '__________'); ?>, Laoac, Pangasinan
                    for almost <?php echo e($request->form_data['years_of_residency'] ?? '___'); ?> year(s),
                    is qualified and has availed of RA 11261 – the First Time Jobseeker Assistance Act of 2019.
                </p>

                <p>
                    This is to CERTIFY further that the holder/bearer was informed of his/her rights,
                    including the duties and responsibilities accorded by RA 11261 through the Oath of Undertaking
                    signed and executed in the presence of Barangay Officials.
                </p>

                <p>
                    Given this <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('jS')); ?> day of
                    <?php echo e(\Carbon\Carbon::parse($request->pickup_date)->format('F, Y')); ?>

                    at Barangay <?php echo e($barangay->name ?? ''); ?>, Laoac, Pangasinan.
                </p>

                
                <div class="signature-row">
                    
                    <div style="text-align: left;">
                        <p><strong><?php echo e($captain->full_name ?? 'HON. ARNEL R. ICO'); ?></strong><br>
                        Punong Barangay</p>
                    </div>

                    
                    <div style="text-align: right;">
                        <p><strong>Verified and Witnessed by:</strong><br>
                            <?php echo e($secretary->full_name ?? 'MARIVIC N. BOLANTE'); ?><br>
                            Barangay Secretary</p>

                        <p style="margin-top: 15px;">
                            <small><i>Not valid without seal</i></small><br>
                            <small><i>This Certification is valid for one (1) year from the date of issuance.</i></small>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <div class="no-print">
        <a href="<?php echo e(route('secretary.document_requests.edit', $request->id)); ?>" class="btn btn-warning">✏️ Edit</a>
        <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
        <a href="<?php echo e(route('secretary.document_requests.show', $request->id)); ?>" class="btn btn-secondary">⬅ Back</a>
    </div>

</body>
</html>
<?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/documents/first_time_jobseeker.blade.php ENDPATH**/ ?>