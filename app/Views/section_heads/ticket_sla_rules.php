<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Ticket Timeframes<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Section SLA Rules<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
 /* --- DataTables Static Light Theme Override --- */

/* 1. The Main Table & Header Cells */
#slaRulesTable, 
#slaRulesTable thead th {
    background-color: #f8fafc !important; /* slate-50 */
    color: #1e293b !important;            /* slate-800 */
    border-color: #e2e8f0 !important;      /* slate-200 */
}

/* 2. Table Body Rows (Static White & Light Gray Striping) */
#slaRulesTable tbody tr {
    background-color: #ffffff !important;
    color: #334155 !important;            /* slate-700 */
}

#slaRulesTable tbody tr.odd {
    background-color: #ffffff !important;
}

#slaRulesTable tbody tr.even {
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
  <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="fade-in bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
      <p class="text-sm font-semibold text-blue-800">Section Ticket Timeframes</p>
      <p class="text-xs text-blue-600 mt-0.5">Set your section target completion windows. Countdown starts when a ticket enters In Progress.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
      <h3 class="font-bold text-gray-900 text-lg mb-4">Add Rule</h3>
      <form method="POST" action="<?= base_url('admin/ticket-sla-rules/add') ?>" class="space-y-4">
        <?= csrf_field() ?>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Request Type (Optional)</label>
          <select name="request_type_id" class="w-full rounded-xl border-gray-200 text-sm">
            <option value="">Any</option>
            <?php foreach ($requestTypes as $rt): ?>
              <option value="<?= (int) $rt['request_type_id'] ?>" <?= ((string) old('request_type_id') === (string) $rt['request_type_id']) ? 'selected' : '' ?>><?= esc($rt['request_type_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Platform (Optional)</label>
          <select name="platform_id" class="w-full rounded-xl border-gray-200 text-sm">
            <option value="">Any</option>
            <?php foreach ($platforms as $p): ?>
              <option value="<?= (int) $p['platform_id'] ?>" <?= ((string) old('platform_id') === (string) $p['platform_id']) ? 'selected' : '' ?>><?= esc($p['platform_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Action (Optional)</label>
          <select name="action_id" class="w-full rounded-xl border-gray-200 text-sm">
            <option value="">Any</option>
            <?php foreach ($actions as $a): ?>
              <option value="<?= (int) $a['action_id'] ?>" <?= ((string) old('action_id') === (string) $a['action_id']) ? 'selected' : '' ?>><?= esc($a['action_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Equipment (Optional)</label>
          <select name="equipment_id" class="w-full rounded-xl border-gray-200 text-sm">
            <option value="">Any</option>
            <?php foreach ($equipments as $e): ?>
              <option value="<?= (int) $e['equipment_id'] ?>" <?= ((string) old('equipment_id') === (string) $e['equipment_id']) ? 'selected' : '' ?>><?= esc($e['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Target Hours</label>
          <input type="number" min="1" step="1" name="target_hours" value="<?= esc(old('target_hours') ?: '24') ?>" required class="w-full rounded-xl border-gray-200 text-sm" />
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Notes (Optional)</label>
          <input type="text" maxlength="255" name="notes" value="<?= esc(old('notes')) ?>" class="w-full rounded-xl border-gray-200 text-sm" placeholder="e.g., Lab device repair" />
        </div>

        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">Save Rule</button>
      </form>
    </div>

    <div class="xl:col-span-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
      <h3 class="font-bold text-gray-900 text-lg mb-4">Current Section Rules</h3>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-sm" id="slaRulesTable">
          <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
              <th class="pb-3 pr-4">Match Scope</th>
              <th class="pb-3 pr-4">Target</th>
              <th class="pb-3 pr-4">Notes</th>
              <th class="pb-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rules as $r): ?>
              <?php
                $parts = [];
                if (!empty($r['request_type_name'])) $parts[] = 'Type: ' . $r['request_type_name'];
                if (!empty($r['platform_name'])) $parts[] = 'Platform: ' . $r['platform_name'];
                if (!empty($r['action_name'])) $parts[] = 'Action: ' . $r['action_name'];
                if (!empty($r['equipment_name'])) $parts[] = 'Equipment: ' . $r['equipment_name'];
                $scope = empty($parts) ? 'Section default' : implode(' | ', $parts);
              ?>
              <tr class="ticket-row border-b border-gray-50">
                <td class="py-3 pr-4 text-xs text-gray-600"><?= esc($scope) ?></td>
                <td class="py-3 pr-4">
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700"><?= (int) $r['target_hours'] ?> hr</span>
                </td>
                <td class="py-3 pr-4 text-xs text-gray-500"><?= esc($r['notes'] ?? '—') ?></td>
                <td class="py-3">
                  <form method="POST" action="<?= base_url('admin/ticket-sla-rules/delete/' . $r['sla_rule_id']) ?>" onsubmit="return confirm('Delete this timeframe rule?');" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(function () {
  if ($.fn.dataTable) {
    $('#slaRulesTable').DataTable({
      pageLength: 25,
      order: [[0, 'asc']],
      scrollX: true,
      language: { emptyTable: 'No timeframe rules yet for this section.' }
    });
  }
});
</script>
<?= $this->endSection() ?>
