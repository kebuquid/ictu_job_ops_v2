<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>

<div class="p-8 space-y-6 max-w-6xl mx-auto">

  <!-- Header -->
  <div class="fade-in">
    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center shadow-lg">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
      </div>
      Form Option Access Control
    </h2>
    <p class="text-sm text-gray-500 mt-1 ml-[52px]">Control which ticket form options (equipment, request types, platforms, actions) are visible to Employees and Students.</p>
  </div>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="fade-in bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="fade-in bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('super-admin/form-option-access') ?>" method="POST">
    <?= csrf_field() ?>

    <!-- Info banner -->
    <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-5 py-4 mb-6">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
          <p class="text-sm font-semibold text-indigo-800">How it works</p>
          <p class="text-xs text-indigo-600 mt-0.5">Toggle each form option on or off per role. When an option is <strong>disabled</strong>, that role will <strong>not see</strong> it in the ticket creation form's dropdowns. This works in addition to Section Access — the section must also be enabled.</p>
        </div>
      </div>
    </div>

    <!-- Tab navigation -->
    <div class="border-b border-gray-200 mb-6">
      <nav class="-mb-px flex gap-4" id="tabNav">
        <button type="button" data-tab="equipment" class="tab-btn inline-flex items-center gap-2 pb-3 px-1 border-b-2 font-semibold text-sm transition-colors border-indigo-500 text-indigo-600">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
          Equipment
        </button>
        <button type="button" data-tab="request_type" class="tab-btn inline-flex items-center gap-2 pb-3 px-1 border-b-2 font-semibold text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          Request Types
        </button>
        <button type="button" data-tab="request_platform" class="tab-btn inline-flex items-center gap-2 pb-3 px-1 border-b-2 font-semibold text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Request Platforms
        </button>
        <button type="button" data-tab="request_action" class="tab-btn inline-flex items-center gap-2 pb-3 px-1 border-b-2 font-semibold text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          Request Actions
        </button>
      </nav>
    </div>

    <?php
      // Build a section lookup
      $sectionMap = [];
      foreach ($sections as $s) {
          $sectionMap[(int) $s['section_id']] = $s;
      }

      // Build request_type lookup (for platforms which belong to a request_type)
      $requestTypeMap = [];
      foreach ($requestTypes as $rt) {
          $requestTypeMap[(int) $rt['request_type_id']] = $rt;
      }

      // Group equipment by section
      $equipmentBySection = [];
      foreach ($equipment as $e) {
          $equipmentBySection[(int) $e['section_id']][] = $e;
      }

      // Group request types by section
      $requestTypesBySection = [];
      foreach ($requestTypes as $rt) {
          $requestTypesBySection[(int) $rt['section_id']][] = $rt;
      }

      // Group request actions by section
      $requestActionsBySection = [];
      foreach ($requestActions as $ra) {
          $requestActionsBySection[(int) $ra['section_id']][] = $ra;
      }

      // Group platforms by request type → section
      $platformsBySection = [];
      foreach ($requestPlatforms as $rp) {
          $rtId = (int) $rp['request_type_id'];
          $rt = $requestTypeMap[$rtId] ?? null;
          $secId = $rt ? (int) $rt['section_id'] : 0;
          $platformsBySection[$secId][] = $rp;
      }

      $iconColors = [
          'NICM'   => 'from-green-400 to-emerald-600',
          'ICTRAM' => 'from-amber-400 to-orange-600',
          'MIS'    => 'from-purple-400 to-violet-600',
      ];
    ?>

    <!-- ═══ Equipment Tab ═══ -->
    <div id="tab-equipment" class="tab-panel">
      <?php foreach ($sections as $sec): ?>
        <?php $items = $equipmentBySection[(int) $sec['section_id']] ?? []; if (empty($items)) continue; ?>
        <?= view('super_admin/_option_access_section', [
            'sectionLabel' => $sec['acronym'] . ' — ' . $sec['name'],
            'iconColor'    => $iconColors[strtoupper($sec['acronym'])] ?? 'from-blue-400 to-indigo-600',
            'acronym'      => $sec['acronym'],
            'items'        => $items,
            'optionType'   => 'equipment',
            'pkField'      => 'equipment_id',
            'nameField'    => 'name',
            'roles'        => $roles,
            'matrix'       => $matrices['equipment'],
        ]) ?>
      <?php endforeach; ?>
    </div>

    <!-- ═══ Request Types Tab ═══ -->
    <div id="tab-request_type" class="tab-panel hidden">
      <?php foreach ($sections as $sec): ?>
        <?php $items = $requestTypesBySection[(int) $sec['section_id']] ?? []; if (empty($items)) continue; ?>
        <?= view('super_admin/_option_access_section', [
            'sectionLabel' => $sec['acronym'] . ' — ' . $sec['name'],
            'iconColor'    => $iconColors[strtoupper($sec['acronym'])] ?? 'from-blue-400 to-indigo-600',
            'acronym'      => $sec['acronym'],
            'items'        => $items,
            'optionType'   => 'request_type',
            'pkField'      => 'request_type_id',
            'nameField'    => 'request_type_name',
            'roles'        => $roles,
            'matrix'       => $matrices['request_type'],
        ]) ?>
      <?php endforeach; ?>
    </div>

    <!-- ═══ Request Platforms Tab ═══ -->
    <div id="tab-request_platform" class="tab-panel hidden">
      <?php foreach ($sections as $sec): ?>
        <?php $items = $platformsBySection[(int) $sec['section_id']] ?? []; if (empty($items)) continue; ?>
        <?= view('super_admin/_option_access_section', [
            'sectionLabel' => $sec['acronym'] . ' — ' . $sec['name'],
            'iconColor'    => $iconColors[strtoupper($sec['acronym'])] ?? 'from-blue-400 to-indigo-600',
            'acronym'      => $sec['acronym'],
            'items'        => $items,
            'optionType'   => 'request_platform',
            'pkField'      => 'platform_id',
            'nameField'    => 'platform_name',
            'roles'        => $roles,
            'matrix'       => $matrices['request_platform'],
            'parentLabel'  => 'Request Type',
            'parentMap'    => $requestTypeMap,
            'parentFk'     => 'request_type_id',
            'parentName'   => 'request_type_name',
        ]) ?>
      <?php endforeach; ?>
    </div>

    <!-- ═══ Request Actions Tab ═══ -->
    <div id="tab-request_action" class="tab-panel hidden">
      <?php foreach ($sections as $sec): ?>
        <?php $items = $requestActionsBySection[(int) $sec['section_id']] ?? []; if (empty($items)) continue; ?>
        <?= view('super_admin/_option_access_section', [
            'sectionLabel' => $sec['acronym'] . ' — ' . $sec['name'],
            'iconColor'    => $iconColors[strtoupper($sec['acronym'])] ?? 'from-blue-400 to-indigo-600',
            'acronym'      => $sec['acronym'],
            'items'        => $items,
            'optionType'   => 'request_action',
            'pkField'      => 'action_id',
            'nameField'    => 'action_name',
            'roles'        => $roles,
            'matrix'       => $matrices['request_action'],
        ]) ?>
      <?php endforeach; ?>
    </div>

    <!-- Submit -->
    <div class="mt-6 flex items-center justify-between bg-white/80 backdrop-blur-sm rounded-2xl border border-indigo-100/50 shadow-lg px-6 py-4">
      <p class="text-xs text-gray-400">Changes take effect immediately after saving.</p>
      <button type="submit"
              class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-700 hover:from-indigo-700 hover:to-purple-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Save Permissions
      </button>
    </div>
  </form>
</div>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    // Deactivate all tabs
    document.querySelectorAll('.tab-btn').forEach(b => {
      b.classList.remove('border-indigo-500', 'text-indigo-600');
      b.classList.add('border-transparent', 'text-gray-500');
    });
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));

    // Activate clicked
    btn.classList.add('border-indigo-500', 'text-indigo-600');
    btn.classList.remove('border-transparent', 'text-gray-500');
    document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
  });
});

// Live toggle label update
document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
  cb.addEventListener('change', function() {
    const label = this.closest('label').querySelector('span:last-child');
    label.textContent = this.checked ? 'On' : 'Off';
  });
});
</script>

<?= $this->endSection() ?>
