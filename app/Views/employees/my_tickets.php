<?= $this->extend('employees/layout') ?>

<?= $this->section('pageTitle') ?>My Tickets<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Track Your Submissions<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">

  <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 shadow-lg">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-gray-900 text-lg">All My Tickets</h3>
      <a href="<?= base_url('employee/create-ticket') ?>" class="px-4 py-2 bg-gradient-to-r from-gray-700 to-slate-800 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">+ New Ticket</a>
    </div>
    <div class="overflow-x-auto">
      <table id="myTicketsTable" class="w-full text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">ID</th>
            <th class="pb-3 pr-4">Description</th>
            <th class="pb-3 pr-4">Assigned To</th>
            <th class="pb-3 pr-4">Status</th>
            <th class="pb-3 pr-4">Date</th>
            <th class="pb-3">Details</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach($tickets ?? [] as $ticket): ?>
            <tr class="ticket-row border-b border-gray-50">
              <td class="py-3 pr-4 mono text-xs font-bold text-slate-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></td>
              <td class="py-3 pr-4 text-gray-700 max-w-[200px] truncate"><?= esc($ticket['problem_description'] ?? 'N/A') ?></td>
              <td class="py-3 pr-4 text-gray-600"><?= esc($ticket['staff_name'] ?? 'Unassigned') ?></td>
              <td class="py-3 pr-4">
                <?= \App\Models\JobStatusModel::badge((int) $ticket['job_status']) ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
              </td>
              <td class="py-3 pr-4 mono text-xs text-gray-500"><?= date('M d, Y', strtotime($ticket['created_at'])) ?></td>
              <td class="py-3">
                <a href="<?= base_url('employee/ticket/' . $ticket['job_ticket_id']) ?>" class="text-xs font-bold text-slate-600 hover:text-slate-800 bg-slate-50 px-3 py-1.5 rounded-lg hover:bg-slate-100 transition-all">View</a>
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
  if($.fn.dataTable) {
    $('#myTicketsTable').DataTable({
      pageLength: 15,
      order: [[0, 'desc']],
      language: { emptyTable: 'You haven\'t submitted any tickets yet' }
    });
  }
});
</script>
<?= $this->endSection() ?>
