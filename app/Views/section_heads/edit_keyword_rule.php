<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Keyword Rules<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Edit Keyword<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-2xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('admin/keyword-rules') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Keyword Rules
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Edit Keyword Rule</h2>
    <p class="text-sm text-gray-500 mt-1">Update keyword detection and troubleshooting tip.</p>
  </div>

  <!-- Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6">

    <?php if (session()->getFlashdata('success')): ?>
      <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (isset($validation)): ?>
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
        <?= $validation->listErrors() ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/keyword-rules/edit/' . $rule['id']) ?>" method="POST" class="space-y-5">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <!-- Is Default Toggle -->
      <label class="flex items-center gap-3 cursor-pointer select-none group">
        <div class="relative">
          <input type="checkbox" id="isDefaultToggle" name="is_default" value="1" class="sr-only peer" <?= $rule['is_default'] ? 'checked' : '' ?>>
          <div class="w-10 h-6 bg-gray-200 rounded-full peer-checked:bg-indigo-600 transition-colors"></div>
          <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-4"></div>
        </div>
        <div>
          <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">Default / Fallback Tip</span>
          <p class="text-xs text-gray-400">Check this if this is the catch-all tip for this section.</p>
        </div>
      </label>

      <!-- Keyword -->
      <div id="keywordField" <?= $rule['is_default'] ? 'style="display:none"' : '' ?>>
        <label for="keyword" class="block text-sm font-semibold text-gray-700 mb-2">Keyword</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
          </div>
          <input type="text" name="keyword" id="keyword"
                 value="<?= esc($rule['is_default'] ? '' : $rule['keyword']) ?>"
                 placeholder="e.g. internet, printer, login…"
                 class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all">
        </div>
      </div>

      <!-- Tip Title -->
      <div>
        <label for="tipTitle" class="block text-sm font-semibold text-gray-700 mb-2">Tip Title <span class="text-gray-400 font-normal">(optional)</span></label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          </div>
          <input type="text" name="tip_title" id="tipTitle"
                 value="<?= esc($rule['tip_title'] ?? '') ?>"
                 placeholder="e.g. No Internet?"
                 class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all">
        </div>
      </div>

      <!-- Tip Body -->
      <div>
        <label for="tipBody" class="block text-sm font-semibold text-gray-700 mb-2">Tip Body <span class="text-gray-400 font-normal">(optional)</span></label>
        <textarea name="tip_body" id="tipBody" rows="3"
                  placeholder="Helpful troubleshooting advice shown to the user before they submit..."
                  class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all resize-none"><?= esc($rule['tip_body'] ?? '') ?></textarea>
      </div>

      <!-- Active Toggle -->
      <label class="flex items-center gap-3 cursor-pointer select-none group">
        <div class="relative">
          <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= $rule['is_active'] ? 'checked' : '' ?>>
          <div class="w-10 h-6 bg-gray-200 rounded-full peer-checked:bg-green-600 transition-colors"></div>
          <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-4"></div>
        </div>
        <div>
          <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">Active</span>
          <p class="text-xs text-gray-400">Inactive keywords are not used for detection.</p>
        </div>
      </label>

      <!-- Submit -->
      <div class="pt-2">
        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all inline-flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Save Changes
        </button>
      </div>
    </form>
  </div>

  <!-- Meta info -->
  <div class="fade-in delay-2 text-center">
    <p class="text-xs text-gray-400">
      Created: <?= esc($rule['created_at'] ?? '—') ?> &nbsp;•&nbsp; Updated: <?= esc($rule['updated_at'] ?? '—') ?>
    </p>
  </div>
</div>

<script>
$(document).ready(function () {
  $('#isDefaultToggle').on('change', function () {
    if ($(this).is(':checked')) {
      $('#keywordField').slideUp(200);
      $('#keyword').removeAttr('required').val('');
    } else {
      $('#keywordField').slideDown(200);
      $('#keyword').attr('required', 'required');
    }
  });
});
</script>
<?= $this->endSection() ?>
