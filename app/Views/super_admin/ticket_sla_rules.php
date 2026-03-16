<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="fade-in bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div>
      <p class="text-sm font-semibold text-blue-800">Ticket Timeframe Rules (SLA)</p>
      <p class="text-xs text-blue-600 mt-0.5">Set expected completion windows by section and request metadata. Countdown starts when the ticket status becomes In Progress.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
      <h3 class="font-bold text-gray-900 text-lg mb-4">Add Timeframe Rule</h3>
      <form method="POST" action="<?= base_url('super-admin/ticket-sla-rules/add') ?>" class="space-y-4">
        <?= csrf_field() ?>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Section</label>
          <select name="section_id" required class="w-full rounded-xl border-gray-200 text-sm">
            <option value="">Select section</option>
            <?php foreach ($sections as $s): ?>
              <option value="<?= (int) $s['section_id'] ?>" <?= ((string) old('section_id') === (string) $s['section_id']) ? 'selected' : '' ?>><?= esc($s['acronym']) ?> - <?= esc($s['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

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
          <input type="text" maxlength="255" name="notes" value="<?= esc(old('notes')) ?>" class="w-full rounded-xl border-gray-200 text-sm" placeholder="e.g., Critical account recovery" />
        </div>

        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">Save Rule</button>
      </form>
    </div>

    <div class="xl:col-span-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
      <h3 class="font-bold text-gray-900 text-lg mb-4">Configured Timeframe Rules</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-sm" id="slaRulesTable">
          <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
              <th class="pb-3 pr-4">Section</th>
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
                <td class="py-3 pr-4">
                  <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full"><?= esc($r['acronym'] ?? 'N/A') ?></span>
                </td>
                <td class="py-3 pr-4 text-xs text-gray-600"><?= esc($scope) ?></td>
                <td class="py-3 pr-4">
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700"><?= (int) $r['target_hours'] ?> hr</span>
                </td>
                <td class="py-3 pr-4 text-xs text-gray-500"><?= esc($r['notes'] ?? '—') ?></td>
                <td class="py-3">
                  <form method="POST" action="<?= base_url('super-admin/ticket-sla-rules/delete/' . $r['sla_rule_id']) ?>" onsubmit="return confirm('Delete this timeframe rule?');" class="inline">
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
      language: { emptyTable: 'No timeframe rules yet.' }
    });
  }
});
</script>
<?= $this->endSection() ?>
