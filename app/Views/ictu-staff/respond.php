<?= $this->extend('ictu-staff/layout') ?>

<?= $this->section('pageTitle') ?>Respond to Ticket<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>ICTU-<?= date('Y', strtotime($response['created_at'])) ?>-<?= str_pad($response['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
@keyframes tipSlideIn {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>
<div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto space-y-6">

  <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Ticket Details Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-emerald-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Ticket Details
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Ticket ID</span>
        <p class="font-bold text-gray-800 mono">ICTU-<?= date('Y', strtotime($response['created_at'])) ?>-<?= str_pad($response['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></p>
      </div>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Requestor</span>
        <p class="font-semibold text-gray-800"><?= esc($response['requestor_name'] ?? 'N/A') ?></p>
      </div>
      <?php if(!empty($response['requestor_account_no'])): ?>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Requestor Account No.</span>
        <p class="mono text-sm text-gray-700"><?= esc($response['requestor_account_no']) ?></p>
      </div>
      <?php endif; ?>
      <?php if(!empty($response['requestor_email'])): ?>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Requestor Email</span>
        <p class="text-gray-700 break-all"><?= esc($response['requestor_email']) ?></p>
      </div>
      <?php endif; ?>
      <?php if(!empty($response['requestor_phone_number'])): ?>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Requestor Contact</span>
        <p class="text-gray-700"><?= esc($response['requestor_phone_number']) ?></p>
      </div>
      <?php endif; ?>
      <div class="md:col-span-2">
        <span class="text-gray-400 text-xs uppercase tracking-wider">Problem Description</span>
        <p class="text-gray-700 mt-1"><?= esc($response['problem_description'] ?? 'N/A') ?></p>
      </div>
      <?php if(!empty($asset)): ?>
      <div class="md:col-span-2 bg-emerald-50/50 p-4 rounded-xl border border-emerald-100">
        <span class="text-emerald-700 text-xs font-bold uppercase tracking-wider block mb-2">Linked Asset Information</span>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
          <div>
            <span class="text-gray-500 text-[10px] uppercase">Asset Tag</span>
            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['asset_tag'] ?? 'N/A') ?></p>
          </div>
          <div>
            <span class="text-gray-500 text-[10px] uppercase">Property No.</span>
            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['property_no'] ?? 'N/A') ?></p>
          </div>
          <div>
            <span class="text-gray-500 text-[10px] uppercase">Brand/Model</span>
            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['brand_model'] ?? 'N/A') ?></p>
          </div>
          <div>
            <span class="text-gray-500 text-[10px] uppercase">Serial Number</span>
            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['serial_number'] ?? 'N/A') ?></p>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php if(!empty($response['hardware_issues_text'])): ?>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Hardware Issues</span>
        <p class="text-gray-700"><?= esc($response['hardware_issues_text']) ?></p>
      </div>
      <?php elseif(!empty($response['hardware_issues'])): ?>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Hardware Issues</span>
        <p class="text-gray-700"><?= esc($response['hardware_issues']) ?></p>
      </div>
      <?php endif; ?>
      <?php $softwareIssues = $response['software_issues_text'] ?? ($response['software_issues'] ?? ($response['sofware_issues'] ?? null)); ?>
      <?php if(!empty($softwareIssues)): ?>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Software Issues</span>
        <p class="text-gray-700"><?= esc($softwareIssues) ?></p>
      </div>
      <?php endif; ?>
      <?php if(!empty($response['additional_details'])): ?>
      <div class="md:col-span-2">
        <span class="text-gray-400 text-xs uppercase tracking-wider">Additional Details</span>
        <p class="text-gray-700"><?= esc($response['additional_details']) ?></p>
      </div>
      <?php endif; ?>
      <?php if(!empty($response['additional_request_file'])): ?>
      <div class="md:col-span-2">
        <span class="text-gray-400 text-xs uppercase tracking-wider block mb-2">Attached File</span>
        <?php 
          $fileExt = strtolower(pathinfo($response['additional_request_file'], PATHINFO_EXTENSION));
          $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
        ?>
        <?php if ($isImage): ?>
          <div class="mt-2 group relative inline-block">
            <img src="<?= base_url($response['additional_request_file']) ?>" 
                 alt="Attached File" 
                 class="max-w-md rounded-lg shadow-md border border-gray-200 cursor-zoom-in hover:shadow-xl transition-shadow">
            <a href="<?= base_url($response['additional_request_file']) ?>" 
               target="_blank" 
               class="absolute bottom-2 right-2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
          </div>
        <?php else: ?>
          <a href="<?= base_url($response['additional_request_file']) ?>" target="_blank" class="text-blue-600 hover:underline text-sm">View Attachment</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <?php if(!empty($response['pre_repair_form'])): ?>
      <div class="md:col-span-2">
        <span class="text-gray-400 text-xs uppercase tracking-wider block mb-2">Pre-Repair Form</span>
        <?php 
          $fileExt = strtolower(pathinfo($response['pre_repair_form'], PATHINFO_EXTENSION));
          $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
        ?>
        <?php if ($isImage): ?>
          <div class="mt-2 group relative inline-block">
            <img src="<?= base_url($response['pre_repair_form']) ?>" 
                 alt="Pre-Repair Form" 
                 class="max-w-md rounded-lg shadow-md border border-gray-200 cursor-zoom-in hover:shadow-xl transition-shadow">
            <a href="<?= base_url($response['pre_repair_form']) ?>" 
               target="_blank" 
               class="absolute bottom-2 right-2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
          </div>
        <?php else: ?>
          <a href="<?= base_url($response['pre_repair_form']) ?>" target="_blank" class="text-blue-600 hover:underline text-sm">View Pre-Repair Form</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Response Form -->
  <form action="<?= base_url($urlPrefix . '/respond/' . $response['job_ticket_response_id']) ?>" method="POST" class="fade-in delay-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-emerald-100/50 shadow-lg space-y-5">
    <?= csrf_field() ?>

    <h3 class="font-bold text-gray-900 flex items-center gap-2">
      <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Submit Your Response
    </h3>

    <!-- Action Performed -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Action Performed <span class="text-red-500">*</span></label>
      <textarea name="action_performed" rows="4" required
        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all resize-none"
        placeholder="Describe the actions you performed to resolve this ticket..."><?= esc($response['action_performed'] ?? '') ?></textarea>
    </div>

    <!-- Parts Replaced / Used -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Parts Replaced / Used</label>
      <div id="parts-container" class="space-y-3">
        <?php if (!empty($existingParts)): ?>
          <?php foreach ($existingParts as $i => $part): ?>
          <div class="part-row flex flex-wrap items-start gap-2 bg-gray-50/80 rounded-xl p-3 border border-gray-200">
            <div class="flex-1 min-w-[120px]">
              <label class="block text-xs text-gray-500 mb-1">Type</label>
              <select name="parts[<?= $i ?>][part_type]" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="replaced" <?= ($part['part_type'] ?? '') === 'replaced' ? 'selected' : '' ?>>Replaced</option>
                <option value="used" <?= ($part['part_type'] ?? '') === 'used' ? 'selected' : '' ?>>Used</option>
              </select>
            </div>
            <div class="flex-[2] min-w-[160px]">
              <label class="block text-xs text-gray-500 mb-1">Part Name</label>
              <input type="text" name="parts[<?= $i ?>][part_name]" value="<?= esc($part['part_name'] ?? '') ?>"
                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="e.g. RAM Module">
            </div>
            <div class="w-20">
              <label class="block text-xs text-gray-500 mb-1">Qty</label>
              <input type="number" name="parts[<?= $i ?>][quantity]" value="<?= (int)($part['quantity'] ?? 1) ?>" min="1"
                class="part-qty w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>
            <div class="w-28">
              <label class="block text-xs text-gray-500 mb-1">Unit Cost (₱)</label>
              <input type="number" step="0.01" min="0" name="parts[<?= $i ?>][unit_cost]" value="<?= esc($part['unit_cost'] ?? '') ?>"
                class="part-cost w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="0.00">
            </div>
            <div class="flex items-end pb-1">
              <button type="button" onclick="this.closest('.part-row').remove(); computeTotal();"
                class="mt-5 p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Remove">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <button type="button" id="add-part-btn"
        class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-xl border border-emerald-200 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Part
      </button>
    </div>

    <!-- Estimated Cost (auto-computed) -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Estimated Total Cost</label>
      <div class="flex items-center gap-3">
        <div class="relative flex-1">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">₱</span>
          <input type="number" step="0.01" min="0" name="estimated_cost" id="estimated_cost" value="<?= esc($response['estimated_cost'] ?? '') ?>"
            class="w-full rounded-xl border border-gray-200 pl-8 pr-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
            placeholder="0.00" readonly>
        </div>
        <span class="text-xs text-gray-400 italic">Auto-computed from parts</span>
      </div>
    </div>

    <!-- Completion Date -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Completion Date</label>
      <input type="date" name="completion_date" value="<?= esc($response['completion_date'] ?? '') ?>"
        class="w-full md:w-1/2 rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all">
    </div>

    <!-- Completion Status -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Completion Status <span class="text-red-500">*</span></label>
      <div class="flex flex-wrap gap-3">
        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-blue-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
          <input type="radio" name="completion_status" value="in_progress" <?= ($response['completion_status'] ?? '') === 'in_progress' ? 'checked' : '' ?> class="text-blue-600 focus:ring-blue-400">
          <span class="text-sm font-medium text-gray-700">Still In Progress</span>
        </label>
        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-amber-50 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
          <input type="radio" name="completion_status" value="waiting_for_parts" <?= ($response['completion_status'] ?? '') === 'waiting_for_parts' ? 'checked' : '' ?> class="text-amber-600 focus:ring-amber-400">
          <span class="text-sm font-medium text-gray-700">Waiting for Parts</span>
        </label>
        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-emerald-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
          <input type="radio" name="completion_status" value="completed" <?= ($response['completion_status'] ?? '') === 'completed' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-400">
          <span class="text-sm font-medium text-gray-700">Completed</span>
        </label>
      </div>
    </div>

    <!-- Submit -->
    <div class="flex items-center gap-3 pt-2">
      <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-700 text-white font-bold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all">
        Submit Response
      </button>
      <a href="<?= base_url($urlPrefix . '/my-tickets') ?>" class="px-6 py-3 bg-gray-100 text-gray-600 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-all">
        Cancel
      </a>
    </div>
  </form>
</div>

<script>
(function() {
  let partIndex = <?= !empty($existingParts) ? count($existingParts) : 0 ?>;

  function partRowHtml(idx) {
    return `
    <div class="part-row flex flex-wrap items-start gap-2 bg-gray-50/80 rounded-xl p-3 border border-gray-200" style="animation: tipSlideIn .3s ease">
      <div class="flex-1 min-w-[120px]">
        <label class="block text-xs text-gray-500 mb-1">Type</label>
        <select name="parts[${idx}][part_type]" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
          <option value="replaced">Replaced</option>
          <option value="used">Used</option>
        </select>
      </div>
      <div class="flex-[2] min-w-[160px]">
        <label class="block text-xs text-gray-500 mb-1">Part Name</label>
        <input type="text" name="parts[${idx}][part_name]"
          class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="e.g. RAM Module">
      </div>
      <div class="w-20">
        <label class="block text-xs text-gray-500 mb-1">Qty</label>
        <input type="number" name="parts[${idx}][quantity]" value="1" min="1"
          class="part-qty w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-emerald-400">
      </div>
      <div class="w-28">
        <label class="block text-xs text-gray-500 mb-1">Unit Cost (₱)</label>
        <input type="number" step="0.01" min="0" name="parts[${idx}][unit_cost]"
          class="part-cost w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-right focus:outline-none focus:ring-2 focus:ring-emerald-400" placeholder="0.00">
      </div>
      <div class="flex items-end pb-1">
        <button type="button" onclick="this.closest('.part-row').remove(); computeTotal();"
          class="mt-5 p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Remove">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
      </div>
    </div>`;
  }

  document.getElementById('add-part-btn').addEventListener('click', function() {
    const container = document.getElementById('parts-container');
    container.insertAdjacentHTML('beforeend', partRowHtml(partIndex++));
    // attach listeners to new inputs
    bindPartListeners();
  });

  window.computeTotal = function() {
    let total = 0;
    document.querySelectorAll('.part-row').forEach(row => {
      const qty  = parseFloat(row.querySelector('.part-qty')?.value) || 0;
      const cost = parseFloat(row.querySelector('.part-cost')?.value) || 0;
      total += qty * cost;
    });
    document.getElementById('estimated_cost').value = total > 0 ? total.toFixed(2) : '';
  };

  function bindPartListeners() {
    document.querySelectorAll('.part-qty, .part-cost').forEach(el => {
      el.removeEventListener('input', window.computeTotal);
      el.addEventListener('input', window.computeTotal);
    });
  }

  // Initial binding & compute
  bindPartListeners();
  computeTotal();
})();
</script>

<?= $this->endSection() ?>
