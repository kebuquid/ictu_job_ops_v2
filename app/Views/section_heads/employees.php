<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Section Employees<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Staff & Technicians<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
 /* --- DataTables Static Light Theme Override --- */

/* 1. The Main Table & Header Cells */
#employeesTable, 
#employeesTable thead th {
    background-color: #f8fafc !important; /* slate-50 */
    color: #1e293b !important;            /* slate-800 */
    border-color: #e2e8f0 !important;      /* slate-200 */
}

/* 2. Table Body Rows (Static White & Light Gray Striping) */
#employeesTable tbody tr {
    background-color: #ffffff !important;
    color: #334155 !important;            /* slate-700 */
}

#employeesTable tbody tr.odd {
    background-color: #ffffff !important;
}

#employeesTable tbody tr.even {
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
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-5">Section Employees</h3>
    <div class="overflow-x-auto">
      <table id="employeesTable" class="w-full min-w-[700px] text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">Name</th>
            <th class="pb-3 pr-4">Email</th>
            <th class="pb-3 pr-4">Role</th>
            <th class="pb-3">Section</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($employees)): ?>
            <?php foreach($employees as $emp): ?>
            <tr class="ticket-row border-b border-gray-50">
              <td class="py-3 pr-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs"><?= $emp['initials'] ?></div>
                  <span class="font-semibold text-gray-800"><?= esc($emp['name']) ?></span>
                </div>
              </td>
              <td class="py-3 pr-4 text-gray-600"><?= esc($emp['email']) ?></td>
              <td class="py-3 pr-4">
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-<?= $emp['role_color'] ?>-100 text-<?= $emp['role_color'] ?>-700"><?= $emp['role'] ?></span>
              </td>
              <td class="py-3 text-gray-600"><?= esc($emp['acronym'] ?? $emp['section_name'] ?? 'N/A') ?></td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="py-8 text-center text-gray-400">No employees found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  if($.fn.dataTable && $('#employeesTable tbody tr').length > 0 && !$('#employeesTable tbody tr td[colspan]').length) {
    $('#employeesTable').DataTable({ pageLength: 15, scrollX: true });
  }
});
</script>
<?= $this->endSection() ?>
