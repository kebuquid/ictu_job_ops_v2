<?= $this->extend('staff/layout') ?>

<?= $this->section('pageTitle') ?>My Tickets<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Assigned to Me<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">

  <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-amber-100/50 shadow-lg">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-gray-900 text-lg">My Assigned Tickets</h3>
      <a href="<?= base_url('create-ticket') ?>" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">+ New Ticket</a>
    </div>
    <div class="overflow-x-auto">
      <table id="myTicketsTable" class="w-full text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">ID</th>
            <th class="pb-3 pr-4">Description</th>
            <th class="pb-3 pr-4">Requestor</th>
            <th class="pb-3 pr-4">Status</th>
            <th class="pb-3 pr-4">Date</th>
            <th class="pb-3">Action</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach($tickets ?? [] as $ticket): ?>
            <tr class="ticket-row border-b border-gray-50">
              <td class="py-3 pr-4 mono text-xs font-bold text-amber-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></td>
              <td class="py-3 pr-4 text-gray-700 max-w-[200px] truncate"><?= esc($ticket['problem_description'] ?? 'N/A') ?></td>
              <td class="py-3 pr-4 text-gray-600"><?= esc($ticket['requestor_name'] ?? 'N/A') ?></td>
              <td class="py-3 pr-4">
                <?= \App\Models\JobStatusModel::badge((int) $ticket['job_status']) ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
              </td>
              <td class="py-3 pr-4 mono text-xs text-gray-500"><?= date('M d, Y', strtotime($ticket['ticket_date'])) ?></td>
              <td class="py-3">
                <div class="flex items-center gap-2">
                  <a href="<?= base_url($urlPrefix . '/ticket/' . $ticket['job_ticket_id']) ?>" class="text-xs font-bold text-amber-600 hover:text-amber-800 bg-amber-50 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition-all inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                  </a>
                  <?php if(in_array((int)$ticket['job_status'], [1, 2, 3])): ?>
                    <a href="<?= base_url($urlPrefix . '/respond/' . $ticket['job_ticket_response_id']) ?>" class="text-xs font-bold text-yellow-700 hover:text-yellow-900 bg-yellow-50 px-3 py-1.5 rounded-lg hover:bg-yellow-100 transition-all">Respond</a>
                    <a href="<?= base_url($urlPrefix . '/transfer/' . $ticket['job_ticket_response_id']) ?>" class="text-xs font-bold text-orange-600 hover:text-orange-800 bg-orange-50 px-3 py-1.5 rounded-lg hover:bg-orange-100 transition-all">Transfer</a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  if($.fn.dataTable) { $('#myTicketsTable').DataTable({ pageLength: 15, order: [[0, 'desc']], language: { emptyTable: 'No tickets assigned to you' } }); }
});
</script>
<?= $this->endSection() ?>
