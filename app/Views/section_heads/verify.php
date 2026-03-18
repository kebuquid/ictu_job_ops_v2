<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Verify Responses<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Pending Verification<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 sm:p-6 lg:p-8 space-y-6">

  <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-5">Tickets Pending Verification</h3>

    <?php if(empty($pendingTickets)): ?>
      <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-gray-400 font-medium">All caught up! No tickets pending verification.</p>
      </div>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach($pendingTickets as $ticket): ?>
        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-2">
                <span class="mono text-sm font-bold text-blue-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></span>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Completed</span>
              </div>
              <p class="text-sm text-gray-700 mb-2"><?= esc($ticket['problem_description'] ?? 'N/A') ?></p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-500">
                <div><span class="font-semibold">Handled by:</span> <?= esc($ticket['staff_name']) ?></div>
                <div><span class="font-semibold">Action:</span> <?= esc($ticket['action_performed'] ?? 'N/A') ?></div>
                <?php if(!empty($ticket['completion_date'])): ?>
                <div><span class="font-semibold">Completed:</span> <?= date('M d, Y', strtotime($ticket['completion_date'])) ?></div>
                <?php endif; ?>
                <?php if(!empty($ticket['estimated_cost'])): ?>
                <div><span class="font-semibold">Cost:</span> PHP <?= number_format($ticket['estimated_cost'], 2) ?></div>
                <?php endif; ?>
              </div>
            </div>
            <form action="<?= base_url('admin/verify/' . $ticket['job_ticket_response_id']) ?>" method="POST" class="w-full lg:w-auto lg:ml-4">
              <?= csrf_field() ?>
              <button type="submit" onclick="return confirm('Verify and close this ticket?')"
                class="w-full lg:w-auto px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold text-xs rounded-xl shadow hover:shadow-lg transition-all whitespace-nowrap">
                Verify & Close
              </button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?= $this->endSection() ?>
