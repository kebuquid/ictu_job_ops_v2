<?php
$pageTitle    = 'Asset Management';
$pageSubtitle = 'Track and manage all organization assets';

$routePrefix = 'super-admin';
if (str_starts_with(uri_string(), 'admin/')) {
    $routePrefix = 'admin';
} elseif (!str_starts_with(uri_string(), 'super-admin/')) {
    $sess = session()->get('user');
    if (isset($sess['role_id']) && (int) $sess['role_id'] === 2) {
        $routePrefix = 'admin';
    }
}

$statusConfig = [
    'Active'       => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-500'],
    'Inactive'     => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600',   'dot' => 'bg-gray-400'],
    'Under Repair' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500'],
    'Disposed'     => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-500'],
];

ob_start();
?>

<!-- STAT CARDS -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-boxes-stacked text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['total'] ?></p>
            <p class="text-xs text-gray-500">Total Assets</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-check text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['active'] ?></p>
            <p class="text-xs text-gray-500">Active</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-wrench text-yellow-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['under_repair'] ?></p>
            <p class="text-xs text-gray-500">Under Repair</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-ban text-gray-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['inactive'] ?></p>
            <p class="text-xs text-gray-500">Inactive</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-trash-can text-red-500 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['disposed'] ?></p>
            <p class="text-xs text-gray-500">Disposed</p>
        </div>
    </div>

</div>

<!-- TABLE CARD -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- Card header: search + add button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-base flex items-center gap-2">
            <i class="fa-solid fa-box-archive text-blue-500"></i>
            Asset List
            <span class="ml-1 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full"><?= $pager->getTotal('assets') ?></span>
        </h2>
        <div class="flex items-center gap-2">
            <!-- Search -->
            <form method="get" action="<?= site_url($routePrefix . '/assets') ?>" class="flex items-center gap-1">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q"
                           class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-56"
                           placeholder="Search assets…"
                           value="<?= esc($keyword ?? '') ?>">
                </div>
                <?php if (!empty($keyword)): ?>
                          <a href="<?= site_url($routePrefix . '/assets') ?>"
                       class="p-2 text-gray-400 hover:text-red-500 transition" title="Clear">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
            <!-- Filter by status -->
            <form method="get" action="<?= site_url($routePrefix . '/assets') ?>">
                <select name="status" onchange="this.form.submit()"
                        class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-600">
                    <option value="">All Status</option>
                    <?php foreach (['Active','Inactive','Under Repair','Disposed'] as $s): ?>
                        <option value="<?= $s ?>" <?= (($filterStatus ?? '') === $s) ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <!-- Add button -->
                <a href="<?= site_url($routePrefix . '/assets/create') ?>"
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-plus text-xs"></i>
                Add Asset
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <th class="px-4 py-3 text-left font-semibold">#</th>
                    <th class="px-4 py-3 text-left font-semibold">Asset Tag</th>
                    <th class="px-4 py-3 text-left font-semibold">Property No.</th>
                    <th class="px-4 py-3 text-left font-semibold">Brand / Model</th>
                    <th class="px-4 py-3 text-left font-semibold">Category</th>
                    <th class="px-4 py-3 text-left font-semibold">Serial No.</th>
                    <th class="px-4 py-3 text-left font-semibold">Date Acquired</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            <?php
            $pageOffset = ($pager->getCurrentPage('assets') - 1) * $pager->getPerPage('assets');
            ?>
            <?php if (empty($assets)): ?>
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-box-open text-4xl mb-3 block text-gray-200"></i>
                        No assets found<?= !empty($keyword) ? ' for "' . esc($keyword) . '"' : '' ?>.
                        <a href="<?= site_url($routePrefix . '/assets/create') ?>" class="text-blue-500 hover:underline ml-1">Add one now</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($assets as $i => $asset):
                    $sc = $statusConfig[$asset['status']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'];
                ?>
                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-4 py-3 text-gray-400 text-xs"><?= $pageOffset + $i + 1 ?></td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-md font-semibold">
                            <?= esc($asset['asset_tag']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700"><?= esc($asset['property_no'] ?? '—') ?></td>
                    <td class="px-4 py-3 font-medium text-gray-800"><?= esc($asset['brand_model'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= esc($asset['category'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-gray-500 font-mono text-xs"><?= esc($asset['serial_number'] ?? '—') ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= $asset['date_acquired'] ? date('M d, Y', strtotime($asset['date_acquired'])) : '—' ?></td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium <?= $sc['bg'] ?> <?= $sc['text'] ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $sc['dot'] ?>"></span>
                            <?= esc($asset['status'] ?? '—') ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1 opacity-80 group-hover:opacity-100 transition">
                                     <a href="<?= site_url($routePrefix . "/assets/show/{$asset['asset_id']}") ?>"
                               class="p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition" title="View">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                                     <a href="<?= site_url($routePrefix . "/assets/edit/{$asset['asset_id']}") ?>"
                               class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 transition" title="Edit">
                                <i class="fa-solid fa-pencil text-xs"></i>
                            </a>
                                     <a href="<?= site_url($routePrefix . "/assets/delete/{$asset['asset_id']}") ?>"
                               class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Delete"
                               onclick="return confirm('Are you sure you want to delete asset <?= esc($asset['asset_tag']) ?>?')">
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

    <!-- Table footer with pagination -->
    <?php
    $cp   = $pager->getCurrentPage('assets');
    $tp   = $pager->getPageCount('assets');
    $pTot = $pager->getTotal('assets');
    $pp   = $pager->getPerPage('assets');
    $from = ($cp - 1) * $pp + 1;
    $to   = min($cp * $pp, $pTot);
    ?>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="text-xs text-gray-400">Showing <?= $from ?>&#8211;<?= $to ?> of <?= $pTot ?> assets</span>
        <?php if ($tp > 1): ?>
        <div class="flex items-center gap-1">
            <?php if ($cp > 1): ?>
            <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page_assets' => $cp - 1])) ?>"
               class="px-3 py-1.5 text-xs rounded-lg text-gray-600 hover:bg-gray-100 border border-gray-200 bg-white transition">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            <?php for ($p = max(1, $cp - 2); $p <= min($tp, $cp + 2); $p++):
                  $pgParams = array_merge($_GET, ['page_assets' => $p]); ?>
            <a href="<?= current_url() . '?' . http_build_query($pgParams) ?>"
               class="px-3 py-1.5 text-xs rounded-lg font-medium transition <?= $p === $cp ? 'bg-blue-600 text-white border border-blue-600' : 'text-gray-600 hover:bg-gray-100 border border-gray-200 bg-white' ?>">
                <?= $p ?>
            </a>
            <?php endfor; ?>
            <?php if ($cp < $tp): ?>
            <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page_assets' => $cp + 1])) ?>"
               class="px-3 py-1.5 text-xs rounded-lg text-gray-600 hover:bg-gray-100 border border-gray-200 bg-white transition">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>

