<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">

  <a href="<?= base_url('super-admin/tickets') ?>" class="inline-flex items-center text-sm text-slate-600 hover:text-slate-800 font-medium transition-colors">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Back to Tickets
  </a>

  <!-- Page Header -->
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-xl font-bold text-gray-900">Ticket Details</h2>
      <p class="text-sm text-gray-500 mono">ICTU-<?= date('Y', strtotime($ticket['created_at'] ?? 'now')) ?>-<?= str_pad($ticket['job_ticket_id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></p>
    </div>
    <?= \App\Models\JobStatusModel::badgeMd((int) $ticket['job_status']) ?: '<span class="text-sm font-bold px-3 py-1.5 rounded-full bg-gray-100 text-gray-600">Unknown</span>' ?>
  </div>

  <?php if (!empty($slaSummary)): ?>
    <?php
      $remaining = (int) ($slaSummary['remaining_seconds'] ?? 0);
      $isOverdue = (bool) ($slaSummary['is_overdue'] ?? false);
      $chipClass = $isOverdue ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700';
      $label     = $isOverdue ? 'Overdue by' : 'Time remaining';
    ?>
    <div class="fade-in bg-white/80 backdrop-blur-sm rounded-2xl p-4 border border-gray-200/60 shadow-sm">
      <div class="flex items-center justify-between gap-3 flex-wrap">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider">Ticket Timeframe</p>
          <p class="text-sm text-gray-700">Rule: <span class="font-semibold"><?= (int) ($slaSummary['target_hours'] ?? 0) ?> hour(s)</span> from In Progress start</p>
        </div>
        <span class="js-sla-countdown inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold <?= $chipClass ?>" data-remaining="<?= $remaining ?>" data-overdue="<?= $isOverdue ? '1' : '0' ?>"><?= $label ?>...</span>
      </div>
    </div>
  <?php endif; ?>

  <!-- Ticket Info -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-5">Ticket Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-4">
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Ticket ID</span>
          <p class="mono text-sm font-bold text-slate-700">ICTU-<?= date('Y', strtotime($ticket['created_at'])) ?>-<?= str_pad($ticket['job_ticket_id'], 5, '0', STR_PAD_LEFT) ?></p>
        </div>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Date Submitted</span>
          <p class="text-sm text-gray-700"><?= date('F d, Y h:i A', strtotime($ticket['created_at'])) ?></p>
        </div>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Requestor</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['requestor_name'] ?? 'N/A') ?></p>
        </div>
        <?php if(!empty($ticket['requestor_account_no'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Requestor Account No.</span>
          <p class="mono text-sm text-gray-700"><?= esc($ticket['requestor_account_no']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['requestor_email'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Requestor Email</span>
          <p class="text-sm text-gray-700 break-all"><?= esc($ticket['requestor_email']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['requestor_phone_number'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Requestor Contact</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['requestor_phone_number']) ?></p>
        </div>
        <?php endif; ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Problem Description</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['problem_description'] ?? 'N/A') ?></p>
        </div>
        <?php if(!empty($asset)): ?>
        <div class="md:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
          <span class="text-slate-600 text-xs font-bold uppercase tracking-wider block mb-2">Linked Asset Information</span>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
            <div>
              <span class="text-gray-400 text-[10px] uppercase">Asset Tag</span>
              <p class="text-sm font-semibold text-slate-800"><?= esc($asset['asset_tag'] ?? 'N/A') ?></p>
            </div>
            <div>
              <span class="text-gray-400 text-[10px] uppercase">Property No.</span>
              <p class="text-sm font-semibold text-slate-800"><?= esc($asset['property_no'] ?? 'N/A') ?></p>
            </div>
            <div>
              <span class="text-gray-400 text-[10px] uppercase">Brand/Model</span>
              <p class="text-sm font-semibold text-slate-800"><?= esc($asset['brand_model'] ?? 'N/A') ?></p>
            </div>
            <div>
              <span class="text-gray-400 text-[10px] uppercase">Serial Number</span>
              <p class="text-sm font-semibold text-slate-800"><?= esc($asset['serial_number'] ?? 'N/A') ?></p>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['additional_details'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Additional Details</span>
          <p class="text-sm text-gray-700 whitespace-pre-line"><?= esc($ticket['additional_details']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['hardware_issues_text'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Hardware Issues</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['hardware_issues_text']) ?></p>
        </div>
        <?php elseif(!empty($ticket['hardware_issues'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Hardware Issues</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['hardware_issues']) ?></p>
        </div>
        <?php endif; ?>
        <?php $softwareIssues = $ticket['software_issues_text'] ?? ($ticket['software_issues'] ?? ($ticket['sofware_issues'] ?? null)); ?>
        <?php if(!empty($softwareIssues)): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Software Issues</span>
          <p class="text-sm text-gray-700"><?= esc($softwareIssues) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['verification_date'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Verification Date</span>
          <p class="text-sm text-gray-700"><?= date('F d, Y h:i A', strtotime($ticket['verification_date'])) ?></p>
        </div>
        <?php endif; ?>
      </div>
      <div class="space-y-4">
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-2">Requestor Details</span>
          <div class="bg-gray-50 rounded-lg p-3 space-y-2">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs uppercase"><?= substr($ticket['requestor_name'] ?? 'U', 0, 1) ?></div>
              <div>
                <p class="text-sm font-bold text-gray-800 leading-none"><?= esc($ticket['requestor_name'] ?? 'Unknown User') ?></p>
                <p class="text-[10px] text-gray-500 uppercase mt-1">Requestor</p>
              </div>
            </div>
            <div class="grid grid-cols-1 gap-1 pt-1 border-t border-gray-200/60">
              <div class="flex items-center gap-2 text-[11px] text-gray-600">
                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002 0V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <?= esc($ticket['requestor_email'] ?? 'N/A') ?>
              </div>
              <?php if(!empty($ticket['requestor_account_no'])): ?>
              <div class="flex items-center gap-2 text-[11px] text-gray-600">
                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                ID: <?= esc($ticket['requestor_account_no']) ?>
              </div>
              <?php endif; ?>
              <?php if(!empty($ticket['requestor_phone_number'])): ?>
              <div class="flex items-center gap-2 text-[11px] text-gray-600">
                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <?= esc($ticket['requestor_phone_number']) ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php if(!empty($ticket['request_type'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Request Type</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['request_type']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['request_platform'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Platform</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['request_platform']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['request_action'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Action</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['request_action']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['request_equipment'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Equipment</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['request_equipment']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['serial_no'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Serial No.</span>
          <p class="mono text-sm text-gray-700"><?= esc($ticket['serial_no']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['property_no'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Property No.</span>
          <p class="mono text-sm text-gray-700"><?= esc($ticket['property_no']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['peripheral_description'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Peripheral Description</span>
          <p class="text-sm text-gray-700"><?= esc($ticket['peripheral_description']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['attachments'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Attachment</span>
          <a href="<?= base_url($ticket['attachments']) ?>" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline"><?= esc($ticket['attachments']) ?></a>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['additional_request_file'])): ?>
        <div class="md:col-span-2">
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-2">Additional File</span>
          <?php 
            $fileExt = strtolower(pathinfo($ticket['additional_request_file'], PATHINFO_EXTENSION));
            $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
          ?>
          <?php if ($isImage): ?>
            <div class="mt-2 group relative inline-block">
              <img src="<?= base_url($ticket['additional_request_file']) ?>" 
                   alt="Additional File" 
                   class="max-w-md rounded-lg shadow-md border border-gray-200 cursor-zoom-in hover:shadow-xl transition-shadow">
              <a href="<?= base_url($ticket['additional_request_file']) ?>" 
                 target="_blank" 
                 class="absolute bottom-2 right-2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            </div>
          <?php else: ?>
            <a href="<?= base_url($ticket['additional_request_file']) ?>" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline"><?= esc($ticket['additional_request_file']) ?></a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if(!empty($ticket['pre_repair_form'])): ?>
        <div class="md:col-span-2">
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-2">Pre-repair Form</span>
          <?php 
            $fileExt = strtolower(pathinfo($ticket['pre_repair_form'], PATHINFO_EXTENSION));
            $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
          ?>
          <?php if ($isImage): ?>
            <div class="mt-2 group relative inline-block">
              <img src="<?= base_url($ticket['pre_repair_form']) ?>" 
                   alt="Pre-repair Form" 
                   class="max-w-md rounded-lg shadow-md border border-gray-200 cursor-zoom-in hover:shadow-xl transition-shadow">
              <a href="<?= base_url($ticket['pre_repair_form']) ?>" 
                 target="_blank" 
                 class="absolute bottom-2 right-2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            </div>
          <?php else: ?>
            <a href="<?= base_url($ticket['pre_repair_form']) ?>" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 underline"><?= esc($ticket['pre_repair_form']) ?></a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Response Info -->
  <?php if(!empty($response)): ?>
  <div class="fade-in delay-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-5">Response / Resolution</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-4">
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Assigned Staff</span>
          <p class="text-sm font-semibold text-gray-700"><?= esc($response['staff_name'] ?? 'N/A') ?></p>
        </div>
        <?php if(!empty($response['action_performed'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Action Performed</span>
          <p class="text-sm text-gray-700"><?= esc($response['action_performed']) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($response['completion_status'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Completion Status</span>
          <?php
            $csMap = ['completed' => 'bg-emerald-100 text-emerald-700', 'in_progress' => 'bg-blue-100 text-blue-700'];
            $csColor = $csMap[$response['completion_status']] ?? 'bg-gray-100 text-gray-600';
          ?>
          <span class="text-xs font-bold px-2 py-1 rounded-full <?= $csColor ?>"><?= ucwords(str_replace('_', ' ', $response['completion_status'])) ?></span>
        </div>
        <?php endif; ?>
        <?php if(!empty($response['completion_date'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Completion Date</span>
          <p class="text-sm text-gray-700"><?= date('F d, Y', strtotime($response['completion_date'])) ?></p>
        </div>
        <?php endif; ?>
      </div>
      <div class="space-y-4">
        <?php if(!empty($responseParts)): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-2">Parts Replaced / Used</span>
          <div class="overflow-hidden rounded-xl border border-gray-200">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                  <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Part</th>
                  <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                  <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase">Unit Cost</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php foreach($responseParts as $part): ?>
                <tr class="hover:bg-gray-50/50">
                  <td class="px-3 py-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold <?= $part['part_type'] === 'replaced' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' ?>">
                      <?= ucfirst($part['part_type']) ?>
                    </span>
                  </td>
                  <td class="px-3 py-2 text-gray-700"><?= esc($part['part_name']) ?></td>
                  <td class="px-3 py-2 text-center text-gray-700"><?= (int)$part['quantity'] ?></td>
                  <td class="px-3 py-2 text-right text-gray-700 mono"><?= $part['unit_cost'] ? '₱' . number_format((float)$part['unit_cost'], 2) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <?php endif; ?>
        <?php if(!empty($response['estimated_cost'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Estimated Cost</span>
          <p class="mono text-sm font-semibold text-gray-700">₱<?= number_format((float)$response['estimated_cost'], 2) ?></p>
        </div>
        <?php endif; ?>
        <?php if(!empty($response['verified_date'])): ?>
        <div>
          <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Verified</span>
          <p class="text-sm text-emerald-600 font-semibold">✓ Verified on <?= date('F d, Y', strtotime($response['verified_date'])) ?></p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="fade-in delay-2 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 shadow-lg text-center">
    <div class="text-gray-300 text-4xl mb-3">⏳</div>
    <p class="text-gray-500 text-sm">No response yet. This ticket is being processed.</p>
  </div>
  <?php endif; ?>

  <!-- Ticket History Timeline -->
  <?php if (!empty($history)): ?>
  <div class="fade-in delay-3 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-5 flex items-center gap-2">
      <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Ticket History
    </h3>
    <div class="relative">
      <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-gray-200"></div>
      <div class="space-y-5">
        <?php
          $actionConfig = [
            'created'     => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>',       'color' => 'bg-blue-500',    'label' => 'Ticket Created'],
            'assigned'    => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',  'color' => 'bg-indigo-500', 'label' => 'Assigned'],
            'in_progress' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>',  'color' => 'bg-amber-500',  'label' => 'In Progress'],
            'completed'   => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',  'color' => 'bg-emerald-500','label' => 'Completed'],
            'verified'    => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',  'color' => 'bg-teal-500',   'label' => 'Verified & Closed'],
            'transferred' => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>',  'color' => 'bg-purple-500', 'label' => 'Transferred'],
            'cancelled'   => ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',  'color' => 'bg-red-500',    'label' => 'Cancelled'],
          ];
        ?>
        <?php foreach ($history as $entry): ?>
          <?php
            $cfg   = $actionConfig[$entry['action']] ?? ['icon' => '<circle cx="12" cy="12" r="3"/>', 'color' => 'bg-gray-400', 'label' => ucwords(str_replace('_', ' ', $entry['action']))];
            $time  = $entry['created_at'] ? date('M j, Y \a\t g:i A', strtotime($entry['created_at'])) : '';
          ?>
          <div class="relative flex items-start gap-4 pl-1">
            <div class="relative z-10 flex-shrink-0 w-[30px] h-[30px] rounded-full <?= $cfg['color'] ?> flex items-center justify-center shadow-sm">
              <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $cfg['icon'] ?></svg>
            </div>
            <div class="pt-0.5 min-w-0">
              <p class="text-sm font-semibold text-gray-800"><?= $cfg['label'] ?></p>
              <?php if (!empty($entry['remarks'])): ?>
                <p class="text-xs text-gray-500 mt-0.5"><?= esc($entry['remarks']) ?></p>
              <?php endif; ?>
              <div class="flex items-center gap-2 mt-1">
                <?php if (!empty($entry['performer_name'])): ?>
                  <span class="text-xs text-gray-400">by <?= esc($entry['performer_name']) ?></span>
                  <span class="text-gray-300">&middot;</span>
                <?php endif; ?>
                <span class="text-xs text-gray-400 mono"><?= $time ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
<script>
(() => {
  const nodes = document.querySelectorAll('.js-sla-countdown');
  if (!nodes.length) return;

  const format = (s) => {
    const n = Math.max(0, Math.abs(s));
    const d = Math.floor(n / 86400);
    const h = Math.floor((n % 86400) / 3600);
    const m = Math.floor((n % 3600) / 60);
    return `${d}d ${h}h ${m}m`;
  };

  nodes.forEach((node) => {
    let remaining = parseInt(node.dataset.remaining || '0', 10);
    const overdue = node.dataset.overdue === '1';

    const tick = () => {
      node.textContent = `${overdue ? 'Overdue by' : 'Time remaining'} ${format(remaining)}`;
      remaining += overdue ? 1 : -1;
    };

    tick();
    setInterval(tick, 60000);
  });
})();
</script>
<?= $this->endSection() ?>
