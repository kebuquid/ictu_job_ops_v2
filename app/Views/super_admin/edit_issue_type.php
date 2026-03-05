<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-2xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('super-admin/issue-types') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Issue Types
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Edit Issue Type</h2>
    <p class="text-sm text-gray-500 mt-1">Update the issue type details below.</p>
  </div>

  <!-- Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (isset($validation)): ?>
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
        <?= $validation->listErrors() ?>
      </div>
    <?php endif; ?>

    <!-- Info Header -->
    <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-400 to-pink-600 text-white flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <p class="text-lg font-extrabold text-gray-900"><?= esc($issueType['issue_type_name']) ?></p>
        <p class="text-xs text-gray-400 mono">Issue Type ID: <?= esc($issueType['issue_type_id']) ?></p>
      </div>
    </div>

    <form action="<?= base_url('super-admin/issue-types/edit/' . $issueType['issue_type_id']) ?>" method="POST" class="space-y-5">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <!-- Issue Type Name -->
      <div>
        <label for="issueTypeName" class="block text-sm font-semibold text-gray-700 mb-2">Issue Type Name</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <input
            type="text"
            name="issue_type_name"
            id="issueTypeName"
            required
            value="<?= old('issue_type_name', $issueType['issue_type_name']) ?>"
            placeholder="e.g. Hardware Failure, Software Bug..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all"
          >
        </div>
      </div>

      <!-- Domain -->
      <div>
        <label for="issueTypeDomain" class="block text-sm font-semibold text-gray-700 mb-2">Domain</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <input
            type="text"
            name="issue_type_domain"
            id="issueTypeDomain"
            required
            value="<?= old('issue_type_domain', $issueType['issue_type_domain']) ?>"
            placeholder="e.g. IT, Facilities, HR..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all"
          >
        </div>
      </div>

      <!-- Section -->
      <div>
        <label for="sectionId" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          </div>
          <select
            name="section_id"
            id="sectionId"
            required
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none"
          >
            <option value="">— Select Section —</option>
            <?php foreach ($sections as $section): ?>
              <option value="<?= esc($section['section_id']) ?>" <?= old('section_id', $issueType['section_id']) == $section['section_id'] ? 'selected' : '' ?>>
                <?= esc($section['acronym']) ?> — <?= esc($section['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
        <textarea
          name="description"
          id="description"
          rows="3"
          placeholder="Brief description of this issue type…"
          class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all resize-none"
        ><?= old('description', $issueType['description']) ?></textarea>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-2">
        <a href="<?= base_url('super-admin/issue-types') ?>"
           class="flex-1 text-center px-6 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors">
          Cancel
        </a>
        <button
          type="submit"
          class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all inline-flex items-center justify-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Save Changes
        </button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
