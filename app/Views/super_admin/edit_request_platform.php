<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-2xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('super-admin/request-platforms') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Request Platforms
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Edit Request Platform</h2>
    <p class="text-sm text-gray-500 mt-1">Update the request platform details below.</p>
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
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 text-white flex items-center justify-center shadow-lg">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      </div>
      <div>
        <p class="text-lg font-extrabold text-gray-900"><?= esc($requestPlatform['platform_name']) ?></p>
        <p class="text-xs text-gray-400 mono">Platform ID: <?= esc($requestPlatform['platform_id']) ?></p>
      </div>
    </div>

    <form action="<?= base_url('super-admin/request-platforms/edit/' . $requestPlatform['platform_id']) ?>" method="POST" class="space-y-5">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <!-- Platform Name -->
      <div>
        <label for="platformName" class="block text-sm font-semibold text-gray-700 mb-2">Platform Name</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </div>
          <input
            type="text"
            name="platform_name"
            id="platformName"
            required
            value="<?= old('platform_name', $requestPlatform['platform_name']) ?>"
            placeholder="e.g. Web Portal, Mobile App, Email..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all"
          >
        </div>
      </div>

      <!-- Request Type -->
      <div>
        <label for="requestTypeId" class="block text-sm font-semibold text-gray-700 mb-2">Request Type</label>
        <div class="relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          </div>
          <select
            name="request_type_id"
            id="requestTypeId"
            required
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none"
          >
            <option value="">— Select Request Type —</option>
            <?php foreach ($requestTypes as $requestType): ?>
              <option value="<?= esc($requestType['request_type_id']) ?>" <?= old('request_type_id', $requestPlatform['request_type_id']) == $requestType['request_type_id'] ? 'selected' : '' ?>>
                <?= esc($requestType['request_type_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="platformDescription" class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
        <textarea
          name="platform_description"
          id="platformDescription"
          rows="3"
          placeholder="Brief description of this request platform…"
          class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all resize-none"
        ><?= old('platform_description', $requestPlatform['platform_description']) ?></textarea>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-2">
        <a href="<?= base_url('super-admin/request-platforms') ?>"
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
