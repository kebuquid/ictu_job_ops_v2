<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-2xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('super-admin/priority-levels') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Priority Levels
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Add Priority Level</h2>
    <p class="text-sm text-gray-500 mt-1">Register a new priority level in the system.</p>
  </div>

  <!-- Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6">

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if (isset($validation)): ?>
      <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
        <?= $validation->listErrors() ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('super-admin/priority-levels/add') ?>" method="POST" class="space-y-5">
      <?= csrf_field() ?>

      <!-- Priority Name -->
      <div>
        <label for="priorityName" class="block text-sm font-semibold text-gray-700 mb-2">Priority Name</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <input
            type="text"
            name="priority_name"
            id="priorityName"
            required
            value="<?= old('priority_name') ?>"
            placeholder="e.g. Critical, High, Medium, Low..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all"
          >
        </div>
      </div>

      <!-- Operation Status -->
      <div>
        <label for="operationStatus" class="block text-sm font-semibold text-gray-700 mb-2">Operation Status</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <input
            type="text"
            name="operation_status"
            id="operationStatus"
            required
            value="<?= old('operation_status') ?>"
            placeholder="e.g. Active, Inactive..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all"
          >
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
        <textarea
          name="description"
          id="description"
          rows="3"
          placeholder="Brief description of this priority level…"
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
          Add Priority Level
        </button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
