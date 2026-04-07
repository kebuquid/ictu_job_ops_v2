<?= $this->extend('users/layout') ?>

<?= $this->section('pageTitle') ?>My Tickets<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Track Your Submissions<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
 /* --- DataTables Static Light Theme Override --- */

/* 1. The Main Table & Header Cells */
#myTicketsTable, 
#myTicketsTable thead th {
    background-color: #f8fafc !important; /* slate-50 */
    color: #1e293b !important;            /* slate-800 */
    border-color: #e2e8f0 !important;      /* slate-200 */
}

/* 2. Table Body Rows (Static White & Light Gray Striping) */
#myTicketsTable tbody tr {
    background-color: #ffffff !important;
    color: #334155 !important;            /* slate-700 */
}

#myTicketsTable tbody tr.odd {
    background-color: #ffffff !important;
}

#myTicketsTable tbody tr.even {
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

  <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 shadow-lg">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-gray-900 text-lg">All My Tickets</h3>
      <a href="<?= base_url('create-ticket') ?>" class="px-4 py-2 bg-gradient-to-r from-gray-700 to-slate-800 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">+ New Ticket</a>
    </div>
    <div class="overflow-x-auto">
      <table id="myTicketsTable" class="w-full text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">ID</th>
            <th class="pb-3 pr-4">Description</th>
            <th class="pb-3 pr-4">Assigned To</th>
            <th class="pb-3 pr-4">Status</th>
            <th class="pb-3 pr-4">Date</th>
            <th class="pb-3">Details</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach($tickets ?? [] as $ticket): ?>
            <tr class="ticket-row border-b border-gray-50">
              <td class="py-3 pr-4 mono text-xs font-bold text-slate-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></td>
              <td class="py-3 pr-4 text-gray-700 max-w-[200px] truncate"><?= esc($ticket['problem_description'] ?? 'N/A') ?></td>
              <td class="py-3 pr-4 text-gray-600"><?= esc($ticket['staff_name'] ?? 'Unassigned') ?></td>
              <td class="py-3 pr-4">
                <?= \App\Models\JobStatusModel::badge((int) $ticket['job_status']) ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
              </td>
              <td class="py-3 pr-4 mono text-xs text-gray-500"><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
              <td class="py-3">
                <a href="<?= base_url('ticket/' . $ticket['job_ticket_id']) ?>" class="text-xs font-bold text-slate-600 hover:text-slate-800 bg-slate-50 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition-all">View</a>
              </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  if($.fn.dataTable) {
    $('#myTicketsTable').DataTable({
      pageLength: 15,
      order: [[0, 'desc']],
      language: { emptyTable: 'You haven\'t submitted any tickets yet' }
    });
  }
});
</script>
<?= $this->endSection() ?>
