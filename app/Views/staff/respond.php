<?= $this->extend('staff/layout') ?>

<?= $this->section('pageTitle') ?>Respond to Ticket<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>ICTU-<?= date('Y', strtotime($response['created_at'])) ?>-<?= str_pad($response['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
@keyframes tipSlideIn {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>
<div class="p-8 max-w-4xl mx-auto space-y-6">

  <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Ticket Details Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-amber-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
      <div class="md:col-span-2">
        <span class="text-gray-400 text-xs uppercase tracking-wider">Problem Description</span>
        <p class="text-gray-700 mt-1"><?= esc($response['problem_description'] ?? 'N/A') ?></p>
      </div>
      <?php if(!empty($response['hardware_issues'])): ?>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Hardware Issues</span>
        <p class="text-gray-700"><?= esc($response['hardware_issues']) ?></p>
      </div>
      <?php endif; ?>
      <?php $softwareIssues = $response['software_issues'] ?? ($response['sofware_issues'] ?? null); ?>
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
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Attached File</span>
        <a href="<?= base_url($response['additional_request_file']) ?>" target="_blank" class="text-blue-600 hover:underline text-sm">View Attachment</a>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Response Form -->
  <form action="<?= base_url($urlPrefix . '/respond/' . $response['job_ticket_response_id']) ?>" method="POST" class="fade-in delay-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-amber-100/50 shadow-lg space-y-5">
    <?= csrf_field() ?>

    <h3 class="font-bold text-gray-900 flex items-center gap-2">
      <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Submit Your Response
    </h3>

    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-1">Action Performed <span class="text-red-500">*</span></label>
      <textarea name="action_performed" rows="4" required
        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all resize-none"
        placeholder="Describe the actions you performed to resolve this ticket..."><?= esc($response['action_performed'] ?? '') ?></textarea>
    </div>

    <!-- Completion Status -->
    <div>
      <label class="block text-sm font-semibold text-gray-700 mb-2">Completion Status <span class="text-red-500">*</span></label>
      <div class="flex flex-wrap gap-3">
        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-blue-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
          <input type="radio" name="completion_status" value="in_progress" <?= ($response['completion_status'] ?? '') === 'in_progress' ? 'checked' : '' ?> class="text-blue-600 focus:ring-blue-400">
          <span class="text-sm font-medium text-gray-700">Still In Progress</span>
        </label>
        <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-emerald-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
          <input type="radio" name="completion_status" value="completed" <?= ($response['completion_status'] ?? '') === 'completed' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-400">
          <span class="text-sm font-medium text-gray-700">Completed</span>
        </label>
      </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
      <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all">
        Submit Response
      </button>
      <a href="<?= base_url($urlPrefix . '/my-tickets') ?>" class="px-6 py-3 bg-gray-100 text-gray-600 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-all">
        Cancel
      </a>
    </div>
  </form>
</div>



<?= $this->endSection() ?>
