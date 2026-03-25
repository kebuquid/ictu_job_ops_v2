<?php
// Detect route prefix from URI; fall back to session role
$_p = 'super-admin';
if (str_starts_with(uri_string(), 'admin/')) {
    $_p = 'admin';
} elseif (!str_starts_with(uri_string(), 'super-admin/')) {
    $sess = session()->get('user');
    if (isset($sess['role_id']) && (int)$sess['role_id'] === 2) {
        $_p = 'admin';
    }
}
$_isSA = ($_p === 'super-admin');
$_currentUri = uri_string();

$navPermissions = [
  'dashboard'          => true,
  'tickets'            => true,
  'my_tickets'         => !$_isSA,
  'employees'          => true,
  'section_access'     => $_isSA,
  'form_option_access' => $_isSA,
  'verify'             => !$_isSA,
  'keyword_rules'      => true,
  'ticket_sla_rules'   => true,
  'sections'           => $_isSA,
  'site_components'    => $_isSA,
  'assets'             => true,
  'asset_groups'       => true,
  'maintenance'        => true,
  'pm_plans'           => $_isSA,
  'disposals'          => true,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($pageTitle ?? 'Asset Management') ?> — ICTU</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }
    @keyframes blob {
      0%,100% { transform: translate(0,0) scale(1); }
      33%  { transform: translate(40px,-60px) scale(1.15); }
      66%  { transform: translate(-30px,30px) scale(0.9); }
    }
    .animate-blob { animation: blob 8s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
    .sidebar-item:hover .sidebar-icon { transform: translateX(3px); }
    .sidebar-icon { transition: transform 0.2s ease; }
    .sidebar-active {
      background: linear-gradient(135deg, #1e40af, #4338ca);
      color: white;
      box-shadow: 0 4px 15px rgba(30,64,175,0.35);
    }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen overflow-hidden">

  <!-- Background blobs -->
  <div class="fixed top-0 right-0 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob pointer-events-none"></div>
  <div class="fixed bottom-0 left-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 pointer-events-none"></div>
  <div class="fixed top-1/2 left-1/3 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000 pointer-events-none"></div>

  <div class="flex h-screen overflow-hidden relative z-10">

    <div id="assetSidebarOverlay" class="fixed inset-0 bg-slate-900/45 backdrop-blur-[1px] z-30 hidden lg:hidden" aria-hidden="true"></div>

    <!-- ─── SIDEBAR ─── -->
    <aside id="assetSidebar" class="fixed inset-y-0 left-0 z-40 w-72 max-w-[86vw] bg-white/90 backdrop-blur-xl shadow-2xl flex flex-col border-r border-blue-100/50 shrink-0 -translate-x-full transition-transform duration-300 ease-out lg:relative lg:z-auto lg:max-w-none lg:translate-x-0">

      <!-- Logo -->
      <div class="p-6 border-b border-blue-100/60">
        <div class="flex items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <div>
              <p class="text-xs text-gray-400 font-semibold tracking-wider uppercase">CSPC – ICTU</p>
              <p class="text-sm font-extrabold text-gray-900 leading-tight">Job Ticketing</p>
            </div>
          </div>
          <button id="assetSidebarClose" type="button" class="inline-flex lg:hidden items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition" aria-label="Close navigation menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      </div>

      <?= view_cell('UserCell::displayInfo') ?>

      <!-- Nav -->
      <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <p class="text-xs font-700 text-gray-400 uppercase tracking-widest px-3 pb-1 pt-2">Main</p>

        <?php if ($navPermissions['dashboard']): ?>
        <a href="<?= base_url("$_p/dashboard") ?>"
          class="sidebar-item <?= $_currentUri === "$_p/dashboard" ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Dashboard
        </a>
        <?php endif; ?>

        <?php if ($navPermissions['tickets']): ?>
        <a href="<?= base_url("$_p/tickets") ?>"
          class="sidebar-item <?= str_starts_with($_currentUri, "$_p/tickets") || str_starts_with($_currentUri, "$_p/ticket/") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
          <?= $_isSA ? 'All Tickets' : 'Section Tickets' ?>
        </a>
        <?php endif; ?>

        <?php if ($navPermissions['my_tickets']): ?>
        <a href="<?= base_url('admin/my-tickets') ?>"
          class="sidebar-item <?= str_starts_with($_currentUri, 'admin/my-tickets') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
          My Tickets
        </a>
        <?php endif; ?>

        <p class="text-xs font-700 text-gray-400 uppercase tracking-widest px-3 pb-1 pt-4">Management</p>

          <?php if ($navPermissions['employees']): ?>
        <a href="<?= base_url("$_p/employees") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/employees") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          <?= $_isSA ? 'Employees' : 'Section Employees' ?>
        </a>
          <?php endif; ?>

          <?php if ($navPermissions['section_access']): ?>
        <a href="<?= base_url('super-admin/section-access') ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, 'super-admin/section-access') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          Section Access
        </a>
          <?php endif; ?>

          <?php if ($navPermissions['form_option_access']): ?>
          <a href="<?= base_url('super-admin/form-option-access') ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, 'super-admin/form-option-access') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
           <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
           Form Option Access
          </a>
          <?php endif; ?>

          <?php if ($navPermissions['verify']): ?>
        <a href="<?= base_url('admin/verify') ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, 'admin/verify') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Verify Responses
        </a>
        <?php endif; ?>

          <?php if ($navPermissions['keyword_rules']): ?>
        <a href="<?= base_url("$_p/keyword-rules") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/keyword-rules") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
          Keyword Rules
        </a>
          <?php endif; ?>

          <?php if ($navPermissions['ticket_sla_rules']): ?>
          <a href="<?= base_url("$_p/ticket-sla-rules") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/ticket-sla-rules") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
           <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
           Ticket Timeframes
          </a>
          <?php endif; ?>

          <?php if ($navPermissions['sections']): ?>
          <a href="<?= base_url('super-admin/sections') ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, 'super-admin/sections') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
           <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
           Sections
          </a>
          <?php endif; ?>

          <?php if ($navPermissions['site_components']): ?>
        <!-- Site Components Dropdown -->
        <?php
          $componentRoutes = [
            'super-admin/buildings','super-admin/expertise','super-admin/issue-types',
            'super-admin/organizational-units','super-admin/priority-levels',
            'super-admin/request-actions','super-admin/request-platforms',
            'super-admin/request-types','super-admin/ticket-equipment'
          ];
          $isComponentPage = false;
          foreach ($componentRoutes as $cr) {
            if (uri_string() === $cr || str_starts_with(uri_string(), $cr . '/')) { $isComponentPage = true; break; }
          }
        ?>
        <div>
          <button onclick="document.getElementById('site-components-menu').classList.toggle('hidden'); document.getElementById('site-components-chevron').classList.toggle('rotate-180');"
                  class="sidebar-item flex items-center justify-between w-full px-4 py-3 rounded-xl text-gray-600 hover:text-blue-700 hover:bg-blue-50 font-medium text-sm transition-all">
            <span class="flex items-center gap-3">
              <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
              Site Components
            </span>
            <svg id="site-components-chevron" class="w-4 h-4 transition-transform <?= $isComponentPage ? 'rotate-180' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div id="site-components-menu" class="ml-6 mt-1 space-y-0.5 border-l-2 border-blue-100 pl-3 <?= $isComponentPage ? '' : 'hidden' ?>">
            <a href="<?= base_url('super-admin/buildings') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Buildings</a>
            <a href="<?= base_url('super-admin/expertise') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Expertise</a>
            <a href="<?= base_url('super-admin/issue-types') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Issue Types</a>
            <a href="<?= base_url('super-admin/organizational-units') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Organizational Units</a>
            <a href="<?= base_url('super-admin/priority-levels') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Priority Levels</a>
            <a href="<?= base_url('super-admin/request-actions') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Request Actions</a>
            <a href="<?= base_url('super-admin/request-platforms') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Request Platforms</a>
            <a href="<?= base_url('super-admin/request-types') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Request Types</a>
            <a href="<?= base_url('super-admin/ticket-equipment') ?>" class="block px-3 py-2 rounded-lg text-gray-500 hover:text-blue-700 hover:bg-blue-50 text-sm transition-all">Ticket Equipment</a>
          </div>
        </div>
        <?php endif; ?>

        <p class="text-xs font-700 text-gray-400 uppercase tracking-widest px-3 pb-1 pt-4">Asset Management</p>

          <?php if ($navPermissions['assets']): ?>
        <a href="<?= base_url("$_p/assets") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/assets") && !str_starts_with($_currentUri, "$_p/asset-groups") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          Assets
        </a>
          <?php endif; ?>

          <?php if ($navPermissions['asset_groups']): ?>
        <a href="<?= base_url("$_p/asset-groups") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/asset-groups") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          Asset Groups
        </a>
          <?php endif; ?>

          <?php if ($navPermissions['maintenance']): ?>
        <a href="<?= base_url("$_p/maintenance") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/maintenance") && !str_starts_with($_currentUri, "$_p/pm-plans") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Maintenance
        </a>
          <?php endif; ?>

          <?php if ($navPermissions['pm_plans']): ?>
          <a href="<?= base_url("$_p/pm-plans") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/pm-plans") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
          PM Plans
        </a>
        <?php endif; ?>

          <?php if ($navPermissions['disposals']): ?>
        <a href="<?= base_url("$_p/disposals") ?>"
            class="sidebar-item <?= str_starts_with($_currentUri, "$_p/disposals") ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Disposals
        </a>
          <?php endif; ?>

      <!-- Bottom logout -->
      <div class="p-4 border-t border-blue-100/60">
        <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-medium text-sm transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Sign Out
        </a>
      </div>
      </nav>
    </aside>

    <!-- ─── MAIN CONTENT ─── -->
    <main class="flex-1 overflow-y-auto min-w-0">

      <!-- Top bar -->
      <header class="bg-white/70 backdrop-blur-xl border-b border-blue-100/50 px-4 sm:px-6 lg:px-8 py-4 flex items-center gap-3 sticky top-0 z-20">
        <button id="assetSidebarToggle" type="button" class="inline-flex lg:hidden items-center justify-center w-10 h-10 rounded-xl border border-blue-200 bg-white text-blue-700 hover:bg-blue-50 transition" aria-controls="assetSidebar" aria-expanded="false" aria-label="Open navigation menu">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex-1">
          <h1 class="text-lg sm:text-xl font-extrabold text-gray-900">
            <?= esc($pageTitle ?? 'Asset Management') ?>
            <span class="block sm:inline text-gray-400 font-normal text-sm sm:text-base">/ <?= esc($pageSubtitle ?? '') ?></span>
          </h1>
          <p class="text-xs text-gray-400 mono"><?= date('l, F j, Y') ?></p>
        </div>
      </header>

      <div class="p-4 sm:p-6">

        <!-- Flash messages -->
        <?php if (session()->has('success')): ?>
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm" id="flash-success">
          <i class="fa-solid fa-circle-check text-green-500"></i>
          <?= session('success') ?>
          <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-400 hover:text-green-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <?php endif; ?>
        <?php if (session()->has('error')): ?>
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm" id="flash-error">
          <i class="fa-solid fa-circle-exclamation text-red-500"></i>
          <?= session('error') ?>
          <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <?php endif; ?>

        <?= $pageContent ?>

      </div>

      <div class="text-center pb-4">
        <p class="text-gray-400 text-xs">CSPC – ICTU Job Ticketing System &nbsp;•&nbsp; Version 2.0 – JobIgniter</p>
      </div>
    </main>

  </div>

  <script>
  (() => {
    const sidebar = document.getElementById('assetSidebar');
    const overlay = document.getElementById('assetSidebarOverlay');
    const openBtn = document.getElementById('assetSidebarToggle');
    const closeBtn = document.getElementById('assetSidebarClose');
    if (!sidebar || !overlay || !openBtn || !closeBtn) return;

    const mq = window.matchMedia('(min-width: 1024px)');

    const setOpen = (isOpen) => {
      if (mq.matches) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        openBtn.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
        return;
      }
      sidebar.classList.toggle('-translate-x-full', !isOpen);
      overlay.classList.toggle('hidden', !isOpen);
      openBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      document.body.classList.toggle('overflow-hidden', isOpen);
    };

    openBtn.addEventListener('click', () => setOpen(true));
    closeBtn.addEventListener('click', () => setOpen(false));
    overlay.addEventListener('click', () => setOpen(false));

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') setOpen(false);
    });

    mq.addEventListener('change', () => setOpen(false));
    setOpen(false);
  })();
  </script>
</body>
</html>