<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Section Tickets<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>All Tickets<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
 /* --- DataTables Static Light Theme Override --- */

/* 1. The Main Table & Header Cells */
#ticketsTable, 
#ticketsTable thead th {
    background-color: #f8fafc !important; /* slate-50 */
    color: #1e293b !important;            /* slate-800 */
    border-color: #e2e8f0 !important;      /* slate-200 */
}

/* 2. Table Body Rows (Static White & Light Gray Striping) */
#ticketsTable tbody tr {
    background-color: #ffffff !important;
    color: #334155 !important;            /* slate-700 */
}

#ticketsTable tbody tr.odd {
    background-color: #ffffff !important;
}

#ticketsTable tbody tr.even {
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

  <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <div class="fade-in delay-1 bg-white backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
      <h3 class="font-bold text-gray-900 text-lg">All Section Tickets</h3>
      <a href="<?= base_url('create-ticket') ?>" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">+ New Ticket</a>
    </div>
    <div class="overflow-x-auto">
      <table id="ticketsTable" class="w-full min-w-[1100px] text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">ID</th>
            <th class="pb-3 pr-4">Description</th>
            <th class="pb-3 pr-4">Requestor</th>
            <th class="pb-3 pr-4">Assigned To</th>
            <th class="pb-3 pr-4">Priority</th>
            <th class="pb-3 pr-4">Status</th>
            <th class="pb-3 pr-4">Transfer Request</th>
            <th class="pb-3 pr-4">Date</th>
            <th class="pb-3">Action</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach($tickets ?? [] as $ticket): ?>
            <tr class="ticket-row border-b border-gray-50">
              <td class="py-3 pr-4 mono text-xs font-bold text-blue-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></td>
              <td class="py-3 pr-4 text-gray-700 max-w-[200px] truncate"><?= esc($ticket['problem_description'] ?? 'N/A') ?></td>
              <td class="py-3 pr-4 text-gray-600"><?= esc($ticket['requestor_name'] ?? 'N/A') ?></td>
              <td class="py-3 pr-4 text-gray-600"><?= esc($ticket['staff_name'] ?? 'Unassigned') ?></td>
              <td class="py-3 pr-4">
                <?php
                  $pMap = [1 => ['Low','bg-gray-100 text-gray-600'], 2 => ['Medium','bg-amber-100 text-amber-700'], 3 => ['High','bg-orange-100 text-orange-700'], 4 => ['Critical','bg-red-100 text-red-700']];
                  $p = $pMap[$ticket['priority_level'] ?? 0] ?? ['—','bg-gray-50 text-gray-400'];
                ?>
                <span class="text-xs font-bold px-2 py-1 rounded-full <?= $p[1] ?>"><?= $p[0] ?></span>
              </td>
              <td class="py-3 pr-4">
                <?= \App\Models\JobStatusModel::badge((int) $ticket['job_status']) ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
              </td>
              <td class="py-3 pr-4 text-xs">
                <?php $pendingRequest = $ticket['pending_transfer_request'] ?? null; ?>
                <?php if (!empty($pendingRequest)): ?>
                  <span class="inline-flex items-center px-2 py-1 rounded-full font-bold bg-amber-100 text-amber-700">Pending</span>
                  <div class="mt-1 text-[11px] text-gray-500">By: <?= esc($pendingRequest['requested_by_name'] ?? ('Staff #' . (int) $pendingRequest['requested_by'])) ?></div>
                <?php else: ?>
                  <span class="inline-flex items-center px-2 py-1 rounded-full font-bold bg-gray-100 text-gray-600">None</span>
                <?php endif; ?>
              </td>
              <td class="py-3 mono text-xs text-gray-500"><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
              <td class="py-3">
                <div class="flex items-center gap-2">
                  <a href="<?= base_url('admin/ticket/' . $ticket['job_ticket_id']) ?>" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-all inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                  </a>
                  <?php if(!empty($pendingRequest)): ?>
                    <a href="<?= base_url('admin/transfer/' . $ticket['job_ticket_response_id'] . '?transfer_request_id=' . (int) $pendingRequest['transfer_request_id']) ?>" class="text-xs font-bold text-amber-700 hover:text-amber-900 bg-amber-100 px-3 py-1.5 rounded-lg hover:bg-amber-200 transition-all">Review Request</a>
                  <?php endif; ?>
                  <?php if(!empty($ticket['job_ticket_response_id']) && in_array((int)$ticket['job_status'], [1, 2, 3])): ?>
                    <a href="<?= base_url('admin/transfer/' . $ticket['job_ticket_response_id']) ?>" class="text-xs font-bold text-orange-600 hover:text-orange-800 bg-orange-50 px-3 py-1.5 rounded-lg hover:bg-orange-100 transition-all">Transfer</a>
                  <?php endif; ?>
                </div>
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
    $('#ticketsTable').DataTable({ pageLength: 15, order: [[0, 'desc']], scrollX: true, language: { emptyTable: 'No tickets found' } });
  }
});
</script>
<?= $this->endSection() ?>
