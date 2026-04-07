<?= $this->extend('super_admin/layout')?>

<?= $this->section('content') ?>
<style>
 /* --- DataTables Static Light Theme Override --- */

/* 1. The Main Table & Header Cells */
#usersTable, 
#usersTable thead th {
    background-color: #f8fafc !important; /* slate-50 */
    color: #1e293b !important;            /* slate-800 */
    border-color: #e2e8f0 !important;      /* slate-200 */
}

/* 2. Table Body Rows (Static White & Light Gray Striping) */
#usersTable tbody tr {
    background-color: #ffffff !important;
    color: #334155 !important;            /* slate-700 */
}

#usersTable tbody tr.odd {
    background-color: #ffffff !important;
}

#usersTable tbody tr.even {
    background-color: #f9fafb !important; /* gray-50 */
}

/* 3. Search Input & Length Dropdown */
#dt-search-0, 
#dt-length-0,
.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
    background-color: #ffffff !important;
    color: #1e293b !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
}

/* 4. Pagination Buttons */
.dt-paging .pagination a, 
.dt-paging .pagination span,
.dataTables_wrapper .dataTables_paginate .paginate_button {
    background-color: #ffffff !important;
    color: #475569 !important;            /* slate-600 */
    border: 1px solid #e2e8f0 !important;
}

/* 5. Active/Selected Page Button */
.dt-paging .pagination a[aria-current="page"],
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #eff6ff !important; /* blue-50 */
    color: #2563eb !important;            /* blue-600 */
    border-color: #bfdbfe !important;      /* blue-200 */
    font-weight: 700 !important;
}

/* 6. Disabled Buttons (Previous/Next when inactive) */
.dt-paging .pagination a[aria-disabled="true"],
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background-color: #ffffff !important;
    color: #cbd5e1 !important;            /* slate-300 */
    cursor: not-allowed !important;
}

/* 7. Footer Info Text ("Showing 1 to 7...") */
.dt-info, 
.dataTables_wrapper .dataTables_info {
    color: #64748b !important;            /* slate-500 */
}

/* 8. Fix for the horizontal scroll area background */
.dt-scroll-body {
    background-color: #ffffff !important;
}
</style>
<div class="p-8 space-y-6">

  <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-gray-900 text-lg">ICTU Employees</h3>
      <a href="<?= base_url('super-admin/employees/add') ?>"
         class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">+ Add Employee</a>
    </div>
    <div class="overflow-x-auto">
            <table class="w-full text-sm" id="usersTable">
                <thead>
                    <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="pb-3 pr-4">Name</th>
                        <th class="pb-3 pr-4">Email</th>
                        <th class="pb-3 pr-4">Role/Position</th>
                        <th class="pb-3 pr-4">Section</th>
                        <th class="pb-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees ?? [] as $employee): ?>
                    <tr class="ticket-row border-b border-gray-50">
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-600 text-white text-xs flex items-center justify-center font-bold shrink-0">
                                    <?php if($employee['avatar']): ?>
                                    <img src="<?= esc($employee['avatar']) ?>" alt="Avatar" class="w-full h-full object-cover rounded-lg">
                                    <?php else: ?>
                                    <?= esc($employee['initials']) ?>
                                    <?php endif; ?>
                                </div>
                                <span class="text-gray-700 font-medium text-xs whitespace-nowrap"><?= esc($employee['name']) ?></span>
                            </div>
                        </td>
                        <td class="py-3 pr-4">
                            <p class="font-semibold text-gray-800 mono text-xs"><?= esc($employee['email']) ?></p>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-block px-2.5 py-1
                                         bg-<?= esc($employee['role_color']) ?>-100 text-<?= esc($employee['role_color']) ?>-600
                                         text-xs font-bold rounded-full whitespace-nowrap">
                                <?= esc($employee['role']) ?>
                            </span>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-block px-2.5 py-1 bg-amber-100 text-amber-600 text-xs font-bold rounded-full"><?= esc($employee['acronym']) ?></span>
                        </td>
                        <td class="py-3">
                            <a href="<?= base_url('super-admin/employees/edit/' . $employee['user_id']) ?>" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-all">
                                Profile
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    if($.fn.dataTable) {
        $('#usersTable').DataTable({
            pageLength: 15,
            order: [[0, 'desc']],
            language: {
                emptyTable: 'No employees found.'
            }
        });
    }
});
</script>
<?= $this->endSection() ?>