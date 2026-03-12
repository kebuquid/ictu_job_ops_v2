<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-2xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('super-admin/sections') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Sections
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Add Section</h2>
    <p class="text-sm text-gray-500 mt-1">Register a new section in the system.</p>
  </div>

  <!-- Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6">

    <!-- Flash / Validation Messages -->
    <?php if (session()->getFlashdata('error')): ?>
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (isset($validation)): ?>
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
        <?= $validation->listErrors() ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('super-admin/sections/add') ?>" method="POST" class="space-y-5">
      <?= csrf_field() ?>

      <!-- Acronym -->
      <div>
        <label for="sectionAcronym" class="block text-sm font-semibold text-gray-700 mb-2">Acronym <span class="text-red-500">*</span></label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
          </div>
          <input
            type="text"
            name="acronym"
            id="sectionAcronym"
            required
            maxlength="20"
            value="<?= old('acronym') ?>"
            placeholder="e.g. ICT, HR, FINANCE..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all uppercase"
            style="text-transform:uppercase"
          >
        </div>
        <p class="text-xs text-gray-400 mt-1">Short identifier for the section. Will be stored in uppercase.</p>
      </div>

      <!-- Section Name -->
      <div>
        <label for="sectionName" class="block text-sm font-semibold text-gray-700 mb-2">Section Name <span class="text-red-500">*</span></label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <input
            type="text"
            name="name"
            id="sectionName"
            required
            maxlength="255"
            value="<?= old('name') ?>"
            placeholder="e.g. Information and Communication Technology Unit"
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all"
          >
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="sectionDescription" class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
        <textarea
          name="description"
          id="sectionDescription"
          rows="4"
          maxlength="1000"
          placeholder="Brief description of this section's role or responsibilities..."
          class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all resize-none"
        ><?= old('description') ?></textarea>
      </div>

      <!-- Submit -->
      <div class="pt-2">
        <button
          type="submit"
          class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all inline-flex items-center justify-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Add Section
        </button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
