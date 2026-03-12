<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-3xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('super-admin/employees') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Employees
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Employee Profile</h2>
    <p class="text-sm text-gray-500 mt-1">View and manage employee details, section, role, and expertise.</p>
  </div>

  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="fade-in bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="fade-in bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Employee Info Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6">
    <div class="flex items-center gap-4 mb-2">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-xl flex items-center justify-center font-bold shadow-lg overflow-hidden">
        <?php if ($employee['avatar']): ?>
          <img src="<?= esc($employee['avatar']) ?>" alt="Avatar" class="w-full h-full object-cover rounded-2xl">
        <?php else: ?>
          <?= esc($employee['initials']) ?>
        <?php endif; ?>
      </div>
      <div>
        <p class="text-lg font-extrabold text-gray-900"><?= esc($employee['name']) ?></p>
        <p class="text-sm text-gray-500 mono"><?= esc($employee['email']) ?></p>
        <div class="flex items-center gap-2 mt-1.5">
          <span class="inline-block px-2.5 py-0.5 bg-<?= esc($employee['role_color']) ?>-100 text-<?= esc($employee['role_color']) ?>-600 text-xs font-bold rounded-full">
            <?= esc($employee['role']) ?>
          </span>
          <?php if (! empty($employee['acronym'])): ?>
          <span class="inline-block px-2.5 py-0.5 bg-amber-100 text-amber-600 text-xs font-bold rounded-full">
            <?= esc($employee['acronym']) ?>
          </span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Section & Role Card -->
  <div class="fade-in delay-2 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6">
    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
      Section &amp; Role
    </h3>

    <form action="<?= base_url('super-admin/employees/edit/' . $employee['user_id']) ?>" method="POST" class="space-y-5" id="sectionRoleForm">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">

      <!-- Section -->
      <div>
        <label for="sectionSelect" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
        <select
          name="section_id"
          id="sectionSelect"
          required
          class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer"
        >
          <option value="" disabled>Select a section</option>
          <?php if (!empty($sections)): ?>
            <?php foreach ($sections as $section): ?>
              <option
                value="<?= esc($section['section_id']) ?>"
                data-acronym="<?= esc($section['acronym']) ?>"
                <?= ((int) $section['section_id'] === (int) $employee['section_id']) ? 'selected' : '' ?>
              >
                <?= esc($section['name']) ?> (<?= esc($section['acronym']) ?>)
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
        <p id="sectionChangeWarning" class="hidden text-xs text-amber-600 mt-1.5 font-medium">
          ⚠ Changing the section will remove all current expertise (expertise is section-specific).
        </p>
      </div>

      <!-- Role -->
      <div>
        <label for="roleSelect" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
        <select
          name="role_id"
          id="roleSelect"
          required
          class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer"
        >
          <option value="" disabled>Select a role</option>
        </select>
        <p id="roleHint" class="text-xs text-gray-400 mt-1.5">Available roles depend on the selected section.</p>
      </div>

      <div class="pt-1">
        <button
          type="submit"
          class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all"
        >
          Save Changes
        </button>
      </div>
    </form>
  </div>

  <!-- Expertise Card -->
  <div class="fade-in delay-3 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6">
    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
      <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
      Expertise
    </h3>

    <!-- Current Expertise Tags -->
    <div id="currentExpertise" class="flex flex-wrap gap-2 mb-4">
      <?php if (empty($employeeExpertise)): ?>
        <p class="text-sm text-gray-400" id="noExpertiseMsg">No expertise assigned yet.</p>
      <?php else: ?>
        <?php foreach ($employeeExpertise as $exp): ?>
          <span class="expertise-chip inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs font-semibold pl-3 pr-1.5 py-1.5 rounded-lg" data-id="<?= esc($exp['expertise_id']) ?>">
            <?= esc($exp['skill']) ?>
            <button type="button"
                    class="remove-expertise-btn hover:bg-blue-200 rounded p-0.5 transition-colors"
                    data-expertise-id="<?= esc($exp['expertise_id']) ?>"
                    data-skill="<?= esc($exp['skill']) ?>"
                    title="Remove expertise">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Add Expertise -->
    <div class="border-t border-gray-100 pt-4">
      <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Add Expertise</label>
      <div class="relative">
        <div id="addExpertiseContainer"
             class="w-full min-h-[44px] px-3 py-2 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus-within:ring-2 focus-within:ring-blue-400 focus-within:bg-white transition-all flex flex-wrap gap-2 items-center cursor-text"
             onclick="document.getElementById('expertiseSearch').focus()">
          <input
            type="text"
            id="expertiseSearch"
            autocomplete="off"
            placeholder="Type to search expertise…"
            class="flex-1 min-w-[140px] bg-transparent outline-none text-sm py-1"
          >
        </div>
        <div id="expertiseDropdown" class="relative">
          <div id="expertiseOptions" class="hidden absolute z-30 left-0 right-0 mt-1 bg-white border border-blue-100 rounded-xl shadow-lg max-h-48 overflow-y-auto"></div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 space-y-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
      </div>
      <div>
        <p class="font-bold text-gray-900 text-sm">Remove Expertise</p>
        <p id="confirmText" class="text-xs text-gray-500">Are you sure?</p>
      </div>
    </div>
    <div class="flex gap-2">
      <button id="confirmCancel" class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
      <button id="confirmDelete" class="flex-1 px-4 py-2.5 text-sm font-bold rounded-xl bg-red-600 hover:bg-red-700 text-white transition-colors">Remove</button>
    </div>
  </div>
</div>

<!-- Section Change Confirmation Modal -->
<div id="sectionChangeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 space-y-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div>
        <p class="font-bold text-gray-900 text-sm">Section Change Warning</p>
        <p class="text-xs text-gray-500">Changing the section will <strong>remove all current expertise</strong> because expertise is linked to a specific section.</p>
      </div>
    </div>
    <div class="flex gap-2">
      <button id="sectionChangeCancel" class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
      <button id="sectionChangeConfirm" class="flex-1 px-4 py-2.5 text-sm font-bold rounded-xl bg-amber-500 hover:bg-amber-600 text-white transition-colors">Continue</button>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
  const employeeUserId = <?= (int) $employee['user_id'] ?>;
  const originalSectionId = '<?= esc($employee['section_id']) ?>';
  const originalRoleId = <?= (int) $employee['role_id'] ?>;

  // ─── Role population on load & section change ──────────
  function populateRoles(sectionId, acronym, preselectRoleId) {
    const roleSelect = $('#roleSelect');
    roleSelect.empty();

    let roles = [];
    if (acronym === 'NICM' || acronym === 'ICTRAM' || acronym === 'MIS') {
      roles = [
        { value: 2, label: 'Head of Section' },
        { value: 3, label: 'ICTU Staff' }
      ];
    } else {
      // Default: all sections support Head of Section + ICTU Staff
      roles = [
        { value: 2, label: 'Head of Section' },
        { value: 3, label: 'ICTU Staff' }
      ];
    }

    if (roles.length === 0) {
      roleSelect.html('<option value="" disabled selected>No roles for this section</option>');
      roleSelect.prop('disabled', true).removeClass('bg-white cursor-pointer text-gray-900').addClass('bg-gray-100 cursor-not-allowed text-gray-400');
      $('#roleHint').text('No roles available for this section.').removeClass('text-amber-600').addClass('text-gray-400');
      return;
    }

    // Check if section already has a Head of Section
    $.getJSON('<?= base_url("super-admin/employees/check-head") ?>', { section_id: sectionId }, function (data) {
      roleSelect.empty();
      roleSelect.append('<option value="" disabled>Select a role</option>');

      roles.forEach(function (r) {
        if (r.value === 2 && data.has_head && data.head_name) {
          // Check if current employee IS the head — allow them to keep their own role
          if (sectionId === originalSectionId && originalRoleId === 2) {
            const opt = $(`<option value="${r.value}">${r.label}</option>`);
            roleSelect.append(opt);
          } else {
            roleSelect.append(`<option value="${r.value}" disabled>${r.label} (Assigned to ${escHtml(data.head_name)})</option>`);
          }
        } else {
          roleSelect.append(`<option value="${r.value}">${r.label}</option>`);
        }
      });

      // Preselect
      if (preselectRoleId) {
        roleSelect.val(String(preselectRoleId));
      }

      roleSelect.prop('disabled', false).removeClass('bg-gray-100 cursor-not-allowed text-gray-400').addClass('bg-white cursor-pointer text-gray-900');

      if (data.has_head && !(sectionId === originalSectionId && originalRoleId === 2)) {
        $('#roleHint').text('Head of Section is already assigned to ' + data.head_name + '.').removeClass('text-gray-400').addClass('text-amber-600');
      } else {
        $('#roleHint').text('Available roles depend on the selected section.').removeClass('text-amber-600').addClass('text-gray-400');
      }
    }).fail(function () {
      roleSelect.append('<option value="" disabled>Select a role</option>');
      roles.forEach(function (r) {
        roleSelect.append(`<option value="${r.value}">${r.label}</option>`);
      });
      if (preselectRoleId) roleSelect.val(String(preselectRoleId));
      roleSelect.prop('disabled', false).removeClass('bg-gray-100 cursor-not-allowed text-gray-400').addClass('bg-white cursor-pointer text-gray-900');
    });
  }

  // Initial population
  (function () {
    const sel = $('#sectionSelect').find(':selected');
    if (sel.val()) {
      populateRoles(sel.val(), sel.data('acronym'), originalRoleId);
    }
  })();

  // Section change
  $('#sectionSelect').on('change', function () {
    const opt = $(this).find(':selected');
    const newSectionId = $(this).val();

    // Show warning if section changed
    if (newSectionId !== originalSectionId) {
      $('#sectionChangeWarning').removeClass('hidden');
    } else {
      $('#sectionChangeWarning').addClass('hidden');
    }

    populateRoles(newSectionId, opt.data('acronym'), null);
  });

  // ─── Section Change Confirmation on form submit ────────
  $('#sectionRoleForm').on('submit', function (e) {
    const newSectionId = $('#sectionSelect').val();
    if (newSectionId !== originalSectionId) {
      e.preventDefault();
      $('#sectionChangeModal').removeClass('hidden');
    }
  });

  $('#sectionChangeConfirm').on('click', function () {
    $('#sectionChangeModal').addClass('hidden');
    // Submit without re-triggering the handler
    $('#sectionRoleForm').off('submit').submit();
  });

  $('#sectionChangeCancel').on('click', function () {
    $('#sectionChangeModal').addClass('hidden');
  });

  // ─── Remove Expertise ──────────────────────────────────
  let pendingRemoveId = null;
  let pendingRemoveChip = null;

  $(document).on('click', '.remove-expertise-btn', function (e) {
    e.stopPropagation();
    pendingRemoveId = $(this).data('expertise-id');
    pendingRemoveChip = $(this).closest('.expertise-chip');
    const skill = $(this).data('skill');
    $('#confirmText').text('Remove "' + skill + '" from this employee?');
    $('#confirmModal').removeClass('hidden');
  });

  $('#confirmCancel').on('click', function () {
    $('#confirmModal').addClass('hidden');
    pendingRemoveId = null;
    pendingRemoveChip = null;
  });

  $('#confirmDelete').on('click', function () {
    if (!pendingRemoveId) return;

    const btn = $(this);
    btn.prop('disabled', true).text('Removing…');

    $.ajax({
      url: '<?= base_url("super-admin/employees") ?>/' + employeeUserId + '/expertise/' + pendingRemoveId,
      method: 'POST',
      data: { _method: 'DELETE', <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
      dataType: 'json',
      success: function () {
        if (pendingRemoveChip) pendingRemoveChip.fadeOut(200, function () { $(this).remove(); checkEmpty(); });
        $('#confirmModal').addClass('hidden');
      },
      error: function () {
        alert('Failed to remove expertise. Please try again.');
        $('#confirmModal').addClass('hidden');
      },
      complete: function () {
        btn.prop('disabled', false).text('Remove');
        pendingRemoveId = null;
        pendingRemoveChip = null;
      }
    });
  });

  function checkEmpty() {
    if ($('#currentExpertise .expertise-chip').length === 0) {
      if ($('#noExpertiseMsg').length === 0) {
        $('#currentExpertise').prepend('<p class="text-sm text-gray-400" id="noExpertiseMsg">No expertise assigned yet.</p>');
      }
    }
  }

  // ─── Add Expertise (search & add) ─────────────────────
  let expertiseSearchTimeout = null;

  $('#expertiseSearch').on('input', function () {
    const q = $(this).val().trim();
    clearTimeout(expertiseSearchTimeout);
    if (q.length < 1) { $('#expertiseOptions').addClass('hidden'); return; }

    expertiseSearchTimeout = setTimeout(function () {
      const sectionId = $('#sectionSelect').val();
      $.getJSON('<?= base_url("super-admin/expertise/search") ?>', { q, section_id: sectionId || '' }, function (items) {
        renderExpertiseDropdown(items);
      });
    }, 250);
  });

  $('#expertiseSearch').on('focus', function () {
    const q = $(this).val().trim();
    if (q.length >= 1) {
      const sectionId = $('#sectionSelect').val();
      $.getJSON('<?= base_url("super-admin/expertise/search") ?>', { q, section_id: sectionId || '' }, function (items) {
        renderExpertiseDropdown(items);
      });
    }
  });

  $(document).on('click', function (e) {
    if (!$(e.target).closest('#addExpertiseContainer, #expertiseDropdown').length) {
      $('#expertiseOptions').addClass('hidden');
    }
  });

  function renderExpertiseDropdown(items) {
    const box = $('#expertiseOptions');
    box.empty();

    // Get currently assigned IDs
    const currentIds = [];
    $('#currentExpertise .expertise-chip').each(function () {
      currentIds.push(Number($(this).data('id')));
    });

    const filtered = items.filter(i => !currentIds.includes(i.expertise_id));

    if (filtered.length === 0) {
      box.html('<div class="px-4 py-3 text-xs text-gray-400 text-center">No matching expertise.</div>');
    } else {
      filtered.forEach(function (item) {
        box.append(`
          <div class="px-4 py-2.5 hover:bg-blue-50 cursor-pointer text-sm text-gray-700 flex items-center justify-between transition-colors expertise-option" data-id="${item.expertise_id}" data-skill="${escHtml(item.skill)}">
            <span>${escHtml(item.skill)}</span>
            <span class="text-xs text-gray-400">${escHtml(item.acronym || '')}</span>
          </div>
        `);
      });
    }
    box.removeClass('hidden');
  }

  $(document).on('click', '.expertise-option', function () {
    const id = Number($(this).data('id'));
    const skill = $(this).data('skill');
    addExpertiseToEmployee(id, skill);
    $('#expertiseSearch').val('');
    $('#expertiseOptions').addClass('hidden');
  });

  function addExpertiseToEmployee(id, skill) {
    $.ajax({
      url: '<?= base_url("super-admin/employees") ?>/' + employeeUserId + '/expertise',
      method: 'POST',
      data: { expertise_id: id, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
      dataType: 'json',
      success: function () {
        // Remove "no expertise" msg
        $('#noExpertiseMsg').remove();

        // Add chip
        const chip = $(`
          <span class="expertise-chip inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-xs font-semibold pl-3 pr-1.5 py-1.5 rounded-lg" data-id="${id}">
            ${escHtml(skill)}
            <button type="button"
                    class="remove-expertise-btn hover:bg-blue-200 rounded p-0.5 transition-colors"
                    data-expertise-id="${id}"
                    data-skill="${escHtml(skill)}"
                    title="Remove expertise">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </span>
        `);
        chip.hide().appendTo('#currentExpertise').fadeIn(200);
      },
      error: function () {
        alert('Failed to add expertise. Please try again.');
      }
    });
  }

  // ─── Helpers ───────────────────────────────────────────
  function escHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }
});
</script>
<?= $this->endSection() ?>
