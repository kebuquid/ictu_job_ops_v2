<?php
// Data: $group, $assets, $availableAssets, $unitName, $buildingName
// Also need all groups for the transfer modal
$pageTitle    = 'Group: ' . esc($group['group_name']);
$pageSubtitle = ($group['quantity'] ?? 0) . ' asset(s) in this group';
$routePrefix  = $routePrefix ?? (str_starts_with(uri_string(), 'admin/') ? 'admin' : 'super-admin');
ob_start();
?>

<div class="max-w-5xl mx-auto">

    <!-- Back -->
    <a href="<?= site_url($routePrefix . '/asset-groups') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-5">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Groups
    </a>

    <!-- GROUP HERO -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5">
        <div class="p-6 border-b border-gray-100 flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900"><?= esc($group['group_name']) ?></h2>
                    <p class="text-sm text-gray-500"><?= esc($group['group_code'] ?? '') ?></p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <?php
                $sc = match($group['status'] ?? '') {
                    'Active'       => 'bg-green-100 text-green-700',
                    'Under Repair' => 'bg-yellow-100 text-yellow-700',
                    'Inactive'     => 'bg-gray-100 text-gray-600',
                    'Disposed'     => 'bg-red-100 text-red-600',
                    default        => 'bg-blue-100 text-blue-700',
                };
                ?>
                <span class="text-sm font-semibold px-3 py-1.5 rounded-full <?= $sc ?>">
                    <?= esc($group['status']) ?>
                </span>
                <a href="<?= site_url($routePrefix . '/asset-groups/edit/' . $group['group_id']) ?>"
                   class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 border border-blue-200 px-3 py-1.5 rounded-xl font-medium transition">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Group
                </a>
                <a href="<?= site_url($routePrefix . '/asset-groups/delete/' . $group['group_id']) ?>"
                   onclick="return confirm('Delete group? Individual assets will be unlinked but NOT deleted.')"
                   class="inline-flex items-center gap-2 text-sm text-red-500 hover:text-red-700 border border-red-200 px-3 py-1.5 rounded-xl font-medium transition">
                    <i class="fa-solid fa-trash"></i> Delete Group
                </a>
            </div>
        </div>

        <!-- DETAIL CARDS -->
        <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-xl p-4 text-center">
                <p class="text-xs text-blue-500 font-medium mb-1">Total Quantity</p>
                <p class="text-2xl font-bold text-blue-700"><?= $group['quantity'] ?></p>
            </div>
            <div class="bg-green-50 rounded-xl p-4 text-center">
                <p class="text-xs text-green-600 font-medium mb-1">Acq. Cost / unit</p>
                <p class="text-lg font-bold text-green-700">
                    <?= $group['acquisition_cost'] ? '₱' . number_format((float)$group['acquisition_cost'], 2) : '—' ?>
                </p>
            </div>
            <div class="bg-orange-50 rounded-xl p-4 text-center">
                <p class="text-xs text-orange-600 font-medium mb-1">Depr. Cost / unit</p>
                <p class="text-lg font-bold text-orange-700">
                    <?= $group['depreciation_cost'] ? '₱' . number_format((float)$group['depreciation_cost'], 2) : '—' ?>
                </p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 text-center">
                <p class="text-xs text-purple-500 font-medium mb-1">Total Acq. Value</p>
                <p class="text-lg font-bold text-purple-700">
                    <?php
                    $total = (float)($group['acquisition_cost'] ?? 0) * (int)($group['quantity'] ?? 1);
                    echo $total > 0 ? '₱' . number_format($total, 2) : '—';
                    ?>
                </p>
            </div>
        </div>

        <!-- INFO ROW -->
        <div class="px-6 pb-6 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Category</p>
                <p class="font-medium text-gray-700"><?= esc($group['category'] ?? '—') ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Tag Prefix</p>
                <p class="font-medium text-gray-700 font-mono"><?= esc($group['tag_prefix'] ?? '—') ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Building</p>
                <p class="font-medium text-gray-700"><?= esc($buildingName) ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Org Unit</p>
                <p class="font-medium text-gray-700"><?= esc($unitName) ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Assigned To</p>
                <p class="font-medium text-gray-700"><?= esc($group['assigned_to'] ?? '—') ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Date Created</p>
                <p class="font-medium text-gray-700">
                    <?= $group['created_at'] ? date('M d, Y', strtotime($group['created_at'])) : '—' ?>
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Date Updated</p>
                <p class="font-medium text-gray-700">
                    <?= $group['updated_at'] ? date('M d, Y', strtotime($group['updated_at'])) : '—' ?>
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Lifecycle</p>
                <p class="font-medium text-gray-700"><?= esc($group['lifecycle'] ?? '—') ?></p>
            </div>
            <?php if ($group['description']): ?>
            <div class="sm:col-span-4">
                <p class="text-xs text-gray-400 mb-0.5">Description</p>
                <p class="font-medium text-gray-700"><?= esc($group['description']) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ASSETS TABLE -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-box-archive text-gray-500"></i>
                <h3 class="font-semibold text-gray-800">Generated Assets</h3>
                <span class="ml-1 text-xs bg-blue-100 text-blue-700 font-bold px-2.5 py-0.5 rounded-full"><?= count($assets) ?></span>
            </div>
            <a href="<?= site_url($routePrefix . '/assets') ?>" class="text-xs text-blue-600 hover:underline font-medium">
                View all assets →
            </a>
        </div>

        <?php if (empty($assets)): ?>
            <div class="p-10 text-center text-gray-400 text-sm">No assets linked to this group.</div>
        <?php else: ?>
        <div class="overflow-x-auto">
        <table class="w-full min-w-[920px] text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Asset Tag</th>
                    <th class="px-5 py-3 text-left">Property No.</th>
                    <th class="px-5 py-3 text-left">Brand / Model</th>
                    <th class="px-5 py-3 text-left">Serial</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            <?php foreach ($assets as $i => $a): ?>
                <?php
                $sc2 = match($a['status'] ?? '') {
                    'Active'       => 'bg-green-100 text-green-700',
                    'Under Repair' => 'bg-yellow-100 text-yellow-700',
                    'Inactive'     => 'bg-gray-100 text-gray-600',
                    'Disposed'     => 'bg-red-100 text-red-600',
                    default        => 'bg-blue-100 text-blue-700',
                };
                ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 text-gray-400 text-xs"><?= $i + 1 ?></td>
                    <td class="px-5 py-3 font-semibold text-gray-800 font-mono text-xs"><?= esc($a['asset_tag']) ?></td>
                    <td class="px-5 py-3 text-gray-500"><?= esc($a['property_no'] ?? '—') ?></td>
                    <td class="px-5 py-3 text-gray-600"><?= esc($a['brand_model'] ?? '—') ?></td>
                    <td class="px-5 py-3 text-gray-400 font-mono text-xs"><?= esc($a['serial_number'] ?? '—') ?></td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full <?= $sc2 ?>">
                            <?= esc($a['status']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="<?= site_url($routePrefix . '/assets/show/' . $a['asset_id']) ?>"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fa-solid fa-eye mr-1"></i>View
                        </a>
                        <a href="<?= site_url($routePrefix . '/assets/edit/' . $a['asset_id']) ?>"
                           class="ml-3 text-xs text-gray-500 hover:text-gray-700 font-medium">
                            <i class="fa-solid fa-pen mr-1"></i>Edit
                        </a>
                        <button type="button"
                                onclick="openTransfer(<?= $a['asset_id'] ?>, '<?= esc($a['asset_tag'], 'js') ?>', <?= (float)($a['acquisition_cost'] ?? 0) ?>, <?= (float)($a['depreciation_cost'] ?? 0) ?>)"
                                class="ml-3 text-xs text-purple-600 hover:text-purple-800 font-medium">
                            <i class="fa-solid fa-right-left mr-1"></i>Transfer
                        </button>
                        <a href="<?= site_url($routePrefix . '/asset-groups/remove/' . $group['group_id'] . '/' . $a['asset_id']) ?>"
                           onclick="return confirm('Remove this asset from the group? The asset itself will not be deleted.')"
                           class="ml-3 text-xs text-red-400 hover:text-red-600 font-medium">
                            <i class="fa-solid fa-xmark mr-1"></i>Remove
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- ASSIGN EXISTING ASSETS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-5">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-link text-blue-500"></i>
            <h3 class="font-semibold text-gray-800">Assign Existing Assets</h3>
            <span class="ml-auto text-xs text-gray-400"><?= count($availableAssets) ?> unassigned asset(s) available</span>
        </div>

        <?php if (empty($availableAssets)): ?>
            <div class="p-8 text-center text-gray-400 text-sm">
                <i class="fa-solid fa-box-open text-2xl mb-2 block text-gray-300"></i>
                All assets are already assigned to a group.
                <a href="<?= site_url($routePrefix . '/assets/create') ?>" class="ml-1 text-blue-500 hover:underline">Create a new asset →</a>
            </div>
        <?php else: ?>
            <form action="<?= site_url($routePrefix . '/asset-groups/assign/' . $group['group_id']) ?>" method="post" class="p-6">
                <?= csrf_field() ?>
                <p class="text-xs text-gray-500 mb-3">Select one or more assets and click <strong>Assign to Group</strong>.</p>

                <div class="border border-gray-200 rounded-xl overflow-hidden mb-4">
                    <div class="bg-gray-50 px-4 py-2 flex flex-wrap items-center gap-2 border-b border-gray-100">
                        <input type="checkbox" id="check-all" class="rounded" onchange="document.querySelectorAll('.asset-cb').forEach(c=>c.checked=this.checked); updateAssignCosts();">
                        <label for="check-all" class="text-xs font-semibold text-gray-500 cursor-pointer select-none">Select all</label>
                        <input type="text" id="asset-search" placeholder="Search by tag, serial no., brand or category..."
                               class="sm:ml-auto border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:ring-2 focus:ring-blue-400 outline-none w-full sm:w-64"
                               oninput="filterAssets(this.value)">
                    </div>
                    <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto" id="asset-list">
                        <?php foreach ($availableAssets as $av): ?>
                        <label class="asset-row flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition"
                               data-search="<?= strtolower(esc($av['asset_tag'] . ' ' . ($av['serial_number'] ?? '') . ' ' . ($av['brand_model'] ?? '') . ' ' . ($av['category'] ?? ''))) ?>"
                               data-acq="<?= (float)($av['acquisition_cost'] ?? 0) ?>"
                               data-depr="<?= (float)($av['depreciation_cost'] ?? 0) ?>">
                            <input type="checkbox" name="asset_ids[]" value="<?= $av['asset_id'] ?>" class="asset-cb rounded">
                            <span class="font-mono text-xs font-semibold text-gray-800 w-28 shrink-0"><?= esc($av['asset_tag']) ?></span>
                            <div class="flex flex-col flex-1 min-w-0">
                                <span class="text-xs text-gray-700 truncate"><?= esc($av['brand_model'] ?? '—') ?></span>
                                <?php if (!empty($av['serial_number'])): ?>
                                <span class="text-xs text-gray-400 font-mono">S/N: <?= esc($av['serial_number']) ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs text-gray-400 hidden sm:block"><?= esc($av['category'] ?? '—') ?></span>
                            <?php
                            $sc3 = match($av['status'] ?? '') {
                                'Active'       => 'bg-green-100 text-green-700',
                                'Under Repair' => 'bg-yellow-100 text-yellow-700',
                                'Inactive'     => 'bg-gray-100 text-gray-600',
                                'Disposed'     => 'bg-red-100 text-red-600',
                                default        => 'bg-blue-100 text-blue-700',
                            };
                            ?>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $sc3 ?>"><?= esc($av['status']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Cost Summary for selected assets -->
                <div id="assign-cost-summary" class="hidden mb-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-xs font-semibold text-gray-600 mb-3">Cost Summary for Selected Assets</p>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white rounded-lg p-3 text-center border border-gray-100">
                            <p class="text-xs text-gray-400 mb-0.5">Selected</p>
                            <p class="text-xl font-bold text-gray-800" id="assign-count">0</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3 text-center border border-green-100">
                            <p class="text-xs text-green-600 font-medium mb-0.5">Avg. Acq. Cost / unit</p>
                            <p class="text-base font-bold text-green-700" id="assign-acq">—</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-3 text-center border border-orange-100">
                            <p class="text-xs text-orange-600 font-medium mb-0.5">Avg. Depr. Cost / unit</p>
                            <p class="text-base font-bold text-orange-700" id="assign-depr">—</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow transition">
                        <i class="fa-solid fa-link"></i> Assign to Group
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

</div>
<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>

<!-- TRANSFER MODAL -->
<div id="transfer-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40" onclick="closeTransfer()"></div>

    <!-- Dialog -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-right-left text-purple-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800 text-sm">Transfer Asset</h3>
                <p class="text-xs text-gray-500" id="modal-asset-label"></p>
            </div>
            <button onclick="closeTransfer()" class="ml-auto text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="transfer-form" method="post">
            <?= csrf_field() ?>

            <!-- Asset cost preview -->
            <div id="modal-cost-info" class="hidden mb-4 grid grid-cols-2 gap-3">
                <div class="bg-green-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-green-600 font-medium mb-0.5">Acq. Cost / unit</p>
                    <p class="text-sm font-bold text-green-700" id="modal-acq-cost">—</p>
                </div>
                <div class="bg-orange-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-orange-600 font-medium mb-0.5">Depr. Cost / unit</p>
                    <p class="text-sm font-bold text-orange-700" id="modal-depr-cost">—</p>
                </div>
            </div>

            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Transfer to Group</label>
            <select name="target_group_id" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none bg-white mb-5">
                <option value="">— Select target group —</option>
                <?php foreach ($otherGroups as $og): ?>
                    <option value="<?= $og['group_id'] ?>">
                        <?= esc($og['group_name']) ?>
                        <?= $og['group_code'] ? ' (' . esc($og['group_code']) . ')' : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeTransfer()"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl font-medium transition">
                    Cancel
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-semibold shadow transition">
                    <i class="fa-solid fa-right-left"></i> Transfer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterAssets(q) {
    const query = q.trim().toLowerCase();
    let visible = 0;
    document.querySelectorAll('#asset-list .asset-row').forEach(row => {
        const match = !query || row.dataset.search.includes(query);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    // show/hide "no results" message
    let noRes = document.getElementById('assign-no-results');
    if (!noRes) {
        noRes = document.createElement('p');
        noRes.id = 'assign-no-results';
        noRes.className = 'text-center text-xs text-gray-400 py-4';
        noRes.textContent = 'No assets match your search.';
        document.getElementById('asset-list').appendChild(noRes);
    }
    noRes.style.display = visible === 0 ? '' : 'none';
}

function updateAssignCosts() {
    const checked = document.querySelectorAll('.asset-cb:checked');
    const count = checked.length;
    const summary = document.getElementById('assign-cost-summary');
    document.getElementById('assign-count').textContent = count;
    if (count === 0) {
        if (summary) summary.classList.add('hidden');
        return;
    }
    if (summary) summary.classList.remove('hidden');
    let totalAcq = 0, acqCount = 0;
    let totalDepr = 0, deprCount = 0;
    checked.forEach(cb => {
        const row = cb.closest('.asset-row');
        if (!row) return;
        const acq = parseFloat(row.dataset.acq || 0);
        const depr = parseFloat(row.dataset.depr || 0);
        if (acq > 0) { totalAcq += acq; acqCount++; }
        if (depr > 0) { totalDepr += depr; deprCount++; }
    });
    const fmt = v => '₱' + v.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const avgAcq = acqCount > 0 ? totalAcq / acqCount : 0;
    const avgDepr = deprCount > 0 ? totalDepr / deprCount : 0;
    document.getElementById('assign-acq').textContent = avgAcq > 0 ? fmt(avgAcq) : '—';
    document.getElementById('assign-depr').textContent = avgDepr > 0 ? fmt(avgDepr) : '—';
}

// Attach change listener to each checkbox
document.querySelectorAll('.asset-cb').forEach(cb => {
    cb.addEventListener('change', updateAssignCosts);
});

function openTransfer(assetId, assetTag, acqCost, deprCost) {
    document.getElementById('modal-asset-label').textContent = assetTag;
    document.getElementById('transfer-form').action = '<?= site_url($routePrefix . '/asset-groups/transfer/' . $group['group_id']) ?>/' + assetId;
    const costInfo = document.getElementById('modal-cost-info');
    if (acqCost > 0 || deprCost > 0) {
        costInfo.classList.remove('hidden');
        const fmt = v => v > 0 ? '₱' + v.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '—';
        document.getElementById('modal-acq-cost').textContent = fmt(acqCost);
        document.getElementById('modal-depr-cost').textContent = fmt(deprCost);
    } else {
        costInfo.classList.add('hidden');
    }
    document.getElementById('transfer-modal').classList.remove('hidden');
}
function closeTransfer() {
    document.getElementById('transfer-modal').classList.add('hidden');
}
</script>
