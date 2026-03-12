<?= $this->extend('super_admin/layout')?>

<?= $this->section('content') ?>

<?php
  // Helper: format ticket status badge
  $statusBadge = function(int $status): string {
      return \App\Models\JobStatusModel::badge($status)
          ?: '<span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-600">Unknown</span>';
  };

  // Helper: priority badge
  $priorityBadge = function(?string $name): string {
      if (!$name) return '<span class="px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">N/A</span>';
      $lower = strtolower($name);
      if (str_contains($lower, 'urgent') || str_contains($lower, 'critical')) {
          return '<span class="px-2.5 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full">' . esc($name) . '</span>';
      }
      if (str_contains($lower, 'high')) {
          return '<span class="px-2.5 py-1 bg-orange-100 text-orange-600 text-xs font-bold rounded-full">' . esc($name) . '</span>';
      }
      if (str_contains($lower, 'medium') || str_contains($lower, 'normal')) {
          return '<span class="px-2.5 py-1 bg-blue-100 text-blue-600 text-xs font-bold rounded-full">' . esc($name) . '</span>';
      }
      return '<span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">' . esc($name) . '</span>';
  };

  // Helper: activity icon by status (1=Open, 2=In Progress, 4=Completed, 5=Closed, 6=Cancelled)
  $activityIcon = function(int $status): string {
      return match($status) {
          1 => '<div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></div>',
          2 => '<div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></div>',
          4, 5 => '<div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>',
          6 => '<div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>',
          default => '<div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0"><svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>',
      };
  };

  $activityLabel = function(int $status): string {
      return \App\Models\JobStatusModel::activityLabel($status) ?? 'updated';
  };

  // Greeting
  $hour = (int) date('G');
  $greeting = match(true) {
      $hour < 12  => 'Good morning',
      $hour < 18  => 'Good afternoon',
      default     => 'Good evening',
  };

  // Stats shortcuts
  $total       = $stats['total'] ?? 0;
  $open        = $stats['open'] ?? 0;
  $inProgress  = $stats['in_progress'] ?? 0;
  $completed   = $stats['completed'] ?? 0;
  $closed      = $stats['closed'] ?? 0;
  $resolved    = $completed + $closed;

  // Donut chart calculations (circumference = 2*pi*50 ≈ 314.16)
  $circumference = 314.16;
  $resolvedPct   = $total > 0 ? $resolved / $total : 0;
  $openPct       = $total > 0 ? $open / $total : 0;
  $inProgressPct = $total > 0 ? $inProgress / $total : 0;

  $resolvedDash   = round($resolvedPct * $circumference);
  $openDash       = round($openPct * $circumference);
  $inProgressDash = round($inProgressPct * $circumference);

  $resolvedOffset   = 0;
  $openOffset       = -$resolvedDash;
  $inProgressOffset = -($resolvedDash + $openDash);

  // Progress bar widths for stat cards
  $totalProgress    = 100;
  $openProgress     = $total > 0 ? round((($open + $inProgress) / $total) * 100) : 0;
  $resolvedProgress = $total > 0 ? round(($resolved / $total) * 100) : 0;
  $usersProgress    = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100) : 0;

  // Technician gradient colors
  $techGradients = [
      'from-blue-600 to-indigo-700',
      'from-green-500 to-teal-600',
      'from-purple-500 to-pink-500',
      'from-amber-400 to-orange-500',
      'from-cyan-500 to-blue-500',
  ];
  $techBarGradients = [
      'from-blue-500 to-indigo-500',
      'from-green-500 to-emerald-500',
      'from-purple-500 to-pink-500',
      'from-amber-400 to-orange-400',
      'from-cyan-400 to-blue-400',
  ];
  $rankColors = ['text-blue-400', 'text-indigo-400', 'text-teal-400', 'text-gray-400', 'text-gray-400'];
?>

      <div class="p-8 space-y-8">

        <!-- Welcome banner -->
        <div class="fade-in delay-1 relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 text-white overflow-hidden shadow-2xl">
          <div class="absolute right-0 top-0 w-64 h-full opacity-10">
            <svg viewBox="0 0 200 200" class="w-full h-full"><circle cx="150" cy="50" r="80" fill="white"/><circle cx="50" cy="150" r="60" fill="white"/></svg>
          </div>
          <div class="relative z-10">
            <p class="text-blue-200 text-sm font-semibold mb-1"><?= esc($greeting) ?>,</p>
            <h2 class="text-3xl font-extrabold mb-2"><?= esc($user['name'] ?? 'Admin') ?> 👋</h2>
            <p class="text-blue-100 text-sm max-w-md">
              You have 
              <span class="font-bold text-white bg-white/20 px-2 py-0.5 rounded-lg"><?= $open ?> open ticket<?= $open !== 1 ? 's' : '' ?></span>
              and 
              <span class="font-bold text-white bg-white/20 px-2 py-0.5 rounded-lg"><?= $pendingVerification ?> pending verification<?= $pendingVerification !== 1 ? 's' : '' ?></span>
              awaiting action.
            </p>
          </div>
        </div>

        <!-- Stats row -->
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-5">
          <!-- Total Tickets -->
          <div class="stat-card fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
            <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
              </div>
              <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2.5 py-1 rounded-full">All</span>
            </div>
            <p class="mono text-3xl font-bold text-gray-900 count-anim"><?= $total ?></p>
            <p class="text-gray-500 text-sm mt-1 font-medium">Total Tickets</p>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
              <div class="progress-fill bg-gradient-to-r from-blue-500 to-indigo-500 h-1.5 rounded-full" style="width:<?= $totalProgress ?>%"></div>
            </div>
          </div>

          <!-- Open -->
          <div class="stat-card fade-in delay-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-amber-100/50 shadow-lg">
            <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2.5 py-1 rounded-full">Active</span>
            </div>
            <p class="mono text-3xl font-bold text-gray-900 count-anim"><?= $open + $inProgress ?></p>
            <p class="text-gray-500 text-sm mt-1 font-medium">Open / In Progress</p>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
              <div class="progress-fill bg-gradient-to-r from-amber-400 to-orange-400 h-1.5 rounded-full" style="width:<?= $openProgress ?>%"></div>
            </div>
          </div>

          <!-- Resolved -->
          <div class="stat-card fade-in delay-3 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-green-100/50 shadow-lg">
            <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <span class="text-xs font-semibold text-green-600 bg-green-100 px-2.5 py-1 rounded-full"><?= $total > 0 ? round(($resolved / $total) * 100) . '%' : '0%' ?></span>
            </div>
            <p class="mono text-3xl font-bold text-gray-900 count-anim"><?= $resolved ?></p>
            <p class="text-gray-500 text-sm mt-1 font-medium">Resolved</p>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
              <div class="progress-fill bg-gradient-to-r from-green-500 to-emerald-500 h-1.5 rounded-full" style="width:<?= $resolvedProgress ?>%"></div>
            </div>
          </div>

          <!-- Users -->
          <div class="stat-card fade-in delay-4 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-indigo-100/50 shadow-lg">
            <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              </div>
              <span class="text-xs font-semibold text-indigo-600 bg-indigo-100 px-2.5 py-1 rounded-full"><?= $totalUsers ?> total</span>
            </div>
            <p class="mono text-3xl font-bold text-gray-900 count-anim"><?= $activeUsers ?></p>
            <p class="text-gray-500 text-sm mt-1 font-medium">Active Employees</p>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
              <div class="progress-fill bg-gradient-to-r from-indigo-500 to-purple-500 h-1.5 rounded-full" style="width:<?= $usersProgress ?>%"></div>
            </div>
          </div>
        </div>

        <!-- Middle row -->
        <div class="grid lg:grid-cols-3 gap-6">

          <!-- Recent Tickets Table -->
          <div class="lg:col-span-2 fade-in delay-3 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
            <div class="flex items-center justify-between mb-5">
              <h3 class="font-bold text-gray-900 text-lg">Recent Tickets</h3>
              <span class="text-sm text-gray-400 font-medium"><?= $total ?> total</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                    <th class="pb-3 pr-4">Ticket</th>
                    <th class="pb-3 pr-4">Requestor</th>
                    <th class="pb-3 pr-4">Priority</th>
                    <th class="pb-3 pr-4">Status</th>
                    <th class="pb-3">Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($recentTickets)): ?>
                    <tr>
                      <td colspan="5" class="py-8 text-center text-gray-400">No tickets found.</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($recentTickets as $ticket): ?>
                      <?php
                        $initials = '';
                        $rName = $ticket['requestor_name'] ?? 'Unknown';
                        $words = explode(' ', trim($rName));
                        foreach ($words as $w) { $initials .= strtoupper($w[0] ?? ''); }
                        $initials = substr($initials, 0, 2);

                        $bgColors = ['bg-blue-600', 'bg-indigo-600', 'bg-green-600', 'bg-purple-600', 'bg-teal-600', 'bg-rose-600', 'bg-amber-600', 'bg-cyan-600'];
                        $colorIdx = crc32($rName) % count($bgColors);
                        $avatarBg = $bgColors[abs($colorIdx)];

                        $desc = $ticket['problem_description'] ?? 'No description';
                        $desc = strlen($desc) > 40 ? substr($desc, 0, 40) . '…' : $desc;

                        $createdDate = $ticket['created_at'] ? date('M j', strtotime($ticket['created_at'])) : '—';
                      ?>
                      <tr class="ticket-row border-b border-gray-50">
                        <td class="py-3 pr-4">
                          <p class="font-semibold text-gray-800 mono text-xs">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></p>
                          <p class="text-gray-500 text-xs mt-0.5"><?= esc($desc) ?></p>
                        </td>
                        <td class="py-3 pr-4">
                          <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg <?= $avatarBg ?> text-white text-xs flex items-center justify-center font-bold"><?= $initials ?></div>
                            <div>
                              <span class="text-gray-700 font-medium text-xs"><?= esc($rName) ?></span>
                              <?php if (!empty($ticket['section_acronym'])): ?>
                                <p class="text-gray-400 text-[10px]"><?= esc($ticket['section_acronym']) ?></p>
                              <?php endif; ?>
                            </div>
                          </div>
                        </td>
                        <td class="py-3 pr-4"><?= $priorityBadge($ticket['priority_name'] ?? null) ?></td>
                        <td class="py-3 pr-4"><?= $statusBadge((int) $ticket['job_status']) ?></td>
                        <td class="py-3 mono text-xs text-gray-500"><?= $createdDate ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Right column -->
          <div class="flex flex-col gap-5">

            <!-- Ticket breakdown donut-style -->
            <div class="fade-in delay-4 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
              <h3 class="font-bold text-gray-900 mb-5">Ticket Status</h3>
              <div class="relative flex items-center justify-center mb-5">
                <svg viewBox="0 0 120 120" class="w-32 h-32 -rotate-90">
                  <circle cx="60" cy="60" r="50" fill="none" stroke="#f0f4ff" stroke-width="14"/>
                  <?php if ($total > 0): ?>
                    <!-- Resolved (blue) -->
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#3b82f6" stroke-width="14"
                      stroke-dasharray="<?= $resolvedDash ?> <?= $circumference - $resolvedDash ?>"
                      stroke-dashoffset="<?= $resolvedOffset ?>" stroke-linecap="round"/>
                    <!-- Open (amber) -->
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#f59e0b" stroke-width="14"
                      stroke-dasharray="<?= $openDash ?> <?= $circumference - $openDash ?>"
                      stroke-dashoffset="<?= $openOffset ?>" stroke-linecap="round"/>
                    <!-- In Progress (green) -->
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="14"
                      stroke-dasharray="<?= $inProgressDash ?> <?= $circumference - $inProgressDash ?>"
                      stroke-dashoffset="<?= $inProgressOffset ?>" stroke-linecap="round"/>
                  <?php endif; ?>
                </svg>
                <div class="absolute text-center">
                  <p class="mono text-2xl font-bold text-gray-900"><?= $total ?></p>
                  <p class="text-xs text-gray-400">Total</p>
                </div>
              </div>
              <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-sm text-gray-600">Resolved</span>
                  </div>
                  <span class="mono text-sm font-bold text-gray-800"><?= $resolved ?></span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span class="text-sm text-gray-600">Open</span>
                  </div>
                  <span class="mono text-sm font-bold text-gray-800"><?= $open ?></span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-sm text-gray-600">In Progress</span>
                  </div>
                  <span class="mono text-sm font-bold text-gray-800"><?= $inProgress ?></span>
                </div>
                <?php if (($stats['cancelled'] ?? 0) > 0): ?>
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                      <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span>
                      <span class="text-sm text-gray-600">Cancelled</span>
                    </div>
                    <span class="mono text-sm font-bold text-gray-800"><?= $stats['cancelled'] ?? 0 ?></span>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="fade-in delay-5 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
              <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
              <div class="space-y-3">
                <a href="<?= site_url('super-admin/employees') ?>" class="w-full flex items-center gap-3 p-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                  Manage Employees
                </a>
                <a href="<?= site_url('super-admin/buildings') ?>" class="w-full flex items-center gap-3 p-3.5 rounded-xl bg-blue-50 text-blue-700 font-semibold text-sm hover:bg-blue-100 transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                  Manage Buildings
                </a>
                <a href="<?= site_url('super-admin/expertise') ?>" class="w-full flex items-center gap-3 p-3.5 rounded-xl bg-blue-50 text-blue-700 font-semibold text-sm hover:bg-blue-100 transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                  Manage Expertise
                </a>
              </div>
            </div>

            <!-- Tickets by Section -->
            <?php if (!empty($sectionStats)): ?>
            <div class="fade-in delay-5 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
              <h3 class="font-bold text-gray-900 mb-4">Tickets by Section</h3>
              <div class="space-y-3">
                <?php
                  $sectionColors = ['bg-blue-500', 'bg-green-500', 'bg-amber-500', 'bg-purple-500', 'bg-rose-500'];
                  $maxSection = max(array_column($sectionStats, 'cnt'));
                  foreach ($sectionStats as $i => $sec):
                    $barWidth = $maxSection > 0 ? round(((int)$sec['cnt'] / $maxSection) * 100) : 0;
                    $color = $sectionColors[$i % count($sectionColors)];
                ?>
                  <div>
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-sm font-semibold text-gray-700"><?= esc($sec['section_name']) ?></span>
                      <span class="mono text-sm font-bold text-gray-800"><?= (int) $sec['cnt'] ?></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                      <div class="<?= $color ?> h-2 rounded-full transition-all" style="width:<?= $barWidth ?>%"></div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

          </div>
        </div>

        <!-- Bottom row: Activity + Top Technicians -->
        <div class="grid lg:grid-cols-2 gap-6">

          <!-- Recent Activity -->
          <div class="fade-in delay-4 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
            <h3 class="font-bold text-gray-900 mb-5">Recent Activity</h3>
            <div class="space-y-4">
              <?php if (empty($recentActivity)): ?>
                <p class="text-sm text-gray-400 text-center py-4">No recent activity.</p>
              <?php else: ?>
                <?php foreach ($recentActivity as $activity): ?>
                  <?php
                    $aStatus  = (int) $activity['job_status'];
                    $aName    = $activity['requestor_name'] ?? 'Someone';
                    $sName    = $activity['staff_name'] ?? null;
                    $ticketId = 'ICTU-' . date('Y', strtotime($activity['updated_at'])) . '-' . str_pad($activity['job_ticket_id'], 5, '0', STR_PAD_LEFT);
                    $timeAgo  = $activity['updated_at'] ? date('M j, g:i A', strtotime($activity['updated_at'])) : '';

                    if ($aStatus === 1) {
                        $aDesc = 'Ticket <span class="mono font-bold">' . $ticketId . '</span> opened by ' . esc($aName);
                    } elseif ($sName && in_array($aStatus, [4, 5])) {
                        $aDesc = 'Ticket <span class="mono font-bold">' . $ticketId . '</span> ' . $activityLabel($aStatus) . ' by ' . esc($sName);
                    } else {
                        $aDesc = 'Ticket <span class="mono font-bold">' . $ticketId . '</span> ' . $activityLabel($aStatus);
                    }
                  ?>
                  <div class="flex gap-3">
                    <?= $activityIcon($aStatus) ?>
                    <div>
                      <p class="text-sm text-gray-800 font-medium"><?= $aDesc ?></p>
                      <p class="text-xs text-gray-400 mono mt-0.5"><?= $timeAgo ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>

          <!-- Top Technicians -->
          <div class="fade-in delay-5 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
            <h3 class="font-bold text-gray-900 mb-5">Top Technicians</h3>
            <div class="space-y-4">
              <?php if (empty($topTechnicians)): ?>
                <p class="text-sm text-gray-400 text-center py-4">No technician data yet.</p>
              <?php else: ?>
                <?php foreach ($topTechnicians as $i => $tech): ?>
                  <?php
                    $barPct = $maxResolved > 0 ? round(((int)$tech['resolved_count'] / $maxResolved) * 100) : 0;
                    $gradient = $techGradients[$i % count($techGradients)];
                    $barGrad  = $techBarGradients[$i % count($techBarGradients)];
                    $rankClr  = $rankColors[$i % count($rankColors)];
                  ?>
                  <div class="flex items-center gap-3">
                    <span class="mono text-xs font-bold <?= $rankClr ?> w-4">#<?= $i + 1 ?></span>
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br <?= $gradient ?> flex items-center justify-center text-white font-bold text-xs"><?= esc($tech['initials']) ?></div>
                    <div class="flex-1">
                      <p class="text-sm font-semibold text-gray-800"><?= esc($tech['name']) ?></p>
                      <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                          <div class="bg-gradient-to-r <?= $barGrad ?> h-1.5 rounded-full" style="width:<?= $barPct ?>%"></div>
                        </div>
                        <span class="mono text-xs text-gray-500"><?= (int) $tech['resolved_count'] ?> resolved</span>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>

      </div>

<?= $this->endSection() ?>