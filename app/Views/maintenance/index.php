<?php
$pageTitle    = 'Preventive Maintenance Records';
$pageSubtitle = 'Track repairs and servicing for assets';
$routePrefix  = str_starts_with(uri_string(), 'admin/') ? 'admin' : 'super-admin';

ob_start();
?>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-screwdriver-wrench text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['total'] ?></p>
            <p class="text-xs text-gray-500">Total Records</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-calendar-check text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['this_month'] ?></p>
            <p class="text-xs text-gray-500">This Month</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-boxes-stacked text-purple-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['groups'] ?></p>
            <p class="text-xs text-gray-500">Groups Serviced</p>
        </div>
    </div>
</div>

<!-- PM Schedule: Buildings + Org Units merged -->
<?php if (!empty($pmBuildingStats)): ?>
<?php
    $bldColors = ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#f97316','#84cc16','#ec4899','#6366f1'];
?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6" id="pm-plan-card">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800 text-base flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-blue-500"></i>
            PM Schedule
            <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium"><?= esc($currentMonth) ?> <?= $currentYear ?></span>
        </h2>
        <button id="bld-clear-btn" onclick="clearBuildingFilter()" class="hidden text-xs text-gray-400 hover:text-red-500 flex items-center gap-1 transition">
            <i class="fa-solid fa-xmark"></i> Clear filter
        </button>
    </div>

    <!-- Two-column body -->
    <div class="flex divide-x divide-gray-100" style="min-height:180px">

        <!-- LEFT: Building list -->
        <div class="w-64 flex-shrink-0 p-4 flex flex-col gap-2 overflow-y-auto" style="max-height:340px">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1 px-1">Buildings</p>
            <?php $bldIdx = 0; foreach ($pmBuildingStats as $bldName => $bldInfo):
                $color     = $bldColors[$bldIdx % count($bldColors)];
                $scheduled = $bldInfo['scheduled'];
                $done      = $bldInfo['done'];
                $pct       = $scheduled > 0 ? round($done / $scheduled * 100) : 0;
                $bldIdx++;
            ?>
            <button
                onclick="filterByBuilding(<?= esc(json_encode($bldName)) ?>)"
                data-bld-card="<?= esc($bldName) ?>"
                class="flex items-center gap-3 w-full bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-3 transition text-left"
            >
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:<?= $color ?>22; color:<?= $color ?>">
                    <i class="fa-solid fa-building text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-xs leading-tight truncate"><?= esc($bldName) ?></p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <div class="flex-1 h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 flex-shrink-0"><?= $done ?>/<?= $scheduled ?></span>
                    </div>
                </div>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- RIGHT: Org unit cards for selected building -->
        <div class="flex-1 p-4">
            <div id="ou-placeholder" class="h-full flex flex-col items-center justify-center text-gray-300 gap-2">
                <i class="fa-solid fa-hand-pointer text-3xl"></i>
                <p class="text-xs">Select a building to see org units</p>
            </div>
            <div id="ou-inner" class="hidden">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-2 px-1">
                    Org Units — <span id="ou-bld-label" class="text-blue-500 normal-case tracking-normal font-medium"></span>
                </p>
                <div id="ou-cards" class="flex flex-wrap gap-3"></div>
                <p class="text-[10px] text-gray-400 mt-3 px-1">Click an org unit to view its maintenance records</p>
            </div>
        </div>
    </div>

    <!-- Active filter banner -->
    <div id="bld-filter-banner" class="hidden px-6 py-2 bg-blue-50 border-t border-blue-100 text-xs text-blue-700 flex items-center gap-2">
        <i class="fa-solid fa-filter"></i>
        Showing records for: <strong id="bld-filter-label"></strong>
    </div>
</div>
<?php endif; ?>

<!-- Table Card -->
<div id="maint-log-card" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-base flex items-center gap-2">
            <button id="log-back-btn" onclick="goBackToOuPanel()" class="hidden mr-1 p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Back to PM Schedule">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <i class="fa-solid fa-wrench text-blue-500"></i>
            Maintenance Log
            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full"><?= $stats['total'] ?></span>
        </h2>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <form method="get" action="<?= site_url($routePrefix . '/maintenance') ?>" class="flex items-center gap-1 w-full sm:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q"
                           class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-52"
                           placeholder="Search…"
                           value="<?= esc($keyword ?? '') ?>">
                </div>
                <?php if (!empty($keyword)): ?>
                    <a href="<?= site_url($routePrefix . '/maintenance') ?>?show_log=1" class="p-2 text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
            <a href="<?= site_url($routePrefix . '/maintenance/create') ?>"
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-plus text-xs"></i>
                Add Record
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1080px] text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Asset</th>
                    <th class="px-4 py-3 text-left">Asset Group</th>
                    <th class="px-4 py-3 text-left">Activities</th>
                    <th class="px-4 py-3 text-left">Conducted By</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="maint-log-tbody" class="divide-y divide-gray-50">
            <?php if (empty($records)): ?>
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-wrench text-4xl mb-3 block text-gray-200"></i>
                        No maintenance records found.
                        <a href="<?= site_url($routePrefix . '/maintenance/create') ?>" class="text-blue-500 hover:underline ml-1">Add one now</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($records as $i => $r): ?>
                <tr class="hover:bg-gray-50 transition group" data-building="<?= esc($r['building_name'] ?? '—') ?>" data-unit="<?= esc($r['unit_name'] ?? '—') ?>">
                    <?php $rowNum = ($pager ? ($pager->getCurrentPage() - 1) * $perPage : 0) + $i + 1; ?>
                    <td class="px-4 py-3 text-gray-400 text-xs row-num-cell" data-orig-num="<?= $rowNum ?>"><?= $rowNum ?></td>
                    <td class="px-4 py-3">
                        <?php if (!empty($r['asset_tag'])): ?>
                                     <a href="<?= site_url($routePrefix . "/assets/show/{$r['asset_id']}") ?>"
                               class="font-semibold text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-md hover:bg-gray-200">
                                <?= esc($r['asset_tag']) ?>
                            </a>
                            <?php if (!empty($r['brand_model'])): ?>
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[10rem]" title="<?= esc($r['brand_model']) ?>"><?= esc($r['brand_model']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($r['serial_number'])): ?>
                                <p class="text-xs text-gray-400 mt-0.5">S/N: <?= esc($r['serial_number']) ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-xs text-gray-400">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                                <a href="<?= site_url($routePrefix . "/asset-groups/show/{$r['group_id']}") ?>"
                           class="font-semibold text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-md hover:bg-blue-100">
                            <?= esc($r['group_name'] ?? '—') ?>
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5"><?= esc($r['group_code'] ?? '') ?></p>
                    </td>
                    <td class="px-4 py-3 text-gray-700 max-w-xs">
                        <p class="truncate" title="<?= esc($r['activities'] ?? '') ?>">
                            <?= esc($r['activities'] ?? '—') ?>
                        </p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?= esc($r['conducted_by'] ?? '—') ?>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-nowrap">
                        <?= $r['maintenance_date'] ? date('M d, Y', strtotime($r['maintenance_date'])) : '—' ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php
                            $mDate     = !empty($r['maintenance_date']) ? strtotime($r['maintenance_date']) : null;
                            $mMonth    = $mDate ? (int)date('n', $mDate) : 0;
                            $mYear     = $mDate ? (int)date('Y', $mDate) : 0;
                            $nowMonth  = (int)date('n');
                            $nowYear   = (int)date('Y');
                            $monthsAgo = ($nowYear - $mYear) * 12 + ($nowMonth - $mMonth);
                            if (!$mDate): ?>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-gray-100 text-gray-400 border border-gray-200 px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-minus text-[10px]"></i> No Date
                            </span>
                            <?php elseif ($monthsAgo === 0): ?>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Done
                            </span>
                            <?php elseif ($monthsAgo === 1): ?>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-blue-50 text-blue-600 border border-blue-200 px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-clock text-[10px]"></i> Recent
                            </span>
                            <?php elseif ($monthsAgo <= 3): ?>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Aging
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium bg-red-50 text-red-500 border border-red-200 px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-circle-xmark text-[10px]"></i> Overdue
                            </span>
                            <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1 opacity-80 group-hover:opacity-100 transition">
                                     <a href="<?= site_url($routePrefix . "/maintenance/show/{$r['maintenance_id']}") ?>"
                               class="p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition" title="View">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                                     <a href="<?= site_url($routePrefix . "/maintenance/print/{$r['maintenance_id']}") ?>" target="_blank"
                               class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 transition" title="Print Summary">
                                <i class="fa-solid fa-print text-xs"></i>
                            </a>
                                     <a href="<?= site_url($routePrefix . "/maintenance/edit/{$r['maintenance_id']}") ?>"
                               class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 transition" title="Edit">
                                <i class="fa-solid fa-pencil text-xs"></i>
                            </a>
                            <?php
                                $_delUrl = site_url($routePrefix . "/maintenance/delete/{$r['maintenance_id']}") . '?show_log=1';
                                if (!empty($_GET['bld']))  $_delUrl .= '&bld='  . urlencode($_GET['bld']);
                                if (!empty($_GET['unit'])) $_delUrl .= '&unit=' . urlencode($_GET['unit']);
                            ?>
                            <a href="<?= $_delUrl ?>"
                               class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Delete"
                               onclick="return confirm('Delete this maintenance record?')">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-xs text-gray-400 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <span>Showing <?= count($records) ?> of <?= $stats['total'] ?> records</span>
        <span>Updated: <?= date('M d, Y h:i A') ?></span>
    </div>

    <?php if (isset($pager)): ?>
    <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <span class="text-xs text-gray-400">
            <?= ($pager->getCurrentPage() - 1) * $perPage + 1 ?>
            &ndash;
            <?= min($pager->getCurrentPage() * $perPage, $bldTotal) ?>
            of <?= $bldTotal ?> records
        </span>
        <div id="pager-links" class="flex items-center gap-1 text-sm">
            <?php
            $currentPage  = $pager->getCurrentPage();
            $totalPages   = $pager->getPageCount();
            $_bld         = $_GET['bld']  ?? '';
            $_unit        = $_GET['unit'] ?? '';
            $baseUrl      = site_url($routePrefix . '/maintenance') . '?';
            if (!empty($keyword)) $baseUrl .= 'q='    . urlencode($keyword) . '&';
            if (!empty($_bld))    $baseUrl .= 'bld='  . urlencode($_bld)    . '&';
            if (!empty($_unit))   $baseUrl .= 'unit=' . urlencode($_unit)   . '&';
            $sep          = '';
            ?>
            <!-- Prev -->
            <?php if ($currentPage > 1): ?>
                <a href="<?= $baseUrl ?>page=<?= $currentPage - 1 ?>"
                   class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
            <?php else: ?>
                <span class="px-3 py-1.5 rounded-lg border border-gray-100 text-gray-300 text-xs cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </span>
            <?php endif; ?>

            <!-- Page numbers -->
            <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): ?>
                <?php if ($p === $currentPage): ?>
                    <span class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold"><?= $p ?></span>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>page=<?= $p ?>"
                       class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs"><?= $p ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="<?= $baseUrl ?>page=<?= $currentPage + 1 ?>"
                   class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            <?php else: ?>
                <span class="px-3 py-1.5 rounded-lg border border-gray-100 text-gray-300 text-xs cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const _pmOuStats = <?= json_encode($pmOuStats ?? []) ?>;   // building → unit → {scheduled, done, assets[]}
const _orgLogs   = <?= json_encode($orgUnitStats ?? []) ?>; // building → unit → record count
const _basePageUrl = <?= json_encode(site_url($routePrefix . '/maintenance') . '?' . (!empty($keyword) ? 'q=' . urlencode($keyword) . '&' : '')) ?>;

let _activeBldg = null;
let _activeUnit = null;

<?php
$_hasFilter = !empty($keyword) || !empty($_GET['show_log']) || !empty($_GET['bld']);
?>
<?php if ($_hasFilter): ?>
document.getElementById('pm-plan-card').classList.add('hidden');
<?php else: ?>
document.getElementById('maint-log-card').classList.add('hidden');
<?php endif; ?>

function updatePaginationUrls(bld, unit) {
    document.querySelectorAll('#pager-links a').forEach(function(a) {
        const url  = new URL(a.href);
        const page = url.searchParams.get('page') || '1';
        let href   = _basePageUrl;
        if (bld)  href += 'bld='  + encodeURIComponent(bld)  + '&';
        if (unit) href += 'unit=' + encodeURIComponent(unit) + '&';
        href += 'page=' + page;
        a.href = href;
    });
}

function filterByBuilding(name) {
    if (_activeBldg === name && !_activeUnit) { clearBuildingFilter(); return; }
    _activeBldg = name;
    _activeUnit = null;

    // Remove any asset schedule panel
    const existingPanel = document.getElementById('asset-schedule-panel');
    if (existingPanel) existingPanel.remove();

    // Highlight active building card
    document.querySelectorAll('[data-bld-card]').forEach(function(btn) {
        btn.classList.toggle('ring-2',        btn.dataset.bldCard === name);
        btn.classList.toggle('ring-blue-400', btn.dataset.bldCard === name);
        btn.classList.toggle('bg-blue-50',    btn.dataset.bldCard === name);
        btn.classList.toggle('border-blue-300', btn.dataset.bldCard === name);
        btn.classList.toggle('opacity-50',    btn.dataset.bldCard !== name);
    });

    // Build org unit cards in the right column
    const units     = _pmOuStats[name] || {};
    const container = document.getElementById('ou-cards');
    container.innerHTML = '';

    if (Object.keys(units).length === 0) {
        container.innerHTML = '<p class="text-xs text-gray-400 py-2">No PM schedule found for this building this month.</p>';
    } else {
        Object.entries(units).forEach(function([unit, info]) {
            const pct = info.scheduled > 0 ? Math.round(info.done / info.scheduled * 100) : 0;
            const assetLines = (info.assets || []).map(function(a) {
                return '<li class="flex items-center gap-1.5">'
                    + '<span class="' + (a.is_done ? 'text-green-500' : 'text-amber-400') + '">'
                    + '<i class="fa-solid ' + (a.is_done ? 'fa-circle-check' : 'fa-circle') + ' text-xs"></i></span>'
                    + '<span class="text-xs text-gray-600 truncate" title="' + a.description + '">' + a.description + '</span>'
                    + '</li>';
            }).join('');

            const card = document.createElement('button');
            card.className = 'flex flex-col bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-xl px-4 py-3 transition cursor-pointer text-left';
            card.style.minWidth = '160px';
            card.innerHTML =
                '<div class="flex items-center justify-between mb-1.5">'
                + '<span class="font-semibold text-sm text-gray-800">' + unit + '</span>'
                + '<span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full">' + info.done + '/' + info.scheduled + '</span>'
                + '</div>'
                + '<div class="w-full h-1 bg-gray-200 rounded-full overflow-hidden mb-2">'
                + '<div class="h-full bg-blue-500 rounded-full" style="width:' + pct + '%"></div></div>'
                + '<ul class="space-y-0.5">' + assetLines + '</ul>';
            card.onclick = function() { filterByUnit(name, unit); };
            container.appendChild(card);
        });
    }

    document.getElementById('ou-bld-label').textContent = name;
    document.getElementById('ou-placeholder').classList.add('hidden');
    document.getElementById('ou-inner').classList.remove('hidden');
    document.getElementById('bld-clear-btn').classList.remove('hidden');
    document.getElementById('bld-filter-banner').classList.add('hidden');
    document.getElementById('maint-log-card').classList.add('hidden');
    updatePaginationUrls(name, null);
}

function renumberRows() {
    let n = 1;
    document.querySelectorAll('#maint-log-tbody tr[data-building]').forEach(function(tr) {
        if (tr.style.display !== 'none') {
            const cell = tr.querySelector('.row-num-cell');
            if (cell) cell.textContent = n++;
        }
    });
}
function restoreRowNumbers() {
    document.querySelectorAll('#maint-log-tbody tr[data-building] .row-num-cell').forEach(function(cell) {
        cell.textContent = cell.dataset.origNum || '';
    });
}

function filterByUnit(building, unit) {
    _activeUnit = unit;

    // Remove old asset schedule panel if present
    const existingPanel = document.getElementById('asset-schedule-panel');
    if (existingPanel) existingPanel.remove();

    // Build scheduled-assets panel above the log
    const info = (_pmOuStats[building] || {})[unit] || { scheduled: 0, done: 0, assets: [] };
    if (info.assets && info.assets.length > 0) {
        const panel = document.createElement('div');
        panel.id = 'asset-schedule-panel';
        panel.className = 'bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden mb-4';

        const rows = info.assets.map(function(a) {
            const badge = a.is_done
                ? '<span class="inline-flex items-center gap-1 text-[10px] bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-full"><i class="fa-solid fa-check text-[9px]"></i> Done</span>'
                : '<span class="inline-flex items-center gap-1 text-[10px] bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full"><i class="fa-solid fa-clock text-[9px]"></i> Pending</span>';
            return '<tr class="border-t border-gray-50">'
                + '<td class="px-4 py-2 text-xs text-gray-700">' + a.description + '</td>'
                + '<td class="px-4 py-2 text-xs text-gray-400">' + (a.asset_tag || '—') + '</td>'
                + '<td class="px-4 py-2 text-xs text-gray-400 capitalize">' + (a.frequency || '—') + '</td>'
                + '<td class="px-4 py-2">' + badge + '</td>'
                + '</tr>';
        }).join('');

        panel.innerHTML =
            '<div class="px-5 py-3 border-b border-blue-50 flex items-center justify-between">'
            + '<h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">'
            + '<i class="fa-solid fa-list-check text-blue-500"></i> Scheduled Assets &mdash; <span class="text-blue-600">' + unit + '</span>'
            + '<span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full">' + info.done + '/' + info.scheduled + ' done</span>'
            + '</h3></div>'
            + '<div class="overflow-x-auto"><table class="w-full text-sm">'
            + '<thead><tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wide">'
            + '<th class="px-4 py-2 text-left">Activity</th>'
            + '<th class="px-4 py-2 text-left">Asset Tag</th>'
            + '<th class="px-4 py-2 text-left">Frequency</th>'
            + '<th class="px-4 py-2 text-left">Status</th>'
            + '</tr></thead><tbody>' + rows + '</tbody></table></div>';

        const logCard = document.getElementById('maint-log-card');
        logCard.parentNode.insertBefore(panel, logCard);
    }

    // Filter log rows
    document.querySelectorAll('tbody tr[data-building]').forEach(function(tr) {
        tr.style.display = (tr.dataset.building === building && tr.dataset.unit === unit) ? '' : 'none';
    });

    // Hide PM schedule card, show back button in log header
    document.getElementById('pm-plan-card').classList.add('hidden');
    document.getElementById('log-back-btn').classList.remove('hidden');

    document.getElementById('bld-filter-label').textContent = building + ' › ' + unit;
    document.getElementById('bld-filter-banner').classList.remove('hidden');
    document.getElementById('maint-log-card').classList.remove('hidden');
    document.getElementById('maint-log-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    renumberRows();
    updatePaginationUrls(building, unit);
}

function goBackToOuPanel() {
    // Remove scheduled assets panel
    const existingPanel = document.getElementById('asset-schedule-panel');
    if (existingPanel) existingPanel.remove();

    // Reset row visibility
    document.querySelectorAll('tbody tr[data-building]').forEach(function(tr) {
        tr.style.display = '';
    });

    // Hide log, restore PM schedule card
    document.getElementById('maint-log-card').classList.add('hidden');
    document.getElementById('log-back-btn').classList.add('hidden');
    document.getElementById('bld-filter-banner').classList.add('hidden');
    document.getElementById('pm-plan-card').classList.remove('hidden');
    document.getElementById('pm-plan-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    restoreRowNumbers();
    updatePaginationUrls(_activeBldg, null);
    // Update URL: keep bld, drop unit + page
    var _goBackUrl = _basePageUrl + (_activeBldg ? 'bld=' + encodeURIComponent(_activeBldg) : '');
    history.replaceState(null, '', _goBackUrl || location.pathname);

    _activeUnit = null;
}

function clearBuildingFilter() {
    _activeBldg = null;
    _activeUnit = null;

    const existingPanel = document.getElementById('asset-schedule-panel');
    if (existingPanel) existingPanel.remove();

    document.querySelectorAll('[data-bld-card]').forEach(function(btn) {
        btn.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50', 'border-blue-300', 'opacity-50');
    });
    document.querySelectorAll('tbody tr[data-building]').forEach(function(tr) {
        tr.style.display = '';
    });

    document.getElementById('ou-placeholder').classList.remove('hidden');
    document.getElementById('ou-inner').classList.add('hidden');
    document.getElementById('bld-filter-banner').classList.add('hidden');
    document.getElementById('bld-clear-btn').classList.add('hidden');
    document.getElementById('log-back-btn').classList.add('hidden');
    document.getElementById('maint-log-card').classList.add('hidden');
    document.getElementById('pm-plan-card').classList.remove('hidden');
    restoreRowNumbers();
    updatePaginationUrls(null, null);
    history.replaceState(null, '', location.pathname + (<?= json_encode(!empty($keyword) ? '?q=' . urlencode($keyword) : '') ?>));
}

// Restore drill-down state from URL params (pagination navigation)
(function() {
    const params = new URLSearchParams(window.location.search);
    const bld  = params.get('bld');
    const unit = params.get('unit');
    // Clean up show_log flag from URL without a reload
    if (params.has('show_log')) {
        params.delete('show_log');
        const clean = location.pathname + (params.toString() ? '?' + params.toString() : '');
        history.replaceState(null, '', clean);
    }
    if (!bld) return;
    // Restore building highlight + org unit panel (silent, no scroll)
    _activeBldg = bld;
    document.querySelectorAll('[data-bld-card]').forEach(function(btn) {
        btn.classList.toggle('ring-2',          btn.dataset.bldCard === bld);
        btn.classList.toggle('ring-blue-400',   btn.dataset.bldCard === bld);
        btn.classList.toggle('bg-blue-50',      btn.dataset.bldCard === bld);
        btn.classList.toggle('border-blue-300', btn.dataset.bldCard === bld);
        btn.classList.toggle('opacity-50',      btn.dataset.bldCard !== bld);
    });
    // Rebuild org unit cards
    const units     = _pmOuStats[bld] || {};
    const container = document.getElementById('ou-cards');
    container.innerHTML = '';
    Object.entries(units).forEach(function([u, info]) {
        const pct = info.scheduled > 0 ? Math.round(info.done / info.scheduled * 100) : 0;
        const card = document.createElement('button');
        card.className = 'flex flex-col bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-xl px-4 py-3 transition cursor-pointer text-left';
        card.style.minWidth = '160px';
        card.innerHTML = '<div class="flex items-center justify-between mb-1.5"><span class="font-semibold text-sm text-gray-800">' + u + '</span><span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full">' + info.done + '/' + info.scheduled + '</span></div><div class="w-full h-1 bg-gray-200 rounded-full overflow-hidden mb-2"><div class="h-full bg-blue-500 rounded-full" style="width:' + pct + '%"></div></div>';
        card.onclick = function() { filterByUnit(bld, u); };
        container.appendChild(card);
    });
    document.getElementById('ou-bld-label').textContent = bld;
    document.getElementById('ou-placeholder').classList.add('hidden');
    document.getElementById('ou-inner').classList.remove('hidden');
    document.getElementById('bld-clear-btn').classList.remove('hidden');
    if (unit) {
        // Restore unit filter (JS row hide + show log)
        _activeUnit = unit;
        document.querySelectorAll('tbody tr[data-building]').forEach(function(tr) {
            tr.style.display = (tr.dataset.building === bld && tr.dataset.unit === unit) ? '' : 'none';
        });
        document.getElementById('pm-plan-card').classList.add('hidden');
        document.getElementById('log-back-btn').classList.remove('hidden');
        document.getElementById('bld-filter-label').textContent = bld + ' › ' + unit;
        document.getElementById('bld-filter-banner').classList.remove('hidden');
        document.getElementById('maint-log-card').classList.remove('hidden');
        renumberRows();
        updatePaginationUrls(bld, unit);
    }
})();
</script>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
