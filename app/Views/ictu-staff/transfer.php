<?= $this->extend('ictu-staff/layout') ?>

<?= $this->section('pageTitle') ?>Transfer Ticket<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Transfer to Another Employee<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-3xl mx-auto">

  <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Ticket Info Card -->
  <div class="fade-in bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-emerald-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-4">Ticket Details</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Ticket ID</span>
        <p class="font-bold text-emerald-600 mono">ICTU-<?= date('Y', strtotime($response['created_at'])) ?>-<?= str_pad($response['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></p>
      </div>
      <div class="col-span-2">
        <span class="text-gray-400 text-xs uppercase tracking-wider">Problem Description</span>
        <p class="text-gray-700 mt-1"><?= esc($response['problem_description'] ?? 'N/A') ?></p>
      </div>
    </div>
  </div>

  <!-- Transfer Form -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-emerald-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-4">Select New Assignee</h3>

    <form action="<?= base_url($urlPrefix . '/transfer/' . $response['job_ticket_response_id']) ?>" method="post">
      <?= csrf_field() ?>

      <div class="space-y-3">
        <?php if(empty($employees)): ?>
          <p class="text-gray-500 text-sm italic">No other employees available in this section.</p>
        <?php else: ?>
          <?php foreach($employees as $emp): ?>
          <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-emerald-300 hover:bg-emerald-50/50 cursor-pointer transition-all group">
            <input type="radio" name="new_staff_id" value="<?= $emp['user_id'] ?>" required class="text-emerald-600 focus:ring-emerald-500">
            <div class="flex-1">
              <p class="font-semibold text-gray-800 group-hover:text-emerald-700"><?= esc($emp['name']) ?></p>
              <p class="text-xs text-gray-500"><?= esc($emp['email'] ?? '') ?></p>
            </div>
            <div class="text-right">
              <span class="text-xs font-bold px-2 py-1 rounded-full <?= ($emp['active_tickets'] ?? 0) > 3 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' ?>">
                <?= $emp['active_tickets'] ?? 0 ?> active ticket<?= ($emp['active_tickets'] ?? 0) !== 1 ? 's' : '' ?>
              </span>
            </div>
          </label>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php if(!empty($employees)): ?>
      <div class="flex items-center gap-3 mt-6">
        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-green-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">
          Transfer Ticket
        </button>
        <a href="<?= base_url($urlPrefix . '/my-tickets') ?>" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">Cancel</a>
      </div>
      <?php else: ?>
      <div class="mt-6">
        <a href="<?= base_url($urlPrefix . '/my-tickets') ?>" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">&larr; Back to My Tickets</a>
      </div>
      <?php endif; ?>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
