<?= $this->extend('super_admin/layout') ?>

<?= $this->section('pageTitle') ?>Logs<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>View & manage application logs<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- CSRF Token for AJAX requests -->
<meta name="csrf-token" content="<?= csrf_hash() ?>">

<div class="flex h-[calc(100vh-180px)] gap-4 p-6 overflow-hidden">
  
  <!-- Left Sidebar: File List -->
  <div class="w-80 bg-white rounded-xl shadow-md border border-blue-100/50 flex flex-col overflow-hidden">
    
    <!-- Sidebar Header -->
    <div class="px-6 py-4 border-b border-blue-100/50">
      <h2 class="text-lg font-bold text-gray-900">Log Files</h2>
      <p class="text-xs text-gray-500 mt-1"><?= count($logFiles) ?> file<?= count($logFiles) !== 1 ? 's' : '' ?></p>
    </div>

    <!-- File List -->
    <div class="flex-1 overflow-y-auto">
      <div id="fileList" class="divide-y divide-blue-100/30">
        <?php if (empty($logFiles)): ?>
          <div class="p-6 text-center text-gray-400">
            <p class="text-sm">No log files found</p>
          </div>
        <?php else: ?>
          <?php foreach ($logFiles as $file): ?>
            <div class="log-file-item cursor-pointer px-4 py-3 hover:bg-blue-50 transition border-l-4 border-transparent hover:border-blue-400" 
                 data-filename="<?= esc($file['filename']) ?>"
                 onclick="selectLogFile('<?= esc($file['filename']) ?>')">
              <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-sm text-gray-900"><?= esc($file['display_name']) ?></p>
                  <p class="text-xs text-gray-500 mt-1"><?= $file['file_size'] ?> KB</p>
                </div>
                <div class="flex-shrink-0">
                  <?php if ($file['error_count'] > 0): ?>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                      <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                      <?= $file['error_count'] ?>
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                      <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                      0
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Right Main Panel: Log Content -->
  <div class="flex-1 bg-white rounded-xl shadow-md border border-blue-100/50 flex flex-col overflow-hidden">
    
    <!-- Toolbar -->
    <div class="px-6 py-4 border-b border-blue-100/50 space-y-3">
      <div class="flex items-center justify-between gap-4">
        <div class="flex-1">
          <h2 id="logTitle" class="text-lg font-bold text-gray-900">Select a log file</h2>
          <p id="logSubtitle" class="text-xs text-gray-500 mt-1">Choose a file from the list to view</p>
        </div>
      </div>

      <div id="toolbar" class="hidden flex flex-wrap items-center gap-3">
        <!-- Search Input -->
        <div class="flex-1 min-w-56">
          <input type="text" 
                 id="searchInput" 
                 placeholder="Search logs..." 
                 class="w-full px-4 py-2 rounded-lg border border-blue-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                 onkeyup="filterLogs()">
        </div>

        <!-- Level Filter -->
        <select id="levelFilter" 
                class="px-4 py-2 rounded-lg border border-blue-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-700"
                onchange="filterLogs()">
          <option value="">All Levels</option>
          <option value="EMERGENCY">EMERGENCY</option>
          <option value="ALERT">ALERT</option>
          <option value="CRITICAL">CRITICAL</option>
          <option value="ERROR">ERROR</option>
          <option value="WARNING">WARNING</option>
          <option value="NOTICE">NOTICE</option>
          <option value="INFO">INFO</option>
          <option value="DEBUG">DEBUG</option>
        </select>

        <!-- Notification Status (Super Admin only) -->
        <?php if ($user['role_id'] == 1): ?>
          <span id="notificationStatus" class="px-3 py-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-700" title="Notification system status">Notifications: Loading...</span>

          <!-- Test Notification Button -->
          <button type="button"
                  id="testNotifyBtn"
                  class="px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition flex items-center gap-2"
                  onclick="sendTestNotification()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Test Alert
          </button>
        <?php else: ?>
          <span class="px-3 py-1 text-xs text-red-600 bg-red-50 rounded border border-red-200">
            ⚠ Test Alert unavailable (Super Admin only). Your role: <?= ($user['role_id'] ?? 0) ?>
          </span>
        <?php endif; ?>

        <!-- Download Button -->
        <button type="button"
                id="downloadBtn"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition flex items-center gap-2"
                onclick="downloadLog()">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
          </svg>
          Download
        </button>

        <!-- Delete Button (Super Admin only) -->
        <?php if ($user['role_id'] == 1): ?>
          <button type="button"
                  id="deleteBtn"
                  class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition flex items-center gap-2"
                  onclick="deleteLog()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete
          </button>
        <?php endif; ?>
      </div>
    </div>

    <!-- Error Summary Bar -->
    <div id="summaryBar" class="hidden px-6 py-4 border-b border-blue-100/50 bg-white">
      <div class="grid grid-cols-4 gap-4">
        <!-- Total Errors Card -->
        <div class="rounded-lg border border-red-200/50 bg-red-50 p-4">
          <p class="text-xs font-semibold text-red-700 uppercase tracking-wide">Total Errors</p>
          <p id="summaryErrors" class="text-2xl font-bold text-red-600 mt-1">0</p>
        </div>
        <!-- Total Warnings Card -->
        <div class="rounded-lg border border-amber-200/50 bg-amber-50 p-4">
          <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Total Warnings</p>
          <p id="summaryWarnings" class="text-2xl font-bold text-amber-600 mt-1">0</p>
        </div>
        <!-- Total Lines Card -->
        <div class="rounded-lg border border-blue-200/50 bg-blue-50 p-4">
          <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Total Lines</p>
          <p id="summaryTotal" class="text-2xl font-bold text-blue-600 mt-1">0</p>
        </div>
        <!-- Last Activity Card -->
        <div class="rounded-lg border border-gray-200/50 bg-gray-50 p-4">
          <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Last Activity</p>
          <p id="summaryLastActivity" class="text-sm font-semibold text-gray-600 mt-1">—</p>
        </div>
      </div>
    </div>

    <!-- Log Content -->
    <div id="logContent" class="flex-1 overflow-y-auto bg-gray-50 p-6">
      <div class="text-center text-gray-400 py-12">
        <p class="text-sm">Select a log file to view its contents</p>
      </div>
    </div>

    <!-- Status Bar -->
    <div id="statusBar" class="hidden px-6 py-3 border-t border-blue-100/50 bg-gray-50 text-xs text-gray-600 space-y-1">
      <div class="flex items-center justify-between gap-8">
        <div class="flex gap-8">
          <span>Errors: <span id="statsErrors" class="font-semibold text-red-600">0</span></span>
          <span>Warnings: <span id="statsWarnings" class="font-semibold text-amber-600">0</span></span>
          <span>Total Lines: <span id="statsTotalLines" class="font-semibold">0</span></span>
        </div>
        <div class="flex gap-8">
          <span id="statsFilename" class="mono"></span>
          <span id="statsSize" class="mono"></span>
          <span id="statsModified" class="mono"></span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  let currentFilename = null;
  let allLines = [];
  let filteredLines = [];

  /**
   * Level badge color mapping
   */
  const levelColors = {
    'EMERGENCY': { bg: 'bg-red-900', text: 'text-red-50', border: 'border-red-800', dot: 'bg-red-600' },
    'ALERT': { bg: 'bg-red-700', text: 'text-red-50', border: 'border-red-600', dot: 'bg-red-500' },
    'CRITICAL': { bg: 'bg-red-600', text: 'text-red-50', border: 'border-red-500', dot: 'bg-red-400' },
    'ERROR': { bg: 'bg-red-100', text: 'text-red-700', border: 'border-red-300', dot: 'bg-red-500' },
    'WARNING': { bg: 'bg-amber-100', text: 'text-amber-700', border: 'border-amber-300', dot: 'bg-amber-500' },
    'NOTICE': { bg: 'bg-cyan-100', text: 'text-cyan-700', border: 'border-cyan-300', dot: 'bg-cyan-500' },
    'INFO': { bg: 'bg-blue-100', text: 'text-blue-700', border: 'border-blue-300', dot: 'bg-blue-500' },
    'DEBUG': { bg: 'bg-gray-100', text: 'text-gray-700', border: 'border-gray-300', dot: 'bg-gray-500' },
  };

  /**
   * Select a log file and load its content
   */
  async function selectLogFile(filename) {
    // Update active state in sidebar
    document.querySelectorAll('.log-file-item').forEach(el => {
      el.classList.remove('bg-blue-100', 'border-blue-400');
      el.classList.add('border-transparent');
    });
    document.querySelector(`[data-filename="${filename}"]`).classList.add('bg-blue-100', 'border-blue-400');

    currentFilename = filename;

    try {
      const response = await fetch(`<?= base_url('logs/view') ?>/${filename}`);
      const data = await response.json();

      if (!response.ok) {
        showError(data.error || 'Failed to load log file');
        return;
      }

      allLines = data.lines;
      filteredLines = allLines;

      // Update title and content
      document.getElementById('logTitle').textContent = data.filename;
      document.getElementById('logSubtitle').textContent = `Last modified: ${data.modified_time_formatted}`;
      document.getElementById('toolbar').classList.remove('hidden');
      document.getElementById('statusBar').classList.remove('hidden');
      document.getElementById('summaryBar').classList.remove('hidden');
      
      const downloadBtn = document.getElementById('downloadBtn');
      if (downloadBtn) downloadBtn.onclick = () => downloadLog();
      
      const deleteBtn = document.getElementById('deleteBtn');
      if (deleteBtn) deleteBtn.onclick = () => deleteLog();

      // Load notification status (super admin only)
      loadNotificationStatus();

      renderLogs();
      updateStats();
      document.getElementById('searchInput').value = '';
      document.getElementById('levelFilter').value = '';
    } catch (error) {
      showError('Network error: ' + error.message);
    }
  }

  /**
   * Filter logs based on search and level
   */
  function filterLogs() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const levelFilter = document.getElementById('levelFilter').value;

    filteredLines = allLines.filter(entry => {
      const matchesSearch = !searchTerm || 
        entry.timestamp.toLowerCase().includes(searchTerm) ||
        entry.message.toLowerCase().includes(searchTerm);
      
      const matchesLevel = !levelFilter || entry.level === levelFilter;

      return matchesSearch && matchesLevel;
    });

    renderLogs();
    updateStats();
  }

  /**
   * Render log lines in the content area
   */
  function renderLogs() {
    const logContent = document.getElementById('logContent');

    if (filteredLines.length === 0) {
      logContent.innerHTML = '<div class="text-center text-gray-400 py-12"><p class="text-sm">No matching logs found</p></div>';
      return;
    }

    const html = filteredLines.map((entry, idx) => {
      const colors = levelColors[entry.level] || levelColors['INFO'];
      const messageLines = entry.message.split('\n');
      const isErrorLevel = ['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR'].includes(entry.level);
      
      return `
        <div class="mb-3 bg-white rounded border overflow-hidden transition hover:shadow-md ${isErrorLevel ? 'border-l-4 border-l-red-500' : 'border border-blue-100/50'}" ${isErrorLevel ? 'data-error-line="true"' : ''}>
          <div class="px-4 py-3 flex gap-3 items-start bg-gray-50 border-b border-blue-100/30">
            <span class="text-gray-400 flex-shrink-0 font-semibold">${idx + 1}</span>
            <span class="text-gray-500 mono text-xs flex-shrink-0">${escapeHtml(entry.timestamp)}</span>
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded ${colors.bg} ${colors.text} font-semibold whitespace-nowrap flex-shrink-0">
              <span class="w-1.5 h-1.5 ${colors.dot} rounded-full"></span>
              ${escapeHtml(entry.level)}
            </span>
          </div>
          <div class="px-4 py-3 mono text-xs text-gray-800 whitespace-pre-wrap break-words">
            ${messageLines.map(msg => escapeHtml(msg)).join('<br>')}
          </div>
        </div>
      `;
    }).join('');

    logContent.innerHTML = html;

    // Auto-scroll to first error (if filters don't exclude errors)
    setTimeout(() => {
      const firstError = logContent.querySelector('[data-error-line="true"]');
      if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }, 100);
  }

  /**
   * Update the status bar statistics
   */
  function updateStats() {
    const errorCount = filteredLines.filter(e => ['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR'].includes(e.level)).length;
    const warningCount = filteredLines.filter(e => e.level === 'WARNING').length;
    const allErrorCount = allLines.filter(e => ['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR'].includes(e.level)).length;
    const allWarningCount = allLines.filter(e => e.level === 'WARNING').length;

    document.getElementById('statsErrors').textContent = errorCount;
    document.getElementById('statsWarnings').textContent = warningCount;
    document.getElementById('statsTotalLines').textContent = filteredLines.length;

    // Update summary bar
    document.getElementById('summaryErrors').textContent = allErrorCount;
    document.getElementById('summaryWarnings').textContent = allWarningCount;
    document.getElementById('summaryTotal').textContent = allLines.length;
    if (allLines.length > 0) {
      const lastLine = allLines[allLines.length - 1];
      document.getElementById('summaryLastActivity').textContent = lastLine.timestamp;
    }
    
    if (currentFilename) {
      const logFiles = <?= json_encode($logFiles) ?>;
      const file = logFiles.find(f => f.filename === currentFilename);
      if (file) {
        document.getElementById('statsFilename').textContent = `${file.display_name}`;
        document.getElementById('statsSize').textContent = `${file.file_size} KB`;
        document.getElementById('statsModified').textContent = new Date(file.modified_time * 1000).toLocaleString();
      }
    }
  }

  /**
   * Download the current log file
   */
  function downloadLog() {
    if (!currentFilename) return;
    window.location.href = `<?= base_url('logs/download') ?>/${currentFilename}`;
  }

  /**
   * Delete the current log file (super admin only)
   */
  async function deleteLog() {
    if (!currentFilename) return;

    if (!confirm(`Are you sure you want to delete ${currentFilename}? This action cannot be undone.`)) {
      return;
    }

    try {
      const response = await fetch(`<?= base_url('logs/delete') ?>/${currentFilename}`, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        },
      });

      const data = await response.json();

      if (!response.ok) {
        showError(data.error || 'Failed to delete log file');
        return;
      }

      alert('Log file deleted successfully');
      location.reload();
    } catch (error) {
      showError('Network error: ' + error.message);
    }
  }

  /**
   * Escape HTML special characters
   */
  function escapeHtml(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
  }

  /**
   * Show error message
   */
  function showError(message) {
    const logContent = document.getElementById('logContent');
    logContent.innerHTML = `<div class="text-center text-red-500 py-12"><p class="text-sm">${escapeHtml(message)}</p></div>`;
    document.getElementById('toolbar').classList.add('hidden');
    document.getElementById('statusBar').classList.add('hidden');
  }

  /**
   * Load notification system status (Super Admin only)
   */
  async function loadNotificationStatus() {
    const statusEl = document.getElementById('notificationStatus');
    if (!statusEl) return;

    try {
      const response = await fetch(`<?= base_url('logs/settings') ?>`);
      if (response.ok) {
        const data = await response.json();
        if (data.enabled) {
          statusEl.textContent = `Notifications: ON (${data.channels.join(', ')})`;
          statusEl.classList.remove('bg-gray-100', 'text-gray-700');
          statusEl.classList.add('bg-green-100', 'text-green-700');
        } else {
          statusEl.textContent = 'Notifications: OFF';
          statusEl.classList.remove('bg-gray-100', 'text-gray-700');
          statusEl.classList.add('bg-red-100', 'text-red-700');
        }
      }
    } catch (error) {
      console.error('Failed to load notification status:', error);
    }
  }

  /**
   * Send a test notification (Super Admin only)
   */
  async function sendTestNotification() {
    const btn = document.getElementById('testNotifyBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Sending...';

    try {
      // Get CSRF token from meta tag or hidden input
      let csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
      if (!csrfToken) {
        const csrfInput = document.querySelector('input[name="csrf_test_name"]');
        csrfToken = csrfInput?.value || '';
      }

      const response = await fetch(`<?= base_url('logs/test-notify') ?>`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrfToken,
        },
      });

      if (response.ok) {
        const data = await response.json();
        console.log('Test notification response:', data);
        if (data.success) {
          showToast('✓ Test alert sent successfully!', 'success');
        } else {
          showToast('⚠ Test alert sent but with some issues. Check logs.', 'warning');
        }
      } else {
        showToast('✗ Failed to send test alert', 'error');
      }
    } catch (error) {
      showToast('✗ Error: ' + error.message, 'error');
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalText;
    }
  }

  /**
   * Show a toast notification
   */
  function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 right-6 px-6 py-3 rounded-lg text-white text-sm font-semibold shadow-lg z-50 ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-amber-600'}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.animation = 'fadeOut 0.3s ease-out';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  // Auto-select the first log file on page load
  document.addEventListener('DOMContentLoaded', () => {
    const firstFile = document.querySelector('.log-file-item');
    if (firstFile) {
      firstFile.click();
    }
  });
</script>

<style>
  #logContent {
    font-family: 'JetBrains Mono', monospace;
  }
  
  .mono {
    font-family: 'JetBrains Mono', monospace;
  }

  /* Custom scrollbar for log content */
  #logContent::-webkit-scrollbar {
    width: 8px;
  }

  #logContent::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 10px;
  }

  #logContent::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }

  #logContent::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  /* Toast fadeout animation */
  @keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(10px); }
  }
</style>
<?= $this->endSection() ?>
