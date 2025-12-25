

<?php $__env->startSection('content'); ?>
<div class="flex h-screen overflow-y-auto bg-white text-gray-800">
    <!-- Sidebar -->
    <?php echo $__env->make('partials.secretary-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Panel -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        <?php echo $__env->make('partials.secretary-header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <div class="p-6 flex-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold">Edit VAWC Report</h2>
                <a href="<?php echo e(route('vawc_reports.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                    ← Back to Reports
                </a>
            </div>

            <form action="<?php echo e(route('vawc_reports.update', $vawcReport->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

             

                <hr class="my-4">

                
                <h4 class="font-semibold mb-2">Population Summary (Manual Input)</h4>
             

                <hr class="my-4">

                
                <h4 class="font-semibold mb-2">Summary of Services</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block mb-1 font-medium">Total No. of Clients Served</label>
                        <input type="number" name="total_clients_served" class="w-full border rounded px-2 py-1" min="0" value="<?php echo e(old('total_clients_served', $vawcReport->total_clients_served)); ?>">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Total No. of Cases Received by the Barangay</label>
                        <input type="number" name="total_cases_received" class="w-full border rounded px-2 py-1" min="0" value="<?php echo e(old('total_cases_received', $vawcReport->total_cases_received)); ?>">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Total No. of Cases Acted Upon</label>
                        <input type="number" name="total_cases_acted" class="w-full border rounded px-2 py-1" min="0" value="<?php echo e(old('total_cases_acted', $vawcReport->total_cases_acted)); ?>">
                    </div>
                </div>

                <hr class="my-4">

                
                <h4 class="font-semibold mb-2">Cases</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm" id="casesTable">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="border px-2 py-1">Nature of Case</th>
                                <th class="border px-2 py-1">Subcategory</th>
                                <th class="border px-2 py-1">Victims</th>
                                <th class="border px-2 py-1">Cases</th>
                                <th class="border px-2 py-1">PNP</th>
                                <th class="border px-2 py-1">Court</th>
                                <th class="border px-2 py-1">Hospital</th>
                                <th class="border px-2 py-1">BPO Applied</th>
                                <th class="border px-2 py-1">BPO Issued</th>
                                <th class="border px-2 py-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $vawcReport->cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><input type="text" name="cases[<?php echo e($index); ?>][nature_of_case]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->nature_of_case); ?>" required></td>
                                <td><input type="text" name="cases[<?php echo e($index); ?>][subcategory]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->subcategory); ?>"></td>
                                <td><input type="number" name="cases[<?php echo e($index); ?>][num_victims]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->num_victims); ?>" min="0"></td>
                                <td><input type="number" name="cases[<?php echo e($index); ?>][num_cases]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->num_cases); ?>" min="0"></td>
                                <td><input type="number" name="cases[<?php echo e($index); ?>][ref_pnp]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->ref_pnp); ?>" min="0"></td>
                                <td><input type="number" name="cases[<?php echo e($index); ?>][ref_court]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->ref_court); ?>" min="0"></td>
                                <td><input type="number" name="cases[<?php echo e($index); ?>][ref_hospital]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->ref_hospital); ?>" min="0"></td>
                                <td><input type="number" name="cases[<?php echo e($index); ?>][num_applied_bpo]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->num_applied_bpo); ?>" min="0"></td>
                                <td><input type="number" name="cases[<?php echo e($index); ?>][num_bpo_issued]" class="border rounded px-1 py-1 w-full" value="<?php echo e($case->num_bpo_issued); ?>" min="0"></td>
                                <td><button type="button" class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 remove-case">X</button></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" id="addCase" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Add Case</button>

                <hr class="my-4">

                
                <h4 class="font-semibold mb-2">Programs / Projects / Activities (PPAs)</h4>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border text-sm" id="programsTable">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="border px-2 py-1">PPA Type</th>
                                <th class="border px-2 py-1">Title</th>
                                <th class="border px-2 py-1">Remarks</th>
                                <th class="border px-2 py-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $vawcReport->programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pIndex => $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <select name="programs[<?php echo e($pIndex); ?>][ppa_type]" class="border rounded px-1 py-1 w-full">
                                        <option value="Training" <?php echo e($program->ppa_type == 'Training' ? 'selected' : ''); ?>>Training</option>
                                        <option value="Advocacy" <?php echo e($program->ppa_type == 'Advocacy' ? 'selected' : ''); ?>>Advocacy</option>
                                        <option value="Others" <?php echo e($program->ppa_type == 'Others' ? 'selected' : ''); ?>>Others</option>
                                    </select>
                                </td>
                                <td><input type="text" name="programs[<?php echo e($pIndex); ?>][title]" class="border rounded px-1 py-1 w-full" value="<?php echo e($program->title); ?>"></td>
                                <td><input type="text" name="programs[<?php echo e($pIndex); ?>][remarks]" class="border rounded px-1 py-1 w-full" value="<?php echo e($program->remarks); ?>"></td>
                                <td><button type="button" class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 remove-program">X</button></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" id="addProgram" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Add Program</button>

                <hr class="my-4">

              

                <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update Report</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/secretary/vawc_reports/edit.blade.php ENDPATH**/ ?>