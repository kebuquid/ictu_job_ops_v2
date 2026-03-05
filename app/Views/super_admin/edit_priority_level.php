<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-2xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('super-admin/priority-levels') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Priority Levels
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Edit Priority Level</h2>
    <p class="text-sm text-gray-500 mt-1">Update the priority level details below.</p>
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
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-400 to-rose-600 text-white flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      </div>
      <div>
        <p class="text-lg font-extrabold text-gray-900"><?= esc($priorityLevel['priority_name']) ?></p>
        <p class="text-xs text-gray-400 mono">Priority Level ID: <?= esc($priorityLevel['priority_level_id']) ?></p>
      </div>
    </div>

    <form action="<?= base_url('super-admin/priority-levels/edit/' . $priorityLevel['priority_level_id']) ?>" method="POST" class="space-y-5">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

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
            value="<?= old('priority_name', $priorityLevel['priority_name']) ?>"
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
            value="<?= old('operation_status', $priorityLevel['operation_status']) ?>"
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
        ><?= old('description', $priorityLevel['description']) ?></textarea>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-2">
        <a href="<?= base_url('super-admin/priority-levels') ?>"
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
