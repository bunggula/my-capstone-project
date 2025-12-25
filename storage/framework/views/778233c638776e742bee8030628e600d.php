

<?php $__env->startSection('content'); ?>
<div class="flex font-sans min-h-screen overflow-hidden">
    
    <div class="w-64 bg-white shadow-lg sticky top-0 h-screen z-20">
        <?php echo $__env->make('partials.captain-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="flex-1 flex flex-col bg-gray-100 h-screen overflow-hidden">
        
        
        <div class="sticky top-0 z-10 bg-white shadow">
            <?php echo $__env->make('partials.captain-header', ['title' => '👥 List of Residents'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <main class="flex-1 overflow-y-auto p-6"
              x-data="{ showModal: false, modalData: {} }">

            
            <h2 class="text-2xl font-bold text-blue-900 mb-4">
                List of Residents – Barangay <?php echo e(Auth::user()->barangay->name ?? 'N/A'); ?>

            </h2>
            
            
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    
    <a href="<?php echo e(route('captain.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => null]))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('status') === 'approved' && !request('gender') ? 'bg-blue-200 border-blue-600 text-blue-900' : 'bg-blue-100 border-blue-500 text-blue-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['all'] ?? 0); ?></p>
            </div>
            <i data-lucide="users" class="w-8 h-8 opacity-60"></i>
        </div>
    </a>

    
    <a href="<?php echo e(route('captain.residents.index', array_merge(request()->query(), ['status' => 'archived', 'gender' => null]))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('status') === 'archived' ? 'bg-gray-200 border-gray-600 text-gray-900' : 'bg-gray-100 border-gray-400 text-gray-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Archived Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['archived'] ?? 0); ?></p>
            </div>
            <i data-lucide="archive" class="w-8 h-8 opacity-60"></i>
        </div>
    </a>

    
    <a href="<?php echo e(route('captain.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => 'Male']))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('gender') === 'Male' ? 'bg-green-200 border-green-600 text-green-900' : 'bg-green-100 border-green-500 text-green-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Male Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['male'] ?? 0); ?></p>
            </div>
            <i data-lucide="mars" class="w-10 h-10 opacity-70 text-green-600"></i>
        </div>
    </a>

    
    <a href="<?php echo e(route('captain.residents.index', array_merge(request()->query(), ['status' => 'approved', 'gender' => 'Female']))); ?>"
       class="block p-4 rounded shadow border-l-4 <?php echo e(request('gender') === 'Female' ? 'bg-pink-200 border-pink-600 text-pink-900' : 'bg-pink-100 border-pink-500 text-pink-700'); ?>">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-sm">Female Residents</h4>
                <p class="text-2xl font-bold"><?php echo e($counts['female'] ?? 0); ?></p>
            </div>
            <i data-lucide="venus" class="w-10 h-10 opacity-70 text-pink-600"></i>
        </div>
    </a>
</div>

<script>
    lucide.createIcons();
</script>






            
            
<form method="GET" action="<?php echo e(route('captain.residents.index')); ?>"
      x-data="{ search: '<?php echo e(request('search')); ?>' }"
      class="mb-6 flex flex-col md:flex-row gap-4 items-start md:items-center">

    
    <div class="relative w-full sm:w-72">
        <input type="text"
               name="search"
               x-model="search"
               @input.debounce.500ms="$root.submit()"
               placeholder="Search by name"
               class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" />

        <button type="button"
                x-show="search"
                @click="search = ''; $nextTick(() => $root.submit())"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-600 text-base focus:outline-none">
            &times;
        </button>
    </div>

    
    <select id="combinedFilter"
        class="w-full md:w-1/4 px-4 py-2 rounded border border-gray-300 text-blue-900">
        <option value="">-- Filter by Category/Voter --</option>
        <optgroup label="Category">
            <option value="category:Senior" <?php echo e(request('category') == 'Senior' ? 'selected' : ''); ?>>Senior Citizen</option>
            <option value="category:PWD" <?php echo e(request('category') == 'PWD' ? 'selected' : ''); ?>>Person with Disability</option>
            <option value="category:Indigenous" <?php echo e(request('category') == 'Indigenous' ? 'selected' : ''); ?>>Indigenous People</option>
            <option value="category:Single Parent" <?php echo e(request('category') == 'Single Parent' ? 'selected' : ''); ?>>Single Parent</option>
            <option value="category:Minor" <?php echo e(request('category') == 'Minor' ? 'selected' : ''); ?>>Minor (Under 18)</option>
            <option value="category:Adult" <?php echo e(request('category') == 'Adult' ? 'selected' : ''); ?>>Adult (18+)</option>
        </optgroup>
        <optgroup label="Voter">
            <option value="voter:yes" <?php echo e(request('voter') == 'yes' ? 'selected' : ''); ?>>Voters</option>
            <option value="voter:no" <?php echo e(request('voter') == 'no' ? 'selected' : ''); ?>>Non-Voters</option>
        </optgroup>
    </select>

    
    <div class="flex items-center mt-2 md:mt-0" x-show="search || '<?php echo e(request('category')); ?>' || '<?php echo e(request('voter')); ?>'" x-cloak>
        <a href="<?php echo e(route('captain.residents.index')); ?>"
           class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
           Clear
        </a>
    </div>

    
    <a href="<?php echo e(route('captain.residents.print', [
            'search' => request('search'),
            'category' => request('category'),
            'voter' => request('voter')
        ])); ?>"
       target="_blank"
       class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-2 transition ml-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5h20v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
        </svg>
        Print
    </a>

</form>

<script>
document.getElementById('combinedFilter').addEventListener('change', function() {
    const form = this.closest('form');
    const value = this.value;

    // Remove any existing hidden inputs
    form.querySelectorAll('input[name="category"], input[name="voter"]').forEach(el => el.remove());

    if(value.includes('category:')) {
        const category = value.split(':')[1];
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'category';
        input.value = category;
        form.appendChild(input);
    } else if(value.includes('voter:')) {
        const voter = value.split(':')[1];
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'voter';
        input.value = voter;
        form.appendChild(input);
    }

    form.submit();
});
</script>



            
            <div class="bg-white shadow rounded-lg p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border text-sm text-left">
                    <thead class="bg-blue-600 text-white uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Full Name</th>
                            <th class="px-4 py-3">Birthdate</th>
                            <th class="px-4 py-3">Civil Status</th>
                         
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-blue-900">
                        <?php $__empty_1 = true; $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($loop->odd ? 'bg-white' : 'bg-gray-100'); ?> border-t">
                            <td class="px-4 py-2"><?php echo e($loop->iteration); ?></td>
                            <td class="px-4 py-2">
                                <?php echo e($resident->first_name); ?> <?php echo e($resident->middle_name); ?> <?php echo e($resident->last_name); ?> <?php echo e($resident->suffix); ?>

                            </td>
                            <td class="px-4 py-2"><?php echo e($resident->birthdate); ?></td>
                            <td class="px-4 py-2"><?php echo e($resident->civil_status); ?></td>
                          
                            <td class="px-4 py-2">
                            <button 
    @click="modalData = <?php echo e(json_encode($resident)); ?>; showModal = true"
    class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700"
    title="View">
    <!-- Eye Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" 
         fill="none" viewBox="0 0 24 24" 
         stroke-width="1.5" stroke="currentColor" 
         class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M2.25 12s3.75-6.75 9.75-6.75 9.75 6.75 9.75 6.75-3.75 6.75-9.75 6.75S2.25 12 2.25 12z" />
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
</button>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center px-4 py-6 text-gray-500">No residents found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-6">
    <?php echo e($residents->appends(request()->query())->links()); ?>

</div>

            </div>

            
            <?php echo $__env->make('partials.resident-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/captain/residents/index.blade.php ENDPATH**/ ?>