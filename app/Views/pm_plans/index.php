<?php
$pageTitle    = 'Preventive Maintenance Plans';
$pageSubtitle = 'Annual PM schedules for ICTU assets';

ob_start();
?>

<!-- Stats row -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-calendar-days text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $total ?></p>
            <p class="text-xs text-gray-500">Total Plans</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clipboard-check text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= date('Y') ?></p>
            <p class="text-xs text-gray-500">Current Year</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-print text-purple-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-700">Printable</p>
            <p class="text-xs text-gray-500">CSPC-F-ICTU-13 format</p>
        </div>
    </div>
</div>

<!-- Table card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800 text-base flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-blue-500"></i>
            PM Plans
            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full"><?= $total ?></span>
        </h2>
        <div class="flex items-center gap-2">
            <form method="get" action="<?= site_url('super-admin/pm-plans') ?>" class="flex items-center gap-1">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q"
                           class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-48"
                           placeholder="Search year, title…"
                           value="<?= esc($keyword ?? '') ?>">
                </div>
                <?php if (!empty($keyword)): ?>
                    <a href="<?= site_url('super-admin/pm-plans') ?>" class="p-2 text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
            <a href="<?= site_url('super-admin/pm-plans/create') ?>"
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-plus text-xs"></i>
                New Plan
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Year</th>
                    <th class="px-4 py-3 text-left">Title / Department</th>
                    <th class="px-4 py-3 text-left">Prepared By</th>
                    <th class="px-4 py-3 text-left">Reviewed By</th>
                    <th class="px-4 py-3 text-left">Approved By</th>
                    <th class="px-4 py-3 text-left">Created</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            <?php if (empty($plans)): ?>
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-calendar-days text-4xl mb-3 block text-gray-200"></i>
                        No preventive maintenance plans found.
                        <a href="<?= site_url('super-admin/pm-plans/create') ?>" class="text-blue-500 hover:underline ml-1">Create one</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($plans as $i => $pl): ?>
                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-4 py-3 text-gray-400 text-xs"><?= ($pager ? ($pager->getCurrentPage() - 1) * $perPage : 0) + $i + 1 ?></td>
                    <td class="px-4 py-3">
                        <span class="font-bold text-blue-700 text-base"><?= esc($pl['plan_year']) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-800"><?= esc($pl['title']) ?></p>
                        <?php if (!empty($pl['department'])): ?>
                            <p class="text-xs text-gray-400"><?= esc($pl['department']) ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-gray-300 mono"><?= esc($pl['document_code'] ?? '') ?></p>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <?= esc($pl['prepared_by'] ?? '—') ?><br>
                        <span class="text-gray-400"><?= esc($pl['prepared_title'] ?? '') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <?= esc($pl['reviewed_by'] ?? '—') ?><br>
                        <span class="text-gray-400"><?= esc($pl['reviewed_title'] ?? '') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <?= esc($pl['approved_by'] ?? '—') ?><br>
                        <span class="text-gray-400"><?= esc($pl['approved_title'] ?? '') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs text-nowrap">
                        <?= $pl['created_at'] ? date('M d, Y', strtotime($pl['created_at'])) : '—' ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1 opacity-80 group-hover:opacity-100 transition">
                            <a href="<?= site_url('super-admin/pm-plans/show/' . $pl['plan_id']) ?>"
                               class="p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 transition" title="View">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <a href="<?= site_url('super-admin/pm-plans/show/' . $pl['plan_id']) ?>" target="_blank"
                               class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 transition" title="Print / Save PDF">
                                <i class="fa-solid fa-print text-xs"></i>
                            </a>
                            <a href="<?= site_url('super-admin/pm-plans/edit/' . $pl['plan_id']) ?>"
                               class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 transition" title="Edit">
                                <i class="fa-solid fa-pencil text-xs"></i>
                            </a>
                            <a href="<?= site_url('super-admin/pm-plans/delete/' . $pl['plan_id']) ?>"
                               class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Delete"
                               onclick="return confirm('Delete this PM plan and all its items?')">
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

    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-xs text-gray-400 flex items-center justify-between">
        <span>Showing <?= count($plans) ?> of <?= $total ?> plans</span>
        <span>Updated: <?= date('M d, Y h:i A') ?></span>
    </div>

    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <span class="text-xs text-gray-400">
            <?= ($pager->getCurrentPage() - 1) * $perPage + 1 ?> – <?= min($pager->getCurrentPage() * $perPage, $total) ?> of <?= $total ?>
        </span>
        <div class="flex items-center gap-1 text-sm">
            <?php
            $cur      = $pager->getCurrentPage();
            $tot      = $pager->getPageCount();
            $base     = site_url('super-admin/pm-plans') . (empty($keyword) ? '?' : '?q=' . urlencode($keyword) . '&');
            ?>
            <?php if ($cur > 1): ?>
                <a href="<?= $base ?>page=<?= $cur - 1 ?>" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
            <?php endif; ?>
            <?php for ($p = max(1, $cur - 2); $p <= min($tot, $cur + 2); $p++): ?>
                <a href="<?= $base ?>page=<?= $p ?>"
                   class="px-3 py-1.5 rounded-lg text-xs <?= $p === $cur ? 'bg-blue-600 text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
            <?php if ($cur < $tot): ?>
                <a href="<?= $base ?>page=<?= $cur + 1 ?>" class="px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition text-xs">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
