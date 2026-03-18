<?= $this->extend('students/layout') ?>

<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>My Tickets<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 sm:p-6 lg:p-8 space-y-6">

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="fade-in delay-1 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-purple-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        </div>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['total'] ?></p>
      <p class="text-xs text-gray-500 mt-1">My Total Tickets</p>
    </div>

    <div class="fade-in delay-2 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-purple-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['open'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Open / Pending</p>
    </div>

    <div class="fade-in delay-3 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-purple-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['in_progress'] ?></p>
      <p class="text-xs text-gray-500 mt-1">In Progress</p>
    </div>

    <div class="fade-in delay-4 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-purple-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['resolved'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Resolved / Closed</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- My Recent Tickets -->
    <div class="fade-in delay-3 lg:col-span-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-purple-100/50 shadow-lg">
      <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
        <h3 class="font-bold text-gray-900">My Recent Tickets</h3>
        <a href="<?= base_url('student/my-tickets') ?>" class="text-xs text-purple-600 hover:underline font-semibold">View All →</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[680px] text-sm">
          <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
              <th class="pb-3 pr-4">ID</th>
              <th class="pb-3 pr-4">Description</th>
              <th class="pb-3 pr-4">Assigned To</th>
              <th class="pb-3 pr-4">Status</th>
              <th class="pb-3">Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($recentTickets)): ?>
              <tr><td colspan="5" class="py-8 text-center text-gray-400">You haven't submitted any tickets yet</td></tr>
            <?php else: ?>
              <?php foreach($recentTickets as $ticket): ?>
              <tr class="ticket-row border-b border-gray-50">
                <td class="py-3 pr-4 mono text-xs font-bold text-purple-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td class="py-3 pr-4 text-gray-700 max-w-[200px] truncate"><?= esc($ticket['problem_description'] ?? 'N/A') ?></td>
                <td class="py-3 pr-4 text-gray-600"><?= esc($ticket['staff_name'] ?? 'Pending') ?></td>
                <td class="py-3 pr-4">
                  <?= \App\Models\JobStatusModel::badge((int) $ticket['job_status']) ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
                </td>
                <td class="py-3 mono text-xs text-gray-500"><?= date('M d', strtotime($ticket['created_at'])) ?></td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-6">
      <div class="fade-in delay-4 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-purple-100/50 shadow-lg">
        <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
        <div class="space-y-2">
          <a href="<?= base_url('student/create-ticket') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-violet-700 text-white font-semibold text-sm shadow-lg hover:shadow-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Submit New Ticket
          </a>
          <a href="<?= base_url('student/my-tickets') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-purple-50 text-purple-700 font-semibold text-sm hover:bg-purple-100 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Track My Tickets
          </a>
        </div>
      </div>

      <!-- Ticket Status Summary -->
      <div class="fade-in delay-5 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-purple-100/50 shadow-lg">
        <h3 class="font-bold text-gray-900 mb-4">Status Summary</h3>
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
              <span class="text-sm text-gray-600">Open</span>
            </div>
            <span class="font-bold text-gray-800"><?= $stats['open'] ?></span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
              <span class="text-sm text-gray-600">In Progress</span>
            </div>
            <span class="font-bold text-gray-800"><?= $stats['in_progress'] ?></span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
              <span class="text-sm text-gray-600">Resolved</span>
            </div>
            <span class="font-bold text-gray-800"><?= $stats['resolved'] ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
