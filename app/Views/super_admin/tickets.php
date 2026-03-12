<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">

  <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <!-- Page Header -->
  <div class="fade-in delay-1 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-extrabold text-gray-900">All Tickets</h2>
      <p class="text-sm text-gray-500 mt-1">View and manage all job tickets across every section</p>
    </div>
    <div class="flex items-center gap-3">
      <span class="text-sm text-gray-400 font-medium mono"><?= $statusCounts['all'] ?> total ticket<?= $statusCounts['all'] !== 1 ? 's' : '' ?></span>
    </div>
  </div>

  <!-- Status Filter Tabs -->
  <div class="fade-in delay-2 flex flex-wrap gap-2">
    <button data-filter="all" class="filter-btn active-filter px-4 py-2 rounded-xl text-sm font-semibold transition-all">
      All
      <span class="ml-1.5 bg-white/50 px-2 py-0.5 rounded-full text-xs font-bold"><?= $statusCounts['all'] ?></span>
    </button>
    <button data-filter="1" class="filter-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all bg-gray-100 text-gray-600 hover:bg-amber-100 hover:text-amber-700">
      Open
      <span class="ml-1.5 bg-white/50 px-2 py-0.5 rounded-full text-xs font-bold"><?= $statusCounts['open'] ?></span>
    </button>
    <button data-filter="2" class="filter-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-700">
      In Progress
      <span class="ml-1.5 bg-white/50 px-2 py-0.5 rounded-full text-xs font-bold"><?= $statusCounts['in_progress'] ?></span>
    </button>
    <button data-filter="4" class="filter-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all bg-gray-100 text-gray-600 hover:bg-emerald-100 hover:text-emerald-700">
      Completed
      <span class="ml-1.5 bg-white/50 px-2 py-0.5 rounded-full text-xs font-bold"><?= $statusCounts['completed'] ?></span>
    </button>
    <button data-filter="5" class="filter-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-700">
      Closed
      <span class="ml-1.5 bg-white/50 px-2 py-0.5 rounded-full text-xs font-bold"><?= $statusCounts['closed'] ?></span>
    </button>
    <button data-filter="6" class="filter-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all bg-gray-100 text-gray-600 hover:bg-red-100 hover:text-red-700">
      Cancelled
      <span class="ml-1.5 bg-white/50 px-2 py-0.5 rounded-full text-xs font-bold"><?= $statusCounts['cancelled'] ?></span>
    </button>
  </div>

  <!-- Tickets Table -->
  <div class="fade-in delay-3 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <div class="overflow-x-auto">
      <table id="ticketsTable" class="w-full text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">Ticket</th>
            <th class="pb-3 pr-4">Description</th>
            <th class="pb-3 pr-4">Requestor</th>
            <th class="pb-3 pr-4">Section</th>
            <th class="pb-3 pr-4">Assigned To</th>
            <th class="pb-3 pr-4">Priority</th>
            <th class="pb-3 pr-4">Status</th>
            <th class="pb-3 pr-4">Date</th>
            <th class="pb-3">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php

            $priorityMap = [
              1 => ['Low',      'bg-gray-100 text-gray-600'],
              2 => ['Medium',   'bg-blue-100 text-blue-700'],
              3 => ['High',     'bg-orange-100 text-orange-700'],
              4 => ['Critical', 'bg-red-100 text-red-700'],
            ];

            $avatarBgs = ['bg-blue-600','bg-indigo-600','bg-green-600','bg-purple-600','bg-teal-600','bg-rose-600','bg-amber-600','bg-cyan-600'];
          ?>
          <?php foreach ($tickets ?? [] as $ticket): ?>
            <?php
              // Initials
              $rName   = $ticket['requestor_name'] ?? 'Unknown';
              $words   = explode(' ', trim($rName));
              $initials = '';
              foreach ($words as $w) { $initials .= strtoupper($w[0] ?? ''); }
              $initials = substr($initials, 0, 2);
              $colorIdx = abs(crc32($rName) % count($avatarBgs));
              $avatarBg = $avatarBgs[$colorIdx];

              $desc = $ticket['problem_description'] ?? 'No description';
              $descShort = mb_strlen($desc) > 50 ? mb_substr($desc, 0, 50) . '…' : $desc;

              // Priority – prefer priority_name from the join, fallback to numeric map
              if (!empty($ticket['priority_name'])) {
                  $pName  = $ticket['priority_name'];
                  $pLower = strtolower($pName);
                  if (str_contains($pLower, 'critical') || str_contains($pLower, 'urgent')) {
                      $pClass = 'bg-red-100 text-red-700';
                  } elseif (str_contains($pLower, 'high')) {
                      $pClass = 'bg-orange-100 text-orange-700';
                  } elseif (str_contains($pLower, 'medium') || str_contains($pLower, 'normal')) {
                      $pClass = 'bg-blue-100 text-blue-700';
                  } else {
                      $pClass = 'bg-gray-100 text-gray-600';
                  }
              } else {
                  $pData  = $priorityMap[$ticket['priority_level'] ?? 0] ?? ['—', 'bg-gray-50 text-gray-400'];
                  $pName  = $pData[0];
                  $pClass = $pData[1];
              }

              $createdDate = $ticket['created_at'] ? date('M j, Y', strtotime($ticket['created_at'])) : '—';
            ?>
            <tr class="ticket-row border-b border-gray-50" data-status="<?= (int) $ticket['job_status'] ?>">
              <td class="py-3 pr-4">
                <p class="font-semibold text-blue-600 mono text-xs">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></p>
              </td>
              <td class="py-3 pr-4 max-w-[220px]">
                <p class="text-gray-700 text-xs truncate" title="<?= esc($desc) ?>"><?= esc($descShort) ?></p>
              </td>
              <td class="py-3 pr-4">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-lg <?= $avatarBg ?> text-white text-xs flex items-center justify-center font-bold shrink-0"><?= $initials ?></div>
                  <span class="text-gray-700 font-medium text-xs whitespace-nowrap"><?= esc($rName) ?></span>
                </div>
              </td>
              <td class="py-3 pr-4">
                <?php if (!empty($ticket['section_acronym'])): ?>
                  <span class="inline-block px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full"><?= esc($ticket['section_acronym']) ?></span>
                <?php else: ?>
                  <span class="text-xs text-gray-400">—</span>
                <?php endif; ?>
              </td>
              <td class="py-3 pr-4">
                <span class="text-gray-600 text-xs"><?= esc($ticket['staff_name'] ?? 'Unassigned') ?></span>
              </td>
              <td class="py-3 pr-4">
                <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $pClass ?>"><?= esc($pName) ?></span>
              </td>
              <td class="py-3 pr-4">
                <?= \App\Models\JobStatusModel::badge((int) $ticket['job_status']) ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
              </td>
              <td class="py-3 pr-4 mono text-xs text-gray-500 whitespace-nowrap"><?= $createdDate ?></td>
              <td class="py-3">
                <a href="<?= base_url('super-admin/ticket/' . $ticket['job_ticket_id']) ?>" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-all inline-flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  View
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<style>
  .active-filter {
    background: linear-gradient(135deg, #2563eb, #4338ca);
    color: white;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
  }
  .active-filter span {
    background: rgba(255,255,255,0.25);
    color: white;
  }
</style>

<script>
$(document).ready(function() {
  // Initialize DataTable
  var table = $('#ticketsTable').DataTable({
    pageLength: 15,
    order: [[0, 'desc']],
    language: {
      emptyTable: 'No tickets found.',
      zeroRecords: 'No matching tickets found.'
    },
    dom: '<"flex items-center justify-between flex-wrap gap-4 mb-4"lf>rt<"flex items-center justify-between flex-wrap gap-4 mt-4"ip>',
  });

  // Status filter buttons
  var currentFilter = 'all';

  $('.filter-btn').on('click', function() {
    var filter = $(this).data('filter');
    currentFilter = filter;

    // Update active state
    $('.filter-btn').removeClass('active-filter').addClass('bg-gray-100 text-gray-600');
    $(this).addClass('active-filter').removeClass('bg-gray-100 text-gray-600');

    // Apply custom filter
    table.draw();
  });

  // Custom filtering function
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    if (currentFilter === 'all') return true;

    var row = table.row(dataIndex).node();
    var rowStatus = $(row).data('status');
    return rowStatus == currentFilter;
  });
});
</script>
<?= $this->endSection() ?>
