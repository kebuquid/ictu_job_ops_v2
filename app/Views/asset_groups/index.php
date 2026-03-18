<?php
// Data: $groups (array with asset_count)
$pageTitle    = 'Asset Groups';
$pageSubtitle = 'Manage bulk asset groups';
$routePrefix  = $routePrefix ?? (str_starts_with(uri_string(), 'admin/') ? 'admin' : 'super-admin');
ob_start();
?>

<!-- Header row -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Asset Groups</h2>
        <p class="text-sm text-gray-500 mt-0.5"><?= $total ?> group(s) total</p>
    </div>
    <a href="<?= site_url($routePrefix . '/asset-groups/create') ?>"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow transition">
        <i class="fa-solid fa-layer-group"></i> New Group
    </a>
</div>

<?php if (empty($groups)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
        <i class="fa-solid fa-layer-group text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 text-sm">No asset groups yet.</p>
        <a href="<?= site_url($routePrefix . '/asset-groups/create') ?>" class="mt-4 inline-block text-blue-600 text-sm font-medium hover:underline">Create your first group →</a>
    </div>
<?php else: ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[980px] text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-left text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-5 py-3">Group</th>
                <th class="px-5 py-3">Code</th>
                <th class="px-5 py-3">Category</th>
                <th class="px-5 py-3 text-center">Qty</th>
                <th class="px-5 py-3 text-center">Assets</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date Created</th>
                <th class="px-5 py-3 text-right">Acq. Cost / unit</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($groups as $g): ?>
            <?php
            $statusColor = match($g['status'] ?? '') {
                'Active'       => 'bg-green-100 text-green-700',
                'Under Repair' => 'bg-yellow-100 text-yellow-700',
                'Inactive'     => 'bg-gray-100 text-gray-600',
                'Disposed'     => 'bg-red-100 text-red-600',
                default        => 'bg-blue-100 text-blue-700',
            };
            ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3 font-semibold text-gray-800"><?= esc($g['group_name']) ?></td>
                <td class="px-5 py-3 text-gray-500"><?= esc($g['group_code'] ?? '—') ?></td>
                <td class="px-5 py-3 text-gray-600"><?= esc($g['category'] ?? '—') ?></td>
                <td class="px-5 py-3 text-center font-medium text-gray-700"><?= $g['quantity'] ?></td>
                <td class="px-5 py-3 text-center">
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        <?= $g['asset_count'] ?>
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap <?= $statusColor ?>">
                        <?= esc($g['status']) ?>
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500">
                    <?= $g['created_at'] ? date('M d, Y', strtotime($g['created_at'])) : '—' ?>
                </td>
                <td class="px-5 py-3 text-right text-gray-700 font-medium">
                    <?= $g['acquisition_cost'] ? '₱' . number_format((float)$g['acquisition_cost'], 2) : '—' ?>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="<?= site_url($routePrefix . '/asset-groups/show/' . $g['group_id']) ?>"
                           title="View"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </a>
                        <button type="button"
                                title="Delete"
                                onclick="confirmDelete(<?= $g['group_id'] ?>, '<?= esc($g['group_name'], 'js') ?>')"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div><!-- end overflow-x-auto -->
    <?php
    $cp   = $pager->getCurrentPage('groups');
    $tp   = $pager->getPageCount('groups');
    $pTot = $pager->getTotal('groups');
    $pp   = $pager->getPerPage('groups');
    $from = ($cp - 1) * $pp + 1;
    $to   = min($cp * $pp, $pTot);
    ?>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="text-xs text-gray-400">Showing <?= $from ?>&#8211;<?= $to ?> of <?= $pTot ?> groups</span>
        <?php if ($tp > 1): ?>
        <div class="flex items-center gap-1">
            <?php if ($cp > 1): ?>
            <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page_groups' => $cp - 1])) ?>"
               class="px-3 py-1.5 text-xs rounded-lg text-gray-600 hover:bg-gray-100 border border-gray-200 bg-white transition">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            <?php for ($p = max(1, $cp - 2); $p <= min($tp, $cp + 2); $p++):
                  $pgParams = array_merge($_GET, ['page_groups' => $p]); ?>
            <a href="<?= current_url() . '?' . http_build_query($pgParams) ?>"
               class="px-3 py-1.5 text-xs rounded-lg font-medium transition <?= $p === $cp ? 'bg-blue-600 text-white border border-blue-600' : 'text-gray-600 hover:bg-gray-100 border border-gray-200 bg-white' ?>">
                <?= $p ?>
            </a>
            <?php endfor; ?>
            <?php if ($cp < $tp): ?>
            <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page_groups' => $cp + 1])) ?>"
               class="px-3 py-1.5 text-xs rounded-lg text-gray-600 hover:bg-gray-100 border border-gray-200 bg-white transition">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-trash text-red-500"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-sm">Delete Group</h3>
                <p class="text-xs text-gray-500" id="delete-modal-label"></p>
            </div>
            <button onclick="closeDeleteModal()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <p class="text-xs text-gray-600 bg-yellow-50 border border-yellow-200 rounded-lg px-3 py-2 mb-5">
            <i class="fa-solid fa-triangle-exclamation text-yellow-500 mr-1"></i>
            Individual assets will be <strong>unlinked</strong> but NOT deleted.
        </p>
        <div class="flex gap-3 justify-end">
            <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition">
                Cancel
            </button>
            <a id="delete-confirm-btn" href="#"
               class="inline-flex items-center gap-2 px-5 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold shadow transition">
                <i class="fa-solid fa-trash"></i> Delete
            </a>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('delete-modal-label').textContent = name;
    document.getElementById('delete-confirm-btn').href = '<?= site_url($routePrefix . '/asset-groups/delete') ?>/' + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
