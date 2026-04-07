<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<style>
 /* --- DataTables Static Light Theme Override --- */

/* 1. The Main Table & Header Cells */
#keywordRulesTable, 
#keywordRulesTable thead th {
    background-color: #f8fafc !important; /* slate-50 */
    color: #1e293b !important;            /* slate-800 */
    border-color: #e2e8f0 !important;      /* slate-200 */
}

/* 2. Table Body Rows (Static White & Light Gray Striping) */
#keywordRulesTable tbody tr {
    background-color: #ffffff !important;
    color: #334155 !important;            /* slate-700 */
}

#keywordRulesTable tbody tr.odd {
    background-color: #ffffff !important;
}

#keywordRulesTable tbody tr.even {
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
  <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Info Banner -->
  <div class="fade-in bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
      <p class="text-sm font-semibold text-blue-800">Keyword Detection Rules</p>
      <p class="text-xs text-blue-600 mt-0.5">These keywords are used to automatically detect which section a ticket should be routed to. When a user types their problem description, matching keywords trigger section suggestions and troubleshooting tips.</p>
    </div>
  </div>

  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-gray-900 text-lg">All Keyword Rules</h3>
      <a href="<?= base_url('super-admin/keyword-rules/add') ?>"
         class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">+ Add Keyword</a>
    </div>

    <!-- Section filter tabs -->
    <div class="flex flex-wrap gap-2 mb-4">
      <button type="button" class="section-filter-btn active px-3 py-1.5 text-xs font-bold rounded-full bg-blue-600 text-white transition-all" data-section="all">All</button>
      <?php
        $sectionsSeen = [];
        foreach ($keywordRules as $r) {
          $acr = $r['acronym'] ?? '';
          if ($acr && !in_array($acr, $sectionsSeen)) {
            $sectionsSeen[] = $acr;
            echo '<button type="button" class="section-filter-btn px-3 py-1.5 text-xs font-bold rounded-full bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-700 transition-all" data-section="' . esc($acr) . '">' . esc($acr) . '</button>';
          }
        }
      ?>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm" id="keywordRulesTable">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">#</th>
            <th class="pb-3 pr-4">Keyword</th>
            <th class="pb-3 pr-4">Section</th>
            <th class="pb-3 pr-4">Tip Title</th>
            <th class="pb-3 pr-4">Status</th>
            <th class="pb-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($keywordRules as $item): ?>
            <tr class="ticket-row border-b border-gray-50" data-section="<?= esc($item['acronym'] ?? '') ?>">
              <td class="py-3 pr-4">
                <span class="text-gray-400 font-semibold text-xs"><?= esc($item['id']) ?></span>
              </td>
              <td class="py-3 pr-4">
                <div class="flex items-center gap-2">
                  <?php if ($item['is_default']): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                      Default Tip
                    </span>
                  <?php else: ?>
                    <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-800 text-xs font-mono font-bold rounded-full"><?= esc($item['keyword']) ?></span>
                  <?php endif; ?>
                </div>
              </td>
              <td class="py-3 pr-4">
                <?php
                  $badgeColor = match(strtoupper($item['acronym'] ?? '')) {
                    'NICM'   => 'bg-green-100 text-green-700',
                    'ICTRAM' => 'bg-amber-100 text-amber-700',
                    'MIS'    => 'bg-purple-100 text-purple-700',
                    default  => 'bg-gray-100 text-gray-700',
                  };
                ?>
                <span class="inline-block px-2.5 py-1 <?= $badgeColor ?> text-xs font-bold rounded-full"><?= esc($item['acronym'] ?? '—') ?></span>
              </td>
              <td class="py-3 pr-4">
                <span class="text-gray-600 text-xs"><?= esc(mb_strimwidth($item['tip_title'] ?? '—', 0, 40, '…')) ?></span>
              </td>
              <td class="py-3 pr-4">
                <?php if ($item['is_active']): ?>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Active
                  </span>
                <?php else: ?>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Inactive
                  </span>
                <?php endif; ?>
              </td>
              <td class="py-3">
                <div class="flex items-center gap-2">
                  <a href="<?= base_url('super-admin/keyword-rules/edit/' . $item['id']) ?>"
                     class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit
                  </a>
                  <button type="button"
                          onclick="confirmDelete(<?= $item['id'] ?>, '<?= esc($item['is_default'] ? 'Default tip for ' . ($item['acronym'] ?? '') : $item['keyword'], 'js') ?>')"
                          class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 transform transition-all">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
      </div>
      <div>
        <h4 class="font-bold text-gray-900">Delete Keyword Rule</h4>
        <p class="text-xs text-gray-500">This action cannot be undone.</p>
      </div>
    </div>
    <p class="text-sm text-gray-600 mb-5">Are you sure you want to delete <strong id="deleteItemName" class="text-gray-900"></strong>?</p>
    <form id="deleteForm" method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="DELETE">
      <div class="flex gap-3">
        <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition-colors">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors">Delete</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function () {
  if($.fn.dataTable) {
    $('#keywordRulesTable').DataTable({
      pageLength: 25,
      order: [[2, 'asc'], [1, 'asc']],
      language: { emptyTable: 'No keyword rules found. Add one to get started.' }
    });
  }

  // Section filter tabs
  $('.section-filter-btn').on('click', function () {
    const section = $(this).data('section');
    $('.section-filter-btn').removeClass('active bg-blue-600 text-white').addClass('bg-gray-100 text-gray-600');
    $(this).addClass('active bg-blue-600 text-white').removeClass('bg-gray-100 text-gray-600');

    if (section === 'all') {
      $('#keywordRulesTable tbody tr').show();
    } else {
      $('#keywordRulesTable tbody tr').each(function () {
        $(this).toggle($(this).data('section') === section);
      });
    }
  });
});

function confirmDelete(id, name) {
  $('#deleteItemName').text(name);
  $('#deleteForm').attr('action', '<?= base_url("super-admin/keyword-rules/delete") ?>/' + id);
  $('#deleteModal').removeClass('hidden').addClass('flex');
}
function closeDeleteModal() {
  $('#deleteModal').removeClass('flex').addClass('hidden');
}
$('#deleteModal').on('click', function (e) {
  if (e.target === this) closeDeleteModal();
});
</script>
<?= $this->endSection() ?>
