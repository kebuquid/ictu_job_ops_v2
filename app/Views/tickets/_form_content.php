<style>
  .dispatcher-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
  .dispatcher-card:hover { transform: translateY(-1px); }

  .tip-enter { animation: tipSlideIn 0.35s ease forwards; }
  @keyframes tipSlideIn {
    from { opacity: 0; transform: translateY(12px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }

  .section-fields-enter { animation: fieldsReveal 0.4s ease forwards; }
  @keyframes fieldsReveal {
    from { opacity: 0; max-height: 0; transform: translateY(15px); }
    to   { opacity: 1; max-height: 2000px; transform: translateY(0); }
  }

  .keyword-pill { transition: all 0.2s; }
  .keyword-pill:hover { transform: scale(1.05); }

  .typing-indicator span {
    display: inline-block;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #3b82f6;
    animation: typingBounce 1.2s infinite;
  }
  .typing-indicator span:nth-child(2) { animation-delay: 0.15s; }
  .typing-indicator span:nth-child(3) { animation-delay: 0.3s; }
  @keyframes typingBounce {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
    30% { transform: translateY(-6px); opacity: 1; }
  }

  /* Smooth select styling */
  select.smart-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
  }

  /* Wizard step transitions */
  .wizard-step { animation: stepFadeIn 0.3s ease forwards; }
  @keyframes stepFadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Accordion */
  .accordion-body { max-height: 0; overflow: hidden; transition: max-height 0.35s ease, padding 0.35s ease; }
  .accordion-body.open { max-height: 600px; padding-top: 0.75rem; padding-bottom: 0.25rem; }
  .accordion-chevron { transition: transform 0.3s ease; }
  .accordion-chevron.rotate { transform: rotate(180deg); }

  /* Required asterisk */
  .field-required::after { content: ' *'; color: #ef4444; font-weight: 600; }
</style>

<div class="p-8 space-y-6 max-w-3xl mx-auto">

  <!-- Header -->
  <div class="fade-in">
    <h2 class="text-2xl font-extrabold text-gray-900 flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      </div>
      Intelligent Ticket Dispatcher
    </h2>
    <p class="text-sm text-gray-500 mt-1 ml-[52px]">Describe your problem and we'll route it to the right team automatically.</p>
  </div>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="fade-in bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="fade-in bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form id="ticketForm" action="<?= base_url(($rolePrefix ?? 'employee') . '/create-ticket') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="section_id" id="sectionId" value="">

    <!-- Step Progress Indicator -->
    <div id="stepIndicator" class="flex items-center mb-6">
      <div class="flex items-center">
        <div id="stepCircle1" class="w-8 h-8 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center shadow-sm shrink-0 transition-all duration-300">1</div>
        <span id="stepLabel1" class="ml-2 text-sm font-semibold text-blue-600 hidden sm:inline transition-colors duration-300">Describe</span>
      </div>
      <div id="stepLine1" class="flex-1 h-0.5 mx-4 bg-gray-200 rounded transition-colors duration-300"></div>
      <div class="flex items-center">
        <div id="stepCircle2" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 text-xs font-bold flex items-center justify-center shrink-0 transition-all duration-300">2</div>
        <span id="stepLabel2" class="ml-2 text-sm font-semibold text-gray-400 hidden sm:inline transition-colors duration-300">Details</span>
      </div>
      <div id="stepLine2" class="flex-1 h-0.5 mx-4 bg-gray-200 rounded transition-colors duration-300"></div>
      <div class="flex items-center">
        <div id="stepCircle3" class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 text-xs font-bold flex items-center justify-center shrink-0 transition-all duration-300">3</div>
        <span id="stepLabel3" class="ml-2 text-sm font-semibold text-gray-400 hidden sm:inline transition-colors duration-300">Review</span>
      </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         WIZARD STEP 1: Common Details
         ═══════════════════════════════════════════════════════ -->
    <div id="wizardStep1" class="wizard-step">
    <div class="dispatcher-card bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-5">
      <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">1</span>
        Common Details
      </h3>

      <!-- Logged-in user info (read-only) -->
      <div class="flex items-center gap-3 p-3 bg-blue-50/50 rounded-xl border border-blue-100">
        <?php $user = $user ?? session()->get('user'); ?>
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white text-sm flex items-center justify-center font-bold shadow-sm shrink-0 overflow-hidden">
          <?php if (!empty($user['avatar'])): ?>
            <img src="<?= esc($user['avatar']) ?>" alt="" class="w-full h-full object-cover rounded-xl">
          <?php else: ?>
            <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
          <?php endif; ?>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-gray-900 truncate"><?= esc($user['name'] ?? 'Unknown') ?></p>
          <p class="text-xs text-gray-500 truncate"><?= esc($user['email'] ?? '') ?></p>
        </div>
        <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2.5 py-1 rounded-full shrink-0">Requestor</span>
      </div>  
    </div>

    <!-- Describe Your Problem (inside Step 1) -->
    <div class="dispatcher-card bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-4 mt-6">
      <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </span>
        Describe Your Problem
      </h3>

      <div class="relative">
        <textarea name="problem_description" id="problemDescription" rows="4" required
                  placeholder="Type your problem here… e.g. 'My internet is not working' or 'Printer keeps jamming'"
                  class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all resize-none"></textarea>

        <!-- Typing indicator (shows while analyzing) -->
        <div id="typingIndicator" class="hidden absolute bottom-3 right-4 typing-indicator flex gap-1 items-center">
          <span></span><span></span><span></span>
          <span class="text-xs text-blue-500 ml-1 font-medium">Analyzing…</span>
        </div>
      </div>

      <!-- Detected keywords pills -->
      <div id="detectedKeywords" class="hidden flex flex-wrap gap-2">
        <span class="text-xs text-gray-500 font-medium py-1">Detected:</span>
      </div>
    </div>

    <!-- Troubleshooting Tip (appears when keyword matched) -->
    <div id="troubleshootingTip" class="hidden mt-6">
      <!-- Content injected by JS -->
    </div>

    <!-- Manual Section Selection (when no keywords detected) -->
    <div id="manualSelectionPrompt" class="hidden mt-6">
      <div class="tip-enter bg-gray-50 border border-gray-200 rounded-2xl p-5 space-y-4">
        <div class="flex items-start gap-3">
          <span class="text-2xl leading-none mt-0.5">🤔</span>
          <div class="flex-1">
            <p class="font-bold text-gray-800 text-sm">Not sure which category?</p>
            <p class="text-sm text-gray-600 mt-1">We couldn't automatically detect the right team. Please select the category that best fits your issue.</p>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <?php foreach ($sections as $s): ?>
          <button type="button"
                  data-section-id="<?= esc($s['section_id']) ?>"
                  data-section-acronym="<?= esc($s['acronym']) ?>"
                  data-section-name="<?= esc($s['name']) ?>"
                  class="manual-section-btn relative flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all group text-center">
            <?php
              $iconColor = match(strtoupper($s['acronym'])) {
                'NICM'   => 'from-green-400 to-emerald-600',
                'ICTRAM' => 'from-amber-400 to-orange-600',
                'MIS'    => 'from-purple-400 to-violet-600',
                default  => 'from-blue-400 to-indigo-600',
              };
            ?>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?= $iconColor ?> text-white flex items-center justify-center mb-2 shadow-sm">
              <span class="text-xs font-bold"><?= esc($s['acronym']) ?></span>
            </div>
            <span class="font-semibold text-gray-900 text-sm"><?= esc($s['acronym']) ?></span>
            <span class="text-xs text-gray-500 mt-0.5"><?= esc($s['name']) ?></span>
          </button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    </div><!-- /wizardStep1 -->

    <!-- ═══════════════════════════════════════════════════════
         WIZARD STEP 2: Section Details & Submit
         ═══════════════════════════════════════════════════════ -->
    <div id="wizardStep2" class="wizard-step hidden">
    <div id="sectionFields" class="space-y-6">
      <!-- Routed-to banner -->
      <div id="routedBanner" class="dispatcher-card bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          </div>
          <div>
            <p class="text-sm font-bold">Routed to: <span id="routedSectionName" class="text-blue-200"></span></p>
            <p class="text-xs text-blue-200/80">Please complete the additional details below.</p>
          </div>
        </div>
      </div>

      <!-- ── NICM Fields ─────────────────────────────────── -->
      <div id="nicmFields" class="hidden dispatcher-card bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-5 section-fields-enter">
        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <span class="w-6 h-6 rounded-full bg-green-600 text-white text-xs flex items-center justify-center font-bold">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </span>
          NICM — Network & Connectivity Details
        </h3>

        <!-- Equipment (required) -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5 field-required">Equipment</label>
          <select name="equipment" id="nicmEquipment" required
                  class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
            <option value="" disabled selected>Loading…</option>
          </select>
        </div>

        <!-- Action (required) -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5 field-required">Request Action</label>
          <select name="action" id="nicmAction" required
                  class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
            <option value="" disabled selected>Loading…</option>
          </select>
        </div>
      </div>

      <!-- ── ICTRAM Fields ────────────────────────────────── -->
      <div id="ictramFields" class="hidden dispatcher-card bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-5 section-fields-enter">
        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs flex items-center justify-center font-bold">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </span>
          ICTRAM — Hardware & Equipment Details
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Building / Location (required) -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 field-required">Location (Building)</label>
            <select name="building_id" id="ictramBuilding" required
                    class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
              <option value="" disabled selected>Select building…</option>
              <?php foreach ($buildings as $b): ?>
                <option value="<?= esc($b['building_id']) ?>"><?= esc($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <!-- Equipment (required) -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 field-required">Equipment</label>
            <select name="equipment" id="ictramEquipment" required
                    class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
              <option value="" disabled selected>Loading…</option>
            </select>
          </div>
        </div>

        <!-- Brand / Model -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Brand</label>
            <input type="text" name="brand" placeholder="e.g. HP, Dell, Epson…"
                   class="w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Model</label>
            <input type="text" name="model" placeholder="e.g. LaserJet Pro M404…"
                   class="w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all">
          </div>
        </div>

        <!-- Priority Level (required) -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5 field-required">Priority Level</label>
          <select name="priority_level_id" id="ictramPriority" required
                  class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
            <option value="" disabled selected>Select priority…</option>
            <?php foreach ($priorityLevels as $pl): ?>
              <option value="<?= esc($pl['priority_level_id']) ?>"><?= esc($pl['priority_name']) ?> — <?= esc($pl['operation_status'] ?? '') ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Hardware Issues (accordion) -->
        <div class="border border-gray-200 rounded-xl overflow-hidden">
          <button type="button" class="accordion-toggle w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
            <span class="text-sm font-semibold text-gray-700">Hardware Issues</span>
            <svg class="accordion-chevron w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="accordion-body px-4">
            <div id="ictramHardwareIssues" class="grid grid-cols-1 sm:grid-cols-2 gap-2 pb-3">
              <p class="text-xs text-gray-400 col-span-full">Loading…</p>
            </div>
          </div>
        </div>

        <!-- Software Issues (accordion) -->
        <div class="border border-gray-200 rounded-xl overflow-hidden">
          <button type="button" class="accordion-toggle w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
            <span class="text-sm font-semibold text-gray-700">Software Issues</span>
            <svg class="accordion-chevron w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="accordion-body px-4">
            <div id="ictramSoftwareIssues" class="grid grid-cols-1 sm:grid-cols-2 gap-2 pb-3">
              <p class="text-xs text-gray-400 col-span-full">Loading…</p>
            </div>
          </div>
        </div>

        <!-- Request Type (required) -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5 field-required">Request Type</label>
          <select name="request_type" id="ictramRequestType" required
                  class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
            <option value="" disabled selected>Loading…</option>
          </select>
        </div>
      </div>

      <!-- ── MIS Fields ──────────────────────────────────── -->
      <div id="misFields" class="hidden dispatcher-card bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-5 section-fields-enter">
        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <span class="w-6 h-6 rounded-full bg-purple-600 text-white text-xs flex items-center justify-center font-bold">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
          </span>
          MIS — Account & System Details
        </h3>

        <!-- Student/Employee Number (required) -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5 field-required">Student / Employee Number</label>
          <input type="text" name="requestor_number" id="misRequestorNumber" required placeholder="Enter your ID number"
                 class="w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all">
        </div>

        <!-- Request Type (radio cards) (required) -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2 field-required">Request Type</label>
          <div id="misRequestTypes" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <p class="text-xs text-gray-400 col-span-full">Loading…</p>
          </div>
        </div>

        <!-- Dependent: Platform & Action -->
        <div id="misDependentFields" class="hidden space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Request Platform</label>
            <select name="request_platform_id" id="misPlatform"
                    class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
              <option value="" disabled selected>Select platform…</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Request Action</label>
            <select name="request_action_id" id="misAction"
                    class="smart-select w-full px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer pr-10">
              <option value="" disabled selected>Select action…</option>
            </select>
          </div>
        </div>
      </div>

      <!-- ── Additional Details (always shown after routing) ── -->
      <div class="dispatcher-card bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-5">
        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <span class="w-6 h-6 rounded-full bg-gray-700 text-white text-xs flex items-center justify-center font-bold">+</span>
          Additional Information
        </h3>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Extra Notes (optional)</label>
          <textarea name="additional_details" id="additionalDetails" rows="3" placeholder="Any additional context that might help the team…"
                    class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all resize-none"></textarea>
        </div>

        <!-- File Upload -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1.5">Attach Photo (optional)</label>
          <div class="flex items-center justify-center w-full">
            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-blue-200 border-dashed rounded-xl cursor-pointer bg-blue-50/30 hover:bg-blue-50 transition-all">
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                <p class="text-xs text-gray-500"><span class="font-semibold text-blue-600">Click to upload</span> or drag and drop</p>
                <p class="text-xs text-gray-400 mt-0.5">PNG, JPG up to 10MB</p>
              </div>
              <input type="file" name="equipment_photo" id="equipmentPhoto" class="hidden" accept="image/*">
            </label>
          </div>
          <p id="fileNameDisplay" class="hidden text-xs text-gray-600 mt-1.5 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span id="fileNameText"></span>
          </p>
        </div>

        <!-- Navigation -->
        <div class="flex items-center justify-between gap-4 pt-2">
          <button type="button" id="backToStep1"
                  class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
          </button>
          <button type="button" id="nextToStep3"
                  class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
            Review & Submit
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>
    </div><!-- /sectionFields -->
    </div><!-- /wizardStep2 -->

    <!-- ═══════════════════════════════════════════════════════
         WIZARD STEP 3: Review & Submit
         ═══════════════════════════════════════════════════════ -->
    <div id="wizardStep3" class="wizard-step hidden">
      <div class="dispatcher-card bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-5">
        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </span>
          Review Your Ticket
        </h3>
        <p class="text-xs text-gray-500">Please review the information below before submitting. Click "Back" to make changes.</p>

        <div id="summaryContent" class="space-y-4">
          <!-- Populated by JS -->
        </div>

        <!-- Navigation -->
        <div class="flex items-center justify-between gap-4 pt-3 border-t border-gray-100">
          <button type="button" id="backToStep2"
                  class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
          </button>
          <button type="submit"
                  class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Submit Ticket
          </button>
        </div>
      </div>
    </div><!-- /wizardStep3 -->
  </form>
</div>

<script>
$(document).ready(function () {

  // ════════════════════════════════════════════════════════
  // ALLOWED SECTIONS (admin-controlled per role)
  // ════════════════════════════════════════════════════════
  const allowedAcronyms = <?= json_encode($allowedAcronyms ?? ['NICM', 'ICTRAM', 'MIS']) ?>;

  // ════════════════════════════════════════════════════════
  // KEYWORD → SECTION MAPPING
  // ════════════════════════════════════════════════════════
  const sectionMap = {
    <?php foreach ($sections as $s): ?>
    '<?= esc($s['section_id']) ?>': { id: <?= (int) $s['section_id'] ?>, acronym: '<?= esc($s['acronym']) ?>', name: '<?= esc($s['name']) ?>' },
    <?php endforeach; ?>
  };

  // Map section acronym to section_id from DB for safe lookup
  const acronymToId = {};
  Object.values(sectionMap).forEach(s => { acronymToId[s.acronym.toUpperCase()] = s.id; });

  // ════════════════════════════════════════════════════════
  // DYNAMIC KEYWORD RULES (loaded from database)
  // ════════════════════════════════════════════════════════
  const dbKeywordRules = <?= json_encode($keywordRulesData ?? []) ?>;

  // Build keywordRules array from database data
  const keywordRules = dbKeywordRules
    .filter(rule => allowedAcronyms.includes(rule.sectionAcronym))
    .map(rule => {
      // Build regex from keywords array (escape special regex chars, handle multi-word with \s*)
      const escaped = rule.keywords.map(kw => kw.replace(/[.*+?^${}()|[\]\\]/g, '\\$&').replace(/\s+/g, '\\s*'));
      const pattern = escaped.length > 0 ? new RegExp('\\b(' + escaped.join('|') + ')\\b', 'i') : null;
      return {
        pattern: pattern,
        sectionAcronym: rule.sectionAcronym,
        tips: rule.tips || {}
      };
    })
    .filter(rule => rule.pattern !== null);

  let analysisTimeout = null;
  let matchedRule = null;
  let sectionDataCache = {};  // cache AJAX responses
  let currentStep = 1;

  // ════════════════════════════════════════════════════════
  // ACTIVATE SECTION — disable hidden sections so their
  // `required` fields don't block HTML5 form validation
  // ════════════════════════════════════════════════════════
  function activateSection(acronym) {
    const sections = { NICM: '#nicmFields', ICTRAM: '#ictramFields', MIS: '#misFields' };
    Object.entries(sections).forEach(([key, selector]) => {
      $(selector).find('input, select, textarea').prop('disabled', key !== acronym);
    });
  }

  // Disable all section fields on load (none is active yet)
  activateSection('');

  // ════════════════════════════════════════════════════════
  // WIZARD STEP NAVIGATION
  // ════════════════════════════════════════════════════════
  function goToStep(step) {
    $('#wizardStep1, #wizardStep2, #wizardStep3').addClass('hidden');
    const target = $(`#wizardStep${step}`);
    target.removeClass('wizard-step');
    void target[0].offsetWidth; // force reflow for animation replay
    target.addClass('wizard-step').removeClass('hidden');
    updateStepIndicator(step);
    currentStep = step;
    document.getElementById('stepIndicator').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function updateStepIndicator(step) {
    [1, 2, 3].forEach(function (i) {
      const circle = $(`#stepCircle${i}`);
      const label  = $(`#stepLabel${i}`);
      circle.removeClass('bg-blue-600 bg-green-500 bg-gray-200 text-white text-gray-500');
      label.removeClass('text-blue-600 text-green-600 text-gray-400');
      if (i < step) {
        circle.addClass('bg-green-500 text-white').html('<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>');
        label.addClass('text-green-600');
      } else if (i === step) {
        circle.addClass('bg-blue-600 text-white').html(i);
        label.addClass('text-blue-600');
      } else {
        circle.addClass('bg-gray-200 text-gray-500').html(i);
        label.addClass('text-gray-400');
      }
    });
    $('#stepLine1').removeClass('bg-gray-200 bg-green-500').addClass(step > 1 ? 'bg-green-500' : 'bg-gray-200');
    $('#stepLine2').removeClass('bg-gray-200 bg-green-500').addClass(step > 2 ? 'bg-green-500' : 'bg-gray-200');
  }

  // Back buttons
  $(document).on('click', '#backToStep1', function () { goToStep(1); });
  $(document).on('click', '#backToStep2', function () { goToStep(2); });

  // ════════════════════════════════════════════════════════
  // ANALYZE DESCRIPTION AS USER TYPES
  // ════════════════════════════════════════════════════════
  $('#problemDescription').on('input', function () {
    const text = $(this).val().trim();

    clearTimeout(analysisTimeout);
    matchedRule = null;

    if (text.length < 3) {
      hideTip();
      hideKeywords();
      $('#manualSelectionPrompt').addClass('hidden');
      return;
    }

    // Show typing indicator
    $('#typingIndicator').removeClass('hidden');

    analysisTimeout = setTimeout(function () {
      analyzeText(text);
      $('#typingIndicator').addClass('hidden');
    }, 600);
  });

  function analyzeText(text) {
    let bestMatch = null;
    let matchedKeywords = [];

    for (const rule of keywordRules) {
      const matches = text.match(new RegExp(rule.pattern, 'gi'));
      if (matches && matches.length > 0) {
        matchedKeywords = [...new Set(matches.map(m => m.toLowerCase()))];
        bestMatch = rule;
        break;  // first match wins (priority order)
      }
    }

    if (bestMatch) {
      matchedRule = bestMatch;
      showKeywords(matchedKeywords);
      showTip(bestMatch, matchedKeywords[0]);
      $('#manualSelectionPrompt').addClass('hidden');
    } else {
      hideTip();
      hideKeywords();
      // Show manual selection if user has typed 20+ words with no match
      const wordCount = text.split(/\s+/).filter(w => w.length > 0).length;
      if (wordCount >= 5) {
        $('#manualSelectionPrompt').removeClass('hidden');
      } else {
        $('#manualSelectionPrompt').addClass('hidden');
      }
    }
  }

  // ════════════════════════════════════════════════════════
  // DETECTED KEYWORDS DISPLAY
  // ════════════════════════════════════════════════════════
  function showKeywords(keywords) {
    const container = $('#detectedKeywords');
    container.find('.keyword-pill').remove();
    keywords.forEach(kw => {
      container.append(`<span class="keyword-pill inline-block px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">${escHtml(kw)}</span>`);
    });
    container.removeClass('hidden');
  }

  function hideKeywords() {
    $('#detectedKeywords').addClass('hidden').find('.keyword-pill').remove();
  }

  // ════════════════════════════════════════════════════════
  // TROUBLESHOOTING TIP
  // ════════════════════════════════════════════════════════
  function showTip(rule, keyword) {
    const tipData = rule.tips[keyword] || rule.tips['default'];
    const colors = {
      NICM:   { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800', btn: 'bg-green-600 hover:bg-green-700', icon: '🌐' },
      ICTRAM: { bg: 'bg-amber-50', border: 'border-amber-200', text: 'text-amber-800', btn: 'bg-amber-600 hover:bg-amber-700', icon: '🖥️' },
      MIS:    { bg: 'bg-purple-50', border: 'border-purple-200', text: 'text-purple-800', btn: 'bg-purple-600 hover:bg-purple-700', icon: '🔑' },
    };
    const c = colors[rule.sectionAcronym] || colors.NICM;

    const html = `
      <div class="tip-enter ${c.bg} ${c.border} border rounded-2xl p-5 space-y-3">
        <div class="flex items-start gap-3">
          <span class="text-2xl leading-none mt-0.5">${c.icon}</span>
          <div class="flex-1">
            <p class="font-bold ${c.text} text-sm">${escHtml(tipData.title)}</p>
            <p class="text-sm ${c.text} opacity-80 mt-1 leading-relaxed">${escHtml(tipData.body)}</p>
          </div>
        </div>
        <div class="flex items-center gap-3 pt-2 border-t ${c.border}">
          <button type="button" id="tipResolvedBtn"
                  class="px-4 py-2 rounded-xl bg-white border ${c.border} text-sm font-semibold ${c.text} hover:bg-gray-50 transition-all">
            ✓ Resolved! Thanks
          </button>
          <button type="button" id="tipNotResolvedBtn"
                  class="${c.btn} text-white px-4 py-2 rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all">
            ✗ Not Resolved — Continue
          </button>
        </div>
      </div>
    `;
    $('#troubleshootingTip').html(html).removeClass('hidden');
  }

  function hideTip() {
    $('#troubleshootingTip').addClass('hidden').empty();
  }

  // ════════════════════════════════════════════════════════
  // TIP BUTTON HANDLERS
  // ════════════════════════════════════════════════════════
  $(document).on('click', '#tipResolvedBtn', function () {
    // User says it's resolved – clear everything
    $('#troubleshootingTip').html(`
      <div class="tip-enter bg-green-50 border border-green-200 rounded-2xl p-5 text-center">
        <p class="text-green-700 font-bold text-sm">🎉 Glad that helped! If you need anything else, just keep typing.</p>
      </div>
    `);
    setTimeout(() => hideTip(), 3000);
  });

  $(document).on('click', '#tipNotResolvedBtn', function () {
    if (!matchedRule) return;

    const sectionId = acronymToId[matchedRule.sectionAcronym];
    if (!sectionId) return;

    // Set hidden section_id
    $('#sectionId').val(sectionId);

    // Update routed banner
    const sectionInfo = sectionMap[String(sectionId)];
    $('#routedSectionName').text(sectionInfo ? sectionInfo.name + ' (' + sectionInfo.acronym + ')' : matchedRule.sectionAcronym);

    // Hide all section panels, show the correct one
    $('#nicmFields, #ictramFields, #misFields').addClass('hidden');
    const acronym = matchedRule.sectionAcronym;
    if (acronym === 'NICM')   $('#nicmFields').removeClass('hidden');
    if (acronym === 'ICTRAM') $('#ictramFields').removeClass('hidden');
    if (acronym === 'MIS')    $('#misFields').removeClass('hidden');
    activateSection(acronym);

    // Load section data & advance to Step 2
    loadSectionData(sectionId, acronym);
    goToStep(2);
  });

  // ════════════════════════════════════════════════════════
  // MANUAL SECTION SELECTION
  // ════════════════════════════════════════════════════════
  $(document).on('click', '.manual-section-btn', function () {
    const sectionId = $(this).data('section-id');
    const acronym   = $(this).data('section-acronym');
    const name      = $(this).data('section-name');

    // Set hidden section_id
    $('#sectionId').val(sectionId);

    // Update routed banner
    $('#routedSectionName').text(name + ' (' + acronym + ')');

    // Hide all section panels, then show the correct one
    $('#nicmFields, #ictramFields, #misFields').addClass('hidden');
    if (acronym === 'NICM')   $('#nicmFields').removeClass('hidden');
    if (acronym === 'ICTRAM') $('#ictramFields').removeClass('hidden');
    if (acronym === 'MIS')    $('#misFields').removeClass('hidden');
    activateSection(acronym);

    // Load section data & advance to Step 2
    loadSectionData(sectionId, acronym);
    goToStep(2);
  });

  // ════════════════════════════════════════════════════════
  // LOAD SECTION DATA VIA AJAX
  // ════════════════════════════════════════════════════════
  function loadSectionData(sectionId, acronym) {
    if (sectionDataCache[sectionId]) {
      populateSectionFields(sectionDataCache[sectionId], acronym);
      return;
    }

    $.getJSON('<?= base_url(($rolePrefix ?? 'employee') . "/create-ticket/section-data") ?>/' + sectionId, function (data) {
      sectionDataCache[sectionId] = data;
      populateSectionFields(data, acronym);
    }).fail(function () {
      console.error('Failed to load section data');
    });
  }

  function populateSectionFields(data, acronym) {
    if (acronym === 'NICM') {
      populateSelect('#nicmEquipment', data.equipment, 'equipment_id', 'name', 'Select equipment…');
      populateSelect('#nicmAction', data.request_actions, 'action_id', 'action_name', 'Select action…');
    }

    if (acronym === 'ICTRAM') {
      populateSelect('#ictramEquipment', data.equipment, 'equipment_id', 'name', 'Select equipment…');
      populateSelect('#ictramRequestType', data.request_types, 'request_type_id', 'request_type_name', 'Select request type…');
      populateCheckboxes('#ictramHardwareIssues', data.hardware_issues, 'issue_type_id', 'issue_type_name', 'hardware_issues[]');
      populateCheckboxes('#ictramSoftwareIssues', data.software_issues, 'issue_type_id', 'issue_type_name', 'software_issues[]');
    }

    if (acronym === 'MIS') {
      populateRadioCards('#misRequestTypes', data.request_types, 'request_type_id', 'request_type_name');
    }
  }

  // ════════════════════════════════════════════════════════
  // POPULATE HELPERS
  // ════════════════════════════════════════════════════════
  function populateSelect(selector, items, valueKey, labelKey, placeholder) {
    const el = $(selector);
    el.empty();
    el.append(`<option value="" disabled selected>${escHtml(placeholder)}</option>`);
    (items || []).forEach(item => {
      el.append(`<option value="${escHtml(String(item[valueKey]))}">${escHtml(item[labelKey])}</option>`);
    });
  }

  function populateCheckboxes(container, items, valueKey, labelKey, inputName) {
    const el = $(container);
    el.empty();
    if (!items || items.length === 0) {
      el.html('<p class="text-xs text-gray-400 col-span-full">None available.</p>');
      return;
    }
    items.forEach(item => {
      el.append(`
        <label class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors border border-transparent hover:border-blue-100">
          <input type="checkbox" name="${inputName}" value="${escHtml(String(item[valueKey]))}"
                 class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-400">
          <span class="text-sm text-gray-700">${escHtml(item[labelKey])}</span>
        </label>
      `);
    });
  }

  function populateRadioCards(container, items, valueKey, labelKey) {
    const el = $(container);
    el.empty();
    if (!items || items.length === 0) {
      el.html('<p class="text-xs text-gray-400 col-span-full">None available.</p>');
      return;
    }
    items.forEach(item => {
      el.append(`
        <label class="relative flex flex-col p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-blue-50 transition group has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
          <input type="radio" name="request_type_id" value="${escHtml(String(item[valueKey]))}"
                 class="sr-only mis-request-type-radio">
          <span class="font-semibold text-gray-900 text-sm">${escHtml(item[labelKey])}</span>
        </label>
      `);
    });
  }

  // ════════════════════════════════════════════════════════
  // MIS: DEPENDENT DROPDOWNS (request type → platform/action)
  // ════════════════════════════════════════════════════════
  $(document).on('change', '.mis-request-type-radio', function () {
    const requestTypeId = $(this).val();
    $('#misDependentFields').removeClass('hidden');

    $.getJSON('<?= base_url(($rolePrefix ?? 'employee') . "/create-ticket/request-type-data") ?>/' + requestTypeId, function (data) {
      populateSelect('#misPlatform', data.platforms, 'platform_id', 'platform_name', 'Select platform…');
      populateSelect('#misAction', data.actions, 'action_id', 'action_name', 'Select action…');
    });
  });

  // ════════════════════════════════════════════════════════
  // UTILITY
  // ════════════════════════════════════════════════════════
  function escHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = String(str);
    return div.innerHTML;
  }

  // ════════════════════════════════════════════════════════
  // FORM SUBMIT — re-enable active section fields and
  // ensure hidden wizard-step fields don't block submit
  // ════════════════════════════════════════════════════════
  $('#ticketForm').on('submit', function () {
    // Re-enable the active section's fields (in case any were toggled)
    const acronym = getActiveSection();
    if (acronym) activateSection(acronym);

    // Enable Step 1 common fields that are now hidden (inside wizardStep1)
    $('#wizardStep1 select, #wizardStep1 input, #wizardStep1 textarea').prop('disabled', false);
  });

  // ════════════════════════════════════════════════════════
  // ACCORDION TOGGLE
  // ════════════════════════════════════════════════════════
  $(document).on('click', '.accordion-toggle', function () {
    const body    = $(this).next('.accordion-body');
    const chevron = $(this).find('.accordion-chevron');
    body.toggleClass('open');
    chevron.toggleClass('rotate');
  });

  // ════════════════════════════════════════════════════════
  // FILE NAME DISPLAY
  // ════════════════════════════════════════════════════════
  $(document).on('change', '#equipmentPhoto', function () {
    const file = this.files[0];
    if (file) {
      $('#fileNameText').text(file.name);
      $('#fileNameDisplay').removeClass('hidden');
    } else {
      $('#fileNameDisplay').addClass('hidden');
    }
  });

  // ════════════════════════════════════════════════════════
  // STEP 2 → 3 VALIDATION + SUMMARY
  // ════════════════════════════════════════════════════════
  $(document).on('click', '#nextToStep3', function () {
    // Validate required fields in the visible section
    const acronym = getActiveSection();
    let valid = true;
    let firstInvalid = null;

    if (acronym === 'NICM') {
      valid = validateField('#nicmEquipment') && validateField('#nicmAction');
    } else if (acronym === 'ICTRAM') {
      const v1 = validateField('#ictramBuilding');
      const v2 = validateField('#ictramEquipment');
      const v3 = validateField('#ictramPriority');
      const v4 = validateField('#ictramRequestType');
      valid = v1 && v2 && v3 && v4;
    } else if (acronym === 'MIS') {
      const v1 = validateField('#misRequestorNumber');
      const v2 = $('input[name="request_type_id"]:checked').length > 0;
      if (!v2) {
        $('#misRequestTypes').addClass('ring-2 ring-red-400 rounded-xl');
        setTimeout(() => $('#misRequestTypes').removeClass('ring-2 ring-red-400 rounded-xl'), 2000);
      }
      valid = v1 && v2;
    }

    if (!valid) return;

    buildSummary(acronym);
    goToStep(3);
  });

  function validateField(selector) {
    const el = $(selector);
    const val = el.val();
    if (!val || (typeof val === 'string' && !val.trim())) {
      el.addClass('ring-2 ring-red-400 border-red-300').focus();
      setTimeout(() => el.removeClass('ring-2 ring-red-400 border-red-300'), 2000);
      return false;
    }
    return true;
  }

  function getActiveSection() {
    if (!$('#nicmFields').hasClass('hidden'))   return 'NICM';
    if (!$('#ictramFields').hasClass('hidden')) return 'ICTRAM';
    if (!$('#misFields').hasClass('hidden'))    return 'MIS';
    return '';
  }

  function buildSummary(acronym) {
    const rows = [];

    // Common details
    const userName = <?= json_encode($user['name'] ?? 'Unknown') ?>;
    rows.push({ label: 'Requestor', value: escHtml(userName) });
    rows.push({ label: 'Office', value: escHtml($('#officeSelect option:selected').text()) });
    rows.push({ label: 'Problem', value: escHtml($('#problemDescription').val()) });

    // Routed section
    rows.push({ label: 'Routed To', value: escHtml($('#routedSectionName').text()), highlight: true });

    // Section-specific
    if (acronym === 'NICM') {
      rows.push({ label: 'Equipment', value: escHtml($('#nicmEquipment option:selected').text()) });
      rows.push({ label: 'Action', value: escHtml($('#nicmAction option:selected').text()) });
    }

    if (acronym === 'ICTRAM') {
      rows.push({ label: 'Building', value: escHtml($('#ictramBuilding option:selected').text()) });
      rows.push({ label: 'Equipment', value: escHtml($('#ictramEquipment option:selected').text()) });
      const brand = $('input[name="brand"]').val();
      const model = $('input[name="model"]').val();
      if (brand) rows.push({ label: 'Brand', value: escHtml(brand) });
      if (model) rows.push({ label: 'Model', value: escHtml(model) });
      rows.push({ label: 'Priority', value: escHtml($('#ictramPriority option:selected').text()) });

      const hw = [];
      $('#ictramHardwareIssues input[type="checkbox"]:checked').each(function () {
        hw.push(escHtml($(this).closest('label').find('span').text()));
      });
      if (hw.length) rows.push({ label: 'Hardware Issues', value: hw.join(', ') });

      const sw = [];
      $('#ictramSoftwareIssues input[type="checkbox"]:checked').each(function () {
        sw.push(escHtml($(this).closest('label').find('span').text()));
      });
      if (sw.length) rows.push({ label: 'Software Issues', value: sw.join(', ') });

      rows.push({ label: 'Request Type', value: escHtml($('#ictramRequestType option:selected').text()) });
    }

    if (acronym === 'MIS') {
      rows.push({ label: 'ID Number', value: escHtml($('#misRequestorNumber').val()) });
      const rt = $('input[name="request_type_id"]:checked');
      if (rt.length) rows.push({ label: 'Request Type', value: escHtml(rt.closest('label').find('span').text()) });
      const plat = $('#misPlatform option:selected').text();
      const act  = $('#misAction option:selected').text();
      if (plat && plat !== 'Select platform…') rows.push({ label: 'Platform', value: escHtml(plat) });
      if (act && act !== 'Select action…')    rows.push({ label: 'Action', value: escHtml(act) });
    }

    // Additional
    const notes = $('#additionalDetails').val();
    if (notes && notes.trim()) rows.push({ label: 'Extra Notes', value: escHtml(notes) });

    const file = document.getElementById('equipmentPhoto').files[0];
    if (file) rows.push({ label: 'Attachment', value: escHtml(file.name) });

    // Build HTML
    let html = '<div class="divide-y divide-gray-100 rounded-xl border border-gray-200 overflow-hidden">';
    rows.forEach(function (r) {
      const hl = r.highlight ? ' bg-blue-50' : '';
      html += `<div class="flex items-start gap-4 px-4 py-3${hl}">`;
      html += `<span class="text-xs font-semibold text-gray-500 uppercase tracking-wide w-28 shrink-0 pt-0.5">${r.label}</span>`;
      html += `<span class="text-sm text-gray-900 flex-1">${r.value || '<span class="text-gray-300 italic">—</span>'}</span>`;
      html += '</div>';
    });
    html += '</div>';

    $('#summaryContent').html(html);
  }
});
</script>