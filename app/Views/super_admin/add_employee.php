<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6 max-w-3xl mx-auto">

  <!-- Page Header -->
  <div class="fade-in">
    <a href="<?= base_url('super-admin/employees') ?>" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-medium mb-4 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Employees
    </a>
    <h2 class="text-2xl font-extrabold text-gray-900">Add Employee</h2>
    <p class="text-sm text-gray-500 mt-1">Search for a registered user and assign them an employee role.</p>
  </div>

  <!-- Card -->
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl border border-blue-100/50 shadow-lg p-6 space-y-6">

    <?php if (session()->getFlashdata('error')): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Step 1: Search User -->
    <div id="searchSection">
      <label class="block text-sm font-semibold text-gray-700 mb-2">Search User</label>
      <div class="relative">
        <input
          type="text"
          id="userSearch"
          autocomplete="off"
          placeholder="Type a name to search..."
          class="w-full pl-10 pr-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all"
        >
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
          <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
        </div>
      </div>

      <!-- Suggestions List -->
      <div id="suggestionsList" class="mt-3 hidden">
        <div id="suggestionsContainer" class="divide-y divide-gray-100 border border-blue-100 rounded-xl overflow-hidden bg-white shadow-sm max-h-64 overflow-y-auto"></div>
      </div>
    </div>

    <!-- Selected User Display -->
    <div id="selectedUserSection" class="hidden">
      <label class="block text-sm font-semibold text-gray-700 mb-2">Selected User</label>
      <div class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
        <div class="flex items-center gap-3">
          <div id="selectedAvatar" class="w-10 h-10 rounded-xl bg-blue-600 text-white text-sm flex items-center justify-center font-bold overflow-hidden"></div>
          <div>
            <p id="selectedName" class="font-semibold text-gray-900 text-sm"></p>
            <p id="selectedEmail" class="text-xs text-gray-500 mono"></p>
          </div>
        </div>
        <button type="button" id="removeUserBtn" class="text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
          Remove
        </button>
      </div>
    </div>

    <!-- Step 2: Employee Details Form (shown after selecting user) -->
    <form id="employeeForm" action="<?= base_url('super-admin/employees/add') ?>" method="POST" class="hidden space-y-5">
      <?= csrf_field() ?>
      <input type="hidden" name="user_id" id="selectedUserId" value="">

      <!-- Section -->
      <div>
        <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
        <select
          name="section_id"
          id="sectionSelect"
          required
          class="w-full px-4 py-3 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-pointer"
        >
          <option value="" disabled selected>Select a section</option>
          <?php if (!empty($sections)): ?>
            <?php foreach ($sections as $section): ?>
              <option value="<?= esc($section['section_id']) ?>" data-acronym="<?= esc($section['acronym']) ?>">
                <?= esc($section['name']) ?> (<?= esc($section['acronym']) ?>)
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <!-- Role -->
      <div>
        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
        <select
          name="role_id"
          id="roleSelect"
          required
          disabled
          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-sm text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition-all appearance-none cursor-not-allowed"
        >
          <option value="" disabled selected>Select a section first</option>
        </select>
        <p id="roleHint" class="text-xs text-gray-400 mt-1.5">Available roles depend on the selected section.</p>
      </div>

      <!-- Expertise (tag picker) -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Expertise</label>
        <div id="expertiseTagContainer"
             class="w-full min-h-[48px] px-3 py-2 rounded-xl border border-blue-200 bg-blue-50/30 text-sm focus-within:ring-2 focus-within:ring-blue-400 focus-within:bg-white transition-all flex flex-wrap gap-2 items-center cursor-text"
             onclick="document.getElementById('expertiseSearch').focus()">
          <!-- selected tags injected here -->
          <input
            type="text"
            id="expertiseSearch"
            autocomplete="off"
            placeholder="Type to search expertise…"
            class="flex-1 min-w-[140px] bg-transparent outline-none text-sm py-1"
          >
        </div>
        <!-- hidden inputs go here -->
        <div id="expertiseHiddenInputs"></div>
        <!-- dropdown -->
        <div id="expertiseDropdown" class="relative">
          <div id="expertiseOptions" class="hidden absolute z-30 left-0 right-0 mt-1 bg-white border border-blue-100 rounded-xl shadow-lg max-h-48 overflow-y-auto"></div>
        </div>
        <p class="text-xs text-gray-400 mt-1.5">Select one or more skills from the list.</p>
      </div>

      <!-- Submit -->
      <div class="pt-2">
        <button
          type="submit"
          id="submitBtn"
          class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all"
        >
          Add as Employee
        </button>
      </div>
    </form>

  </div>
</div>

<script>
$(document).ready(function () {
  let searchTimeout = null;
  let selectedUser = null;

  // --- User Search ---
  $('#userSearch').on('input', function () {
    const query = $(this).val().trim();

    clearTimeout(searchTimeout);
    if (query.length < 2) {
      $('#suggestionsList').addClass('hidden');
      return;
    }

    $('#searchSpinner').removeClass('hidden');

    searchTimeout = setTimeout(function () {
      $.ajax({
        url: '<?= base_url("super-admin/employees/search-users") ?>',
        method: 'GET',
        data: { q: query },
        dataType: 'json',
        success: function (users) {
          $('#searchSpinner').addClass('hidden');
          renderSuggestions(users);
        },
        error: function () {
          $('#searchSpinner').addClass('hidden');
          $('#suggestionsContainer').html(
            '<div class="px-4 py-3 text-sm text-red-500">Failed to search users.</div>'
          );
          $('#suggestionsList').removeClass('hidden');
        }
      });
    }, 350);
  });

  function renderSuggestions(users) {
    const container = $('#suggestionsContainer');
    container.empty();

    if (users.length === 0) {
      container.html(
        '<div class="px-4 py-4 text-sm text-gray-400 text-center">No users found.</div>'
      );
      $('#suggestionsList').removeClass('hidden');
      return;
    }

    users.forEach(function (user) {
      const initials = user.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
      const avatarContent = user.avatar
        ? `<img src="${user.avatar}" class="w-full h-full object-cover rounded-lg" alt="">`
        : initials;

      const row = $(`
        <div class="flex items-center justify-between px-4 py-3 hover:bg-blue-50 transition-colors cursor-pointer group" data-user='${JSON.stringify(user)}'>
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-600 text-white text-xs flex items-center justify-center font-bold overflow-hidden">
              ${avatarContent}
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-800">${escHtml(user.name)}</p>
              <p class="text-xs text-gray-400 mono">${escHtml(user.email)}</p>
            </div>
          </div>
          <button type="button" class="select-user-btn opacity-0 group-hover:opacity-100 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-all">
            Select
          </button>
        </div>
      `);

      container.append(row);
    });

    $('#suggestionsList').removeClass('hidden');
  }

  // --- Select User ---
  $(document).on('click', '.select-user-btn, [data-user]', function (e) {
    e.stopPropagation();
    const row = $(this).closest('[data-user]');
    const user = row.data('user');
    selectUser(user);
  });

  function selectUser(user) {
    selectedUser = user;

    const initials = user.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
    if (user.avatar) {
      $('#selectedAvatar').html(`<img src="${user.avatar}" class="w-full h-full object-cover rounded-xl" alt="">`);
    } else {
      $('#selectedAvatar').text(initials);
    }

    $('#selectedName').text(user.name);
    $('#selectedEmail').text(user.email);
    $('#selectedUserId').val(user.user_id);

    // Hide search, show selected + form
    $('#searchSection').addClass('hidden');
    $('#suggestionsList').addClass('hidden');
    $('#selectedUserSection').removeClass('hidden');
    $('#employeeForm').removeClass('hidden');

    // Reset form fields
    $('#sectionSelect').val('');
    $('#roleSelect').html('<option value="" disabled selected>Select a section first</option>').prop('disabled', true).removeClass('bg-white cursor-pointer text-gray-900').addClass('bg-gray-100 cursor-not-allowed text-gray-400');
    clearExpertiseTags();
  }

  // --- Remove User ---
  $('#removeUserBtn').on('click', function () {
    selectedUser = null;
    $('#selectedUserId').val('');
    $('#selectedUserSection').addClass('hidden');
    $('#employeeForm').addClass('hidden');
    $('#searchSection').removeClass('hidden');
    $('#userSearch').val('').focus();
  });

  // --- Section → Role mapping ---
  $('#sectionSelect').on('change', function () {
    const selectedOption = $(this).find(':selected');
    const acronym = selectedOption.data('acronym');
    const sectionId = $(this).val();
    const roleSelect = $('#roleSelect');

    roleSelect.empty();
    $('#roleHint').text('Checking available roles…').removeClass('text-amber-600').addClass('text-gray-400');

    let roles = [];
    if (acronym === 'NICM' || acronym === 'ICTRAM') {
      roles = [
        { value: 2, label: 'Head of Section' },
        { value: 3, label: 'Technician' }
      ];
    } else if (acronym === 'MIS') {
      roles = [
        { value: 2, label: 'Head of Section' },
        { value: 4, label: 'Staff' }
      ];
    }

    if (roles.length === 0) {
      roleSelect.html('<option value="" disabled selected>No roles for this section</option>');
      roleSelect.prop('disabled', true).removeClass('bg-white cursor-pointer text-gray-900').addClass('bg-gray-100 cursor-not-allowed text-gray-400');
      $('#roleHint').text('Available roles depend on the selected section.').removeClass('text-amber-600').addClass('text-gray-400');
      return;
    }

    // Check if section already has a Head of Section
    $.getJSON('<?= base_url("super-admin/employees/check-head") ?>', { section_id: sectionId }, function (data) {
      roleSelect.empty();
      roleSelect.append('<option value="" disabled selected>Select a role</option>');

      roles.forEach(function (r) {
        if (r.value === 2 && data.has_head) {
          // Head of Section already assigned — show disabled option
          roleSelect.append(`<option value="${r.value}" disabled>${r.label} (Assigned to ${escHtml(data.head_name)})</option>`);
        } else {
          roleSelect.append(`<option value="${r.value}">${r.label}</option>`);
        }
      });

      roleSelect.prop('disabled', false).removeClass('bg-gray-100 cursor-not-allowed text-gray-400').addClass('bg-white cursor-pointer text-gray-900');

      if (data.has_head) {
        $('#roleHint').text('Head of Section is already assigned to ' + data.head_name + ' for this section.').removeClass('text-gray-400').addClass('text-amber-600');
      } else {
        $('#roleHint').text('Available roles depend on the selected section.').removeClass('text-amber-600').addClass('text-gray-400');
      }
    }).fail(function () {
      // Fallback — just show roles without checking
      roleSelect.append('<option value="" disabled selected>Select a role</option>');
      roles.forEach(function (r) {
        roleSelect.append(`<option value="${r.value}">${r.label}</option>`);
      });
      roleSelect.prop('disabled', false).removeClass('bg-gray-100 cursor-not-allowed text-gray-400').addClass('bg-white cursor-pointer text-gray-900');
      $('#roleHint').text('Available roles depend on the selected section.').removeClass('text-amber-600').addClass('text-gray-400');
    });
  });

  // --- Helpers ---
  function escHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  // ─── Expertise Tag Picker ──────────────────────────────
  let selectedExpertise = [];   // [{id, skill}]
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

  // Close dropdown on outside click
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#expertiseTagContainer, #expertiseDropdown').length) {
      $('#expertiseOptions').addClass('hidden');
    }
  });

  function renderExpertiseDropdown(items) {
    const box = $('#expertiseOptions');
    box.empty();
    const selectedIds = selectedExpertise.map(e => e.id);
    const filtered = items.filter(i => !selectedIds.includes(i.expertise_id));

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
    addExpertiseTag(id, skill);
    $('#expertiseSearch').val('');
    $('#expertiseOptions').addClass('hidden');
  });

  function addExpertiseTag(id, skill) {
    if (selectedExpertise.find(e => e.id === id)) return;
    selectedExpertise.push({ id, skill });
    refreshExpertiseTags();
  }

  function removeExpertiseTag(id) {
    selectedExpertise = selectedExpertise.filter(e => e.id !== id);
    refreshExpertiseTags();
  }

  function clearExpertiseTags() {
    selectedExpertise = [];
    refreshExpertiseTags();
  }

  function refreshExpertiseTags() {
    // Tags
    $('#expertiseTagContainer .expertise-tag').remove();
    const searchInput = $('#expertiseSearch');
    selectedExpertise.forEach(function (item) {
      const tag = $(`
        <span class="expertise-tag inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold pl-2.5 pr-1 py-1 rounded-lg">
          ${escHtml(item.skill)}
          <button type="button" class="hover:bg-blue-200 rounded p-0.5 transition-colors" data-remove-id="${item.id}">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </span>
      `);
      searchInput.before(tag);
    });

    // Hidden inputs
    const container = $('#expertiseHiddenInputs');
    container.empty();
    selectedExpertise.forEach(function (item) {
      container.append(`<input type="hidden" name="expertise_ids[]" value="${item.id}">`);
    });

    // Placeholder
    searchInput.attr('placeholder', selectedExpertise.length ? '' : 'Type to search expertise…');
  }

  $(document).on('click', '[data-remove-id]', function (e) {
    e.stopPropagation();
    removeExpertiseTag(Number($(this).data('remove-id')));
  });
});
</script>
<?= $this->endSection() ?>
