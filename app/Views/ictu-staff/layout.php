<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ICTU Job Ticketing - ICTU Staff</title>
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
    .sidebar-active { background: linear-gradient(135deg, #059669, #047857); color: white; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35); }
    @keyframes countUp { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
    .count-anim { animation: countUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
  </style>
</head>
<body class="bg-gradient-to-br from-emerald-50 via-white to-green-100 min-h-screen overflow-hidden">

  <div class="fixed top-0 right-0 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob pointer-events-none"></div>
  <div class="fixed bottom-0 left-0 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000 pointer-events-none"></div>
  <div class="fixed top-1/2 left-1/3 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000 pointer-events-none"></div>

  <div class="flex h-screen overflow-hidden relative z-10">

    <aside class="w-72 bg-white/80 backdrop-blur-xl shadow-2xl flex flex-col border-r border-emerald-100/50 shrink-0">
      <div class="p-6 border-b border-emerald-100/60">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-emerald-600 to-green-700 rounded-xl flex items-center justify-center shadow-lg">
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

        <a href="<?= base_url('ictu-staff/dashboard') ?>" class="sidebar-item <?= uri_string() === 'ictu-staff/dashboard' ? 'sidebar-active' : 'text-gray-600 hover:text-emerald-700 hover:bg-emerald-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Dashboard
        </a>

        <a href="<?= base_url('ictu-staff/my-tickets') ?>" class="sidebar-item <?= str_starts_with(uri_string(), 'ictu-staff/my-tickets') ? 'sidebar-active' : 'text-gray-600 hover:text-emerald-700 hover:bg-emerald-50' ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all">
          <svg class="sidebar-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
          My Assigned Tickets
        </a>
      </nav>

      <div class="p-4 border-t border-emerald-100/60">
        <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-medium text-sm transition-all">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Sign Out
        </a>
      </div>
    </aside>

    <main class="flex-1 overflow-y-auto">
      <header class="bg-white/70 backdrop-blur-xl border-b border-emerald-100/50 px-8 py-4 flex items-center gap-4 sticky top-0 z-20">
        <div class="flex-1">
          <h1 class="text-xl font-extrabold text-gray-900"><?= $this->renderSection('pageTitle') ?: 'Dashboard' ?> <span class="text-gray-400 font-normal text-base">/ <?= $this->renderSection('pageSubtitle') ?: 'My Overview' ?></span></h1>
          <p class="text-xs text-gray-400 mono"><?= date('l, F j, Y') ?></p>
        </div>
        <button class="relative w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center hover:bg-emerald-100 transition-colors">
          <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        <?= view_cell('UserCell::displayAvatar') ?>
      </header>

      <?= $this->renderSection('content') ?>

      <div class="text-center pb-2">
        <p class="text-gray-400 text-xs">CSPC – ICTU Job Ticketing System &nbsp;•&nbsp; Version 2.0 – JobIgniter</p>
      </div>
    </main>
  </div>

  <div class="fixed bottom-0 left-0 w-full pointer-events-none opacity-20 z-0">
    <svg viewBox="0 0 1440 120" class="w-full h-auto">
      <path d="M0,64 C240,96 480,32 720,64 C960,96 1200,32 1440,64 L1440,120 L0,120 Z" fill="#10b981"/>
    </svg>
  </div>
</body>
</html>
