

<?php $__env->startSection('content'); ?>
<style>
    body {
        background: linear-gradient(to right,rgb(255, 255, 255),rgb(255, 255, 255));
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .custom-panel {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        padding: 50px 40px;
        width: 100%;
        max-width: 650px;
        transition: all 0.3s ease-in-out;
    }

    .custom-panel:hover {
        transform: scale(1.01);
    }

    .custom-panel h4 {
        font-weight: 700;
        font-size: 24px;
        text-align: center;
        margin-bottom: 35px;
        color: #2c3e50;
    }

    .form-label {
        font-weight: 600;
        color: #34495e;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        height: 48px;
        font-size: 16px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .btn-custom {
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.2s ease-in-out;
    }

    .btn-cancel {
        background-color: #ecf0f1;
        color: #2c3e50;
        border: none;
    }

    .btn-cancel:hover {
        background-color: #d0d7dc;
    }

    .btn-submit {
        background-color: #2980b9;
        color: #fff;
        border: none;
    }

    .btn-submit:hover {
        background-color: #1c6ca1;
    }

    .form-group {
        margin-bottom: 25px;
    }
</style>

<div class="form-wrapper">
    <div class="custom-panel">
        <h4>Edit Document Details</h4>
        <form action="<?php echo e(route('secretary.document_requests.update', $request->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-group">
                <label for="control_number" class="form-label">Control Number</label>
                <input type="text" class="form-control" id="control_number" name="control_number" value="<?php echo e(old('control_number', $request->control_number)); ?>">
            </div>

            <div class="form-group">
                <label for="ctc_number" class="form-label">Community Tax Certificate No.</label>
                <input type="text" class="form-control" id="ctc_number" name="ctc_number" value="<?php echo e(old('ctc_number', $request->ctc_number)); ?>">
            </div>

            <div class="form-group">
                <label for="receipt_number" class="form-label">Barangay Receipt No.</label>
                <input type="text" class="form-control" id="receipt_number" name="receipt_number" value="<?php echo e(old('receipt_number', $request->receipt_number)); ?>">
            </div>

            <div class="form-group">
                <label for="price" class="form-label">Amount (Php)</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo e(old('price', $request->price)); ?>">
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('secretary.document_requests.print', $request->id)); ?>" class="btn btn-cancel btn-custom">Cancel</a>
                <button type="submit" class="btn btn-submit btn-custom">Save & Print</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/document_requests/edit.blade.php ENDPATH**/ ?>