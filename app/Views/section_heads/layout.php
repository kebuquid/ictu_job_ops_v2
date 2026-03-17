<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ICTU Job Ticketing - Section Head</title>
  <link rel="icon" href="<?= base_url('ictu.ico') ?>" type="image/x-icon">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.0/js/dataTables.tailwindcss.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.tailwindcss.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .mono { font-family: 'JetBrains Mono', monospace; }
    @keyframes blob { 0%, 100% { transform: translate(0, 0) scale(1); } 33% { transform: translate(40px, -60px) scale(1.15); } 66% { transform: translate(-30px, 30px) scale(0.9); } }
    .animate-blob { animation: blob 8s infinite; will-change: transform; }
    @media (prefers-reduced-motion: reduce) { .animate-blob { animation: none; } }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in { animation: fadeInUp 0.5s ease forwards; }
    .delay-1 { animation-delay: 0.1s; opacity: 0; }
    .delay-2 { animation-delay: 0.2s; opacity: 0; }
    .delay-3 { animation-delay: 0.3s; opacity: 0; }
    .delay-4 { animation-delay: 0.4s; opacity: 0; }
    .delay-5 { animation-delay: 0.5s; opacity: 0; }
    @keyframes pulse-ring { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(1.6); opacity: 0; } }
    .pulse-dot::before { content: ''; position: absolute; inset: -3px; border-radius: 50%; background: currentColor; animation: pulse-ring 1.5s ease-out infinite; }
    .sidebar-item:hover .sidebar-icon { transform: translateX(3px); }
    .sidebar-icon { transition: transform 0.2s ease; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .ticket-row:hover { background: #f0f7ff; }
    .ticket-row { transition: background 0.15s; }
    .sidebar-active { background: linear-gradient(135deg, #1e40af, #4338ca); color: white; box-shadow: 0 4px 15px rgba(30, 64, 175, 0.35); }
    @keyframes countUp { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
    .count-anim { animation: countUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 min-h-screen overflow-hidden">

  <div class="fixed top-0 right-0 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob pointer-events-none"></div>
  <div class="fixed bottom-0 left-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 pointer-events-none"></div>
  <div class="fixed top-1/2 left-1/3 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000 pointer-events-none"></div>

  <div class="flex h-screen overflow-hidden relative z-10">

    <!-- Sidebar -->
    <aside class="w-72 bg-white/80 backdrop-blur-xl shadow-2xl flex flex-col border-r border-blue-100/50 shrink-0">
      <div class="p-6 border-b border-blue-100/60">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
          <div>
            <p class="text-xs text-gray-400 font-semibold tracking-wider uppercase">CSPC – ICTU</p>
            <p class="text-sm font-800 font-extrabold text-gray-900 leading-tight">Job Ticketing</p>
          </div>
        </div>
      </div>

      <?= view_cell('UserCell::displayInfo') ?>

      <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <p class="text-xs font-700 text-gray-400 uppercase tracking-widest px-3 pb-1 pt-2">Main</p>

        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-item <?= uri_string() === 'admin/dashboard' ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Dashboard
        </a>

        <a href="<?= base_url('admin/tickets') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'admin/tickets') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
          Section Tickets
        </a>

        <a href="<?= base_url('admin/my-tickets') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'admin/my-tickets') || str_starts_with(uri_string(), 'admin/respond') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
          My Tickets
        </a>

        <p class="text-xs font-700 text-gray-400 uppercase tracking-widest px-3 pb-1 pt-4">Management</p>

        <a href="<?= base_url('admin/employees') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'admin/employees') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          Section Employees
        </a>

        <a href="<?= base_url('admin/verify') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'admin/verify') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Verify Responses
        </a>

        <a href="<?= base_url('admin/keyword-rules') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'admin/keyword-rules') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
          Keyword Rules
        </a>

        <a href="<?= base_url('admin/ticket-sla-rules') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'admin/ticket-sla-rules') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Ticket Timeframes
        </a>

        <p class="text-xs font-700 text-gray-400 uppercase tracking-widest px-3 pb-1 pt-4">Asset Management</p>

        <a href="<?= base_url('admin/assets') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'super-admin/assets') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          Assets
        </a>

        <a href="<?= base_url('admin/asset-groups') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'super-admin/asset-groups') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          Asset Groups
        </a>

        <a href="<?= base_url('admin/maintenance') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'super-admin/maintenance') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Maintenance
        </a>

        <a href="<?= base_url('admin/disposals') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'super-admin/disposals') ? 'sidebar-active' : 'text-gray-600 hover:text-blue-700 hover:bg-blue-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Disposals
        </a>

      <div class="p-4 border-t border-blue-100/60">
        <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-medium text-sm transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Sign Out
        </a>
      </div>
      
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
      <header class="bg-white/70 backdrop-blur-xl border-b border-blue-100/50 px-8 py-4 flex items-center gap-4 sticky top-0 z-20">
        <div class="flex-1">
          <h1 class="text-xl font-extrabold text-gray-900"><?= $this->renderSection('pageTitle') ?: 'Dashboard' ?> <span class="text-gray-400 font-normal text-base">/ <?= $this->renderSection('pageSubtitle') ?: 'Overview' ?></span></h1>
          <p class="text-xs text-gray-400 mono"><?= date('l, F j, Y') ?></p>
        </div>
      </header>

      <?= $this->renderSection('content') ?>

      <div class="text-center pb-2">
        <p class="text-gray-400 text-xs">CSPC – ICTU Job Ticketing System &nbsp;•&nbsp; Version 2.0 – JobIgniter</p>
      </div>
    </main>
  </div>

  <div class="fixed bottom-0 left-0 w-full pointer-events-none opacity-20 z-0">
    <svg viewBox="0 0 1440 120" class="w-full h-auto">
      <path d="M0,64 C240,96 480,32 720,64 C960,96 1200,32 1440,64 L1440,120 L0,120 Z" fill="#0ea5e9"/>
    </svg>
  </div>
</body>
</html>
