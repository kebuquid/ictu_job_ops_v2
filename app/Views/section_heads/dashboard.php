<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Section Overview<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <!-- Section Tickets -->
    <div class="fade-in delay-1 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-blue-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        </div>
        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Section</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['total'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Total Section Tickets</p>
    </div>

    <!-- Open -->
    <div class="fade-in delay-2 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-blue-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Pending</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['open'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Open Tickets</p>
    </div>

    <!-- In Progress -->
    <div class="fade-in delay-3 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-blue-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <span class="text-xs font-bold text-cyan-600 bg-cyan-50 px-2 py-1 rounded-full">Active</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['in_progress'] ?></p>
      <p class="text-xs text-gray-500 mt-1">In Progress</p>
    </div>

    <!-- Completed -->
    <div class="fade-in delay-4 stat-card bg-white/80 backdrop-blur-sm rounded-2xl p-5 border border-blue-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Done</span>
      </div>
      <p class="text-3xl font-extrabold text-gray-900 count-anim"><?= $stats['completed'] ?></p>
      <p class="text-xs text-gray-500 mt-1">Completed</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Section Tickets -->
    <div class="fade-in delay-3 lg:col-span-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
      <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-gray-900">Recent Section Tickets</h3>
        <a href="<?= base_url('admin/tickets') ?>" class="text-xs text-blue-600 hover:underline font-semibold">View All →</a>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
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
              <tr><td colspan="5" class="py-8 text-center text-gray-400">No tickets yet</td></tr>
            <?php else: ?>
              <?php foreach($recentTickets as $ticket): ?>
              <tr class="ticket-row border-b border-gray-50">
                <td class="py-3 pr-4 mono text-xs font-bold text-blue-600">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td class="py-3 pr-4 text-gray-700 max-w-[200px] truncate"><?= esc($ticket['problem_description'] ?? 'N/A') ?></td>
                <td class="py-3 pr-4 text-gray-600"><?= esc($ticket['staff_name'] ?? 'Unassigned') ?></td>
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

    <!-- Quick Actions + Pending Verification -->
    <div class="space-y-6">
      <!-- Quick Actions -->
      <div class="fade-in delay-4 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
        <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
        <div class="space-y-2">
          <a href="<?= base_url('create-ticket') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm shadow-lg hover:shadow-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create New Ticket
          </a>
          <a href="<?= base_url('admin/tickets') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-50 text-blue-700 font-semibold text-sm hover:bg-blue-100 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            View All Section Tickets
          </a>
          <a href="<?= base_url('admin/verify') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 font-semibold text-sm hover:bg-emerald-100 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Verify Responses (<?= $stats['pending_verification'] ?>)
          </a>
        </div>
      </div>

      <!-- Section Staff Overview -->
      <div class="fade-in delay-5 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
        <h3 class="font-bold text-gray-900 mb-4">Section Staff</h3>
        <div class="space-y-3">
          <?php if(empty($sectionStaff)): ?>
            <p class="text-sm text-gray-400">No staff members</p>
          <?php else: ?>
            <?php foreach(array_slice($sectionStaff, 0, 5) as $staff): ?>
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                <?= strtoupper(substr($staff['name'], 0, 1)) ?>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate"><?= esc($staff['name']) ?></p>
                <p class="text-xs text-gray-400"><?= $staff['active_tickets'] ?? 0 ?> active tickets</p>
              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
