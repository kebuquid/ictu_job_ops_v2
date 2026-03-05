<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>

<div class="p-8 space-y-6 max-w-5xl mx-auto">

  <!-- Header -->
  <div class="fade-in">
    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
      </div>
      Section Access Control
    </h2>
    <p class="text-sm text-gray-500 mt-1 ml-[52px]">Configure which ticket sections each user role can access when creating tickets.</p>
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

  <form action="<?= base_url('super-admin/section-access') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg overflow-hidden">

      <!-- Info banner -->
      <div class="bg-blue-50 border-b border-blue-100 px-6 py-4">
        <div class="flex items-start gap-3">
          <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <div>
            <p class="text-sm font-semibold text-blue-800">How it works</p>
            <p class="text-xs text-blue-600 mt-0.5">Toggle each section on or off per role. When a section is disabled, that role will <strong>not see</strong> the section as an option in the ticket creation form — neither through auto-detection nor manual selection.</p>
          </div>
        </div>
      </div>

      <!-- Matrix table -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Section</th>
              <?php foreach ($roles as $roleId => $roleLabel): ?>
                <th class="text-center px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                  <div class="flex flex-col items-center gap-1">
                    <?php
                      $roleEnum = \App\Enums\UserRole::from($roleId);
                      $badgeColor = match($roleId) {
                        5 => 'bg-gray-100 text-gray-700',
                        6 => 'bg-purple-100 text-purple-700',
                        default => 'bg-blue-100 text-blue-700',
                      };
                    ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $badgeColor ?>">
                      <?= esc($roleLabel) ?>
                    </span>
                    <span class="text-[10px] text-gray-400 font-normal normal-case">Role ID: <?= $roleId ?></span>
                  </div>
                </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($sections as $s): ?>
              <?php
                $iconColor = match(strtoupper($s['acronym'])) {
                  'NICM'   => 'from-green-400 to-emerald-600',
                  'ICTRAM' => 'from-amber-400 to-orange-600',
                  'MIS'    => 'from-purple-400 to-violet-600',
                  default  => 'from-blue-400 to-indigo-600',
                };
              ?>
              <tr class="hover:bg-blue-50/50 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br <?= $iconColor ?> text-white flex items-center justify-center shadow-sm shrink-0">
                      <span class="text-xs font-bold"><?= esc($s['acronym']) ?></span>
                    </div>
                    <div>
                      <p class="text-sm font-bold text-gray-900"><?= esc($s['acronym']) ?></p>
                      <p class="text-xs text-gray-500"><?= esc($s['name']) ?></p>
                    </div>
                  </div>
                </td>
                <?php foreach ($roles as $roleId => $roleLabel): ?>
                  <?php
                    $isEnabled = ($matrix[$roleId][$s['section_id']] ?? 0) === 1;
                    $inputName = 'access[' . $roleId . '_' . $s['section_id'] . ']';
                  ?>
                  <td class="px-6 py-4 text-center">
                    <label class="relative inline-flex items-center cursor-pointer group">
                      <input type="checkbox"
                             name="<?= $inputName ?>"
                             value="1"
                             <?= $isEnabled ? 'checked' : '' ?>
                             class="sr-only peer">
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-200 rounded-full
                                  peer-checked:after:translate-x-full peer-checked:after:border-white
                                  after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white
                                  after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5
                                  after:transition-all peer-checked:bg-blue-600 transition-colors"></div>
                      <span class="ml-2 text-xs font-medium peer-checked:text-blue-600 text-gray-400 transition-colors">
                        <?= $isEnabled ? 'On' : 'Off' ?>
                      </span>
                    </label>
                  </td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Submit -->
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
        <p class="text-xs text-gray-400">Changes take effect immediately after saving.</p>
        <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Save Permissions
        </button>
      </div>
    </div>
  </form>
</div>

<script>
// Live toggle label update
document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
  cb.addEventListener('change', function() {
    const label = this.closest('label').querySelector('span:last-child');
    label.textContent = this.checked ? 'On' : 'Off';
  });
});
</script>

<?= $this->endSection() ?>
