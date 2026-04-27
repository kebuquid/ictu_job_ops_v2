<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Transfer Ticket<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Reassign or Review Request<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-3xl mx-auto">

  <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Ticket Info Card -->
  <div class="fade-in bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-4">Ticket Details</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Ticket ID</span>
        <p class="font-bold text-blue-600 mono">ICTU-<?= date('Y', strtotime($response['created_at'])) ?>-<?= str_pad($response['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></p>
      </div>
      <div>
        <span class="text-gray-400 text-xs uppercase tracking-wider">Currently Assigned To</span>
        <p class="font-semibold text-gray-700"><?= esc($response['assigned_name'] ?? 'Unassigned') ?></p>
      </div>
      <div class="sm:col-span-2">
        <span class="text-gray-400 text-xs uppercase tracking-wider">Problem Description</span>
        <p class="text-gray-700 mt-1"><?= esc($response['problem_description'] ?? 'N/A') ?></p>
      </div>
    </div>
  </div>

  <?php if (!empty($transferRequest)): ?>
    <div class="fade-in delay-1 bg-amber-50/90 backdrop-blur-sm rounded-2xl p-6 border border-amber-200 shadow-lg">
      <h3 class="font-bold text-amber-800 text-lg mb-3">Pending Transfer Request</h3>
      <div class="text-sm text-gray-700 space-y-2">
        <p><span class="font-semibold text-gray-900">Requested By:</span> <?= esc($transferRequest['requested_by_name'] ?? ('Staff #' . (int) $transferRequest['requested_by'])) ?></p>
        <p><span class="font-semibold text-gray-900">Reason:</span> <?= esc($transferRequest['reason'] ?? 'N/A') ?></p>
        <?php if (!empty($transferRequest['suggested_staff_name'])): ?>
          <p><span class="font-semibold text-gray-900">Suggested Assignee:</span> <?= esc($transferRequest['suggested_staff_name']) ?></p>
        <?php endif; ?>
        <p><span class="font-semibold text-gray-900">Requested At:</span> <?= date('M d, Y h:i A', strtotime($transferRequest['created_at'])) ?></p>
      </div>
    </div>
  <?php endif; ?>

  <!-- Transfer Form -->
  <div class="fade-in delay-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-4">Select New Assignee</h3>

    <form action="<?= base_url('admin/transfer/' . $response['job_ticket_response_id']) ?>" method="post">
      <?= csrf_field() ?>
      <?php if (!empty($transferRequest)): ?>
        <input type="hidden" name="transfer_request_id" value="<?= (int) $transferRequest['transfer_request_id'] ?>">
      <?php endif; ?>

      <div class="space-y-3">
        <?php if(empty($employees)): ?>
          <p class="text-gray-500 text-sm italic">No other employees available in this section.</p>
        <?php else: ?>
          <?php foreach($employees as $emp): ?>
          <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50/50 cursor-pointer transition-all group">
            <input type="radio" name="new_staff_id" value="<?= $emp['user_id'] ?>" required class="text-blue-600 focus:ring-blue-500">
            <div class="flex-1">
              <p class="font-semibold text-gray-800 group-hover:text-blue-700"><?= esc($emp['name']) ?></p>
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

      <?php if (!empty($transferRequest)): ?>
        <div class="mt-5 space-y-2">
          <label for="review_note" class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Review Note (Optional)</label>
          <textarea id="review_note" name="review_note" rows="3" class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Optional note for the requester."></textarea>
        </div>
      <?php endif; ?>

      <?php if(!empty($employees)): ?>
      <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-6">
        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">
          <?= !empty($transferRequest) ? 'Approve and Transfer' : 'Transfer Ticket' ?>
        </button>

        <?php if (!empty($transferRequest)): ?>
          <button type="submit" form="rejectTransferRequestForm" class="px-6 py-2.5 bg-red-50 text-red-700 font-semibold text-sm rounded-xl border border-red-200 hover:bg-red-100 transition-all">Reject Request</button>
        <?php endif; ?>

        <a href="<?= base_url('admin/tickets') ?>" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">Cancel</a>
      </div>
      <?php else: ?>
      <div class="mt-6">
        <?php if (!empty($transferRequest)): ?>
          <button type="submit" form="rejectTransferRequestForm" class="px-6 py-2.5 bg-red-50 text-red-700 font-semibold text-sm rounded-xl border border-red-200 hover:bg-red-100 transition-all mr-3">Reject Request</button>
        <?php endif; ?>
        <a href="<?= base_url('admin/tickets') ?>" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">&larr; Back to Tickets</a>
      </div>
      <?php endif; ?>
    </form>

    <?php if (!empty($transferRequest)): ?>
      <form id="rejectTransferRequestForm" action="<?= base_url('admin/transfer-request/' . (int) $transferRequest['transfer_request_id'] . '/reject') ?>" method="post" class="hidden" onsubmit="return confirm('Reject this transfer request?');">
        <?= csrf_field() ?>
      </form>
    <?php endif; ?>
  </div>
</div>
<?= $this->endSection() ?>
