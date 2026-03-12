<?= $this->extend('ictu-staff/layout') ?>

<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>My Overview<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="fade-in delay-1 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-emerald-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        </div>
        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Assigned</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['total'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Total Assigned</p>
    </div>

    <div class="fade-in delay-2 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-emerald-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Active</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['active'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Active / In Progress</p>
    </div>

    <div class="fade-in delay-3 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-emerald-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Done</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['completed'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Completed</p>
    </div>

    <div class="fade-in delay-4 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-emerald-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Pending</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['needs_response'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Needs My Response</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- My Active Tickets -->
    <div class="fade-in delay-3 lg:col-span-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-emerald-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-gray-900">My Active Tickets</h3>
        <a href="<?= base_url('ictu-staff/my-tickets') ?>" class="text-xs text-emerald-600 hover:underline font-semibold">View All →</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
              <th class="pb-3 pr-4">ID</th>
              <th class="pb-3 pr-4">Description</th>
              <th class="pb-3 pr-4">Status</th>
              <th class="pb-3 pr-4">Date</th>
              <th class="pb-3">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($activeTickets)): ?>
              <tr><td colspan="5" class="py-8 text-center text-gray-400">No active tickets assigned to you</td></tr>
            <?php else: ?>
              <?php foreach($activeTickets as $ticket): ?>
              <tr class="ticket-row border-b border-gray-50">
                <td class="py-3 pr-4 mono text-xs font-bold text-emerald-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td class="py-3 pr-4 text-gray-700 max-w-[200px] truncate"><?= esc($ticket['problem_description'] ?? 'N/A') ?></td>
                <td class="py-3 pr-4">
                  <?= \App\Models\JobStatusModel::badge((int) $ticket['job_status']) ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
                </td>
                <td class="py-3 pr-4 mono text-xs text-gray-500"><?= date('M d', strtotime($ticket['created_at'])) ?></td>
                <td class="py-3">
                  <a href="<?= base_url('ictu-staff/respond/' . $ticket['job_ticket_response_id']) ?>" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition-all">Respond</a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-6">
      <div class="fade-in delay-4 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-emerald-100/50 shadow-lg">
        <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
        <div class="space-y-2">
          <a href="<?= base_url('ictu-staff/my-tickets') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-green-700 text-white font-semibold text-sm shadow-lg hover:shadow-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            View My Tickets
          </a>
          <a href="<?= base_url('create-ticket') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 font-semibold text-sm hover:bg-emerald-100 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create New Ticket
          </a>
        </div>
      </div>

      <!-- Performance Summary -->
      <div class="fade-in delay-5 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-emerald-100/50 shadow-lg">
        <h3 class="font-bold text-gray-900 mb-4">My Performance</h3>
        <div class="space-y-4">
          <div>
            <div class="flex justify-between text-xs mb-1">
              <span class="text-gray-500">Completion Rate</span>
              <span class="font-bold text-gray-700"><?= $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 ?>%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
              <div class="bg-gradient-to-r from-emerald-500 to-green-500 h-2 rounded-full transition-all" style="width: <?= $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 ?>%"></div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3 text-center">
            <div class="bg-emerald-50 rounded-xl p-3">
              <p class="text-xl font-extrabold text-emerald-700"><?= $stats['completed'] ?></p>
              <p class="text-xs text-gray-500">Resolved</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-3">
              <p class="text-xl font-extrabold text-amber-700"><?= $stats['active'] ?></p>
              <p class="text-xs text-gray-500">In Queue</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
