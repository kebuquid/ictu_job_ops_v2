<?php
$pageTitle    = 'Asset Disposals';
$pageSubtitle = 'Manage disposed or retired assets';
$routePrefix  = $routePrefix ?? (str_starts_with(uri_string(), 'admin/') ? 'admin' : 'super-admin');

ob_start();
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-base flex items-center gap-2">
            <i class="fa-solid fa-trash-can text-red-400"></i>
            Disposal Records
            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full"><?= count($records) ?></span>
        </h2>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <form method="get" action="<?= site_url($routePrefix . '/disposals') ?>" class="flex items-center gap-1 w-full sm:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q"
                           class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 w-full sm:w-52"
                           placeholder="Search…"
                           value="<?= esc($keyword ?? '') ?>">
                </div>
                <?php if (!empty($keyword)): ?>
                    <a href="<?= site_url($routePrefix . '/disposals') ?>" class="p-2 text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
            <a href="<?= site_url($routePrefix . '/disposals/create') ?>"
               class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-plus text-xs"></i>
                Add Disposal
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[840px] text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Asset</th>
                    <th class="px-4 py-3 text-left">Disposal Date</th>
                    <th class="px-4 py-3 text-left">Condition</th>
                    <th class="px-4 py-3 text-left">Reason</th>
                    <th class="px-4 py-3 text-left">Approved By</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            <?php if (empty($records)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-trash-can text-4xl mb-3 block text-gray-200"></i>
                        No disposal records found.
                        <a href="<?= site_url($routePrefix . '/disposals/create') ?>" class="text-red-500 hover:underline ml-1">Add one now</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($records as $i => $r): ?>
                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-4 py-3 text-gray-400 text-xs"><?= $i + 1 ?></td>
                    <td class="px-4 py-3">
                        <a href="<?= site_url($routePrefix . "/assets/show/{$r['asset_id']}") ?>"
                           class="font-mono text-xs bg-red-50 text-red-700 px-2 py-1 rounded-md font-semibold hover:bg-red-100">
                            <?= esc($r['asset_tag'] ?? '—') ?>
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5"><?= esc($r['brand_model'] ?? '') ?></p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <?= $r['disposal_date'] ? date('M d, Y', strtotime($r['disposal_date'])) : '—' ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php
                        $condColor = match(strtolower($r['condition_status'] ?? '')) {
                            'good'         => 'bg-green-100 text-green-700',
                            'fair'         => 'bg-yellow-100 text-yellow-700',
                            'poor', 'bad'  => 'bg-red-100 text-red-700',
                            default        => 'bg-gray-100 text-gray-600',
                        };
                        ?>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium <?= $condColor ?>">
                            <?= esc($r['condition_status'] ?? '—') ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <p class="truncate text-xs" title="<?= esc($r['disposal_reason'] ?? '') ?>">
                            <?= esc($r['disposal_reason'] ?? '—') ?>
                        </p>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <?= $r['approved_by'] ? "User #{$r['approved_by']}" : '—' ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1 opacity-80 group-hover:opacity-100 transition">
                            <a href="<?= site_url($routePrefix . "/disposals/show/{$r['disposal_id']}") ?>"
                               class="p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition" title="View">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <a href="<?= site_url($routePrefix . "/disposals/edit/{$r['disposal_id']}") ?>"
                               class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 transition" title="Edit">
                                <i class="fa-solid fa-pencil text-xs"></i>
                            </a>
                            <a href="<?= site_url($routePrefix . "/disposals/delete/{$r['disposal_id']}") ?>"
                               class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Delete"
                               onclick="return confirm('Delete this disposal record?')">
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
        <span><?= count($records) ?> records shown</span>
        <span>Updated: <?= date('M d, Y h:i A') ?></span>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
