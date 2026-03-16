<?php
$pageTitle    = 'Maintenance Record';
$pageSubtitle = 'Record #' . $record['maintenance_id'];

ob_start();
$r = $record;
?>

<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="<?= site_url('maintenance') ?>" class="hover:text-blue-600 transition">Maintenance</a>
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
    <span class="text-gray-700 font-medium">Record #<?= $r['maintenance_id'] ?></span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <!-- LEFT: Main content -->
    <div class="lg:col-span-2 space-y-4">

        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-screwdriver-wrench text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            <?= esc($r['group_name'] ?? 'Unknown Group') ?>
                        </h2>
                        <p class="text-xs text-gray-500 font-mono"><?= esc($r['group_code'] ?? '') ?></p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Serviced on: <span class="font-medium text-gray-600"><?= $r['maintenance_date'] ? date('F d, Y', strtotime($r['maintenance_date'])) : '' ?></span>
                        </p>
                    </div>
                </div>
                <span class="text-sm font-bold text-green-700 bg-green-50 px-3 py-1.5 rounded-lg whitespace-nowrap">
                    &#8369;<?= number_format((float)($r['cost'] ?? 0), 2) ?>
                </span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm border-t border-gray-50 pt-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Frequency</p>
                    <p class="font-medium text-gray-700"><?= esc($r['frequency'] ?? '') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Job Ticket ID</p>
                    <p class="font-medium text-gray-700"><?= esc($r['job_ticket_id'] ?? '') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Logged</p>
                    <p class="font-medium text-gray-700"><?= $r['created_at'] ? date('M d, Y', strtotime($r['created_at'])) : '' ?></p>
                </div>
            </div>
        </div>

        <!-- Activities -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-blue-400"></i>
                Activities Performed
            </h3>
            <?php
            $actRaw      = array_filter(array_map('trim', explode(',', $r['activities'] ?? '')));
            $actBase     = array_values(array_unique(array_map(function($a) {
                return preg_match('/^Others:/i', $a) ? 'Others' : $a;
            }, $actRaw)));
            $othersText  = '';
            foreach ($actRaw as $a) {
                if (preg_match('/^Others:\s*([\s\S]+)/i', $a, $m)) {
                    $lines = array_map(function($l) { return trim(preg_replace('/^[•\-]\s*/', '', $l)); }, explode("\n", trim($m[1])));
                    $othersText = implode("\n", array_filter($lines));
                    break;
                }
            }
            $locationGroups = $assetGroups ?? [];
            $totalAssets    = array_sum(array_map(fn($g) => count($g['assets']), $locationGroups));
            ?>
            <?php if (!empty($actBase)): ?>
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="w-full text-xs border-collapse min-w-[360px]">
                    <thead>
                        <!-- row 1 -->
                        <tr class="bg-blue-600 text-white">
                            <th rowspan="<?= $totalAssets > 0 ? 3 : 1 ?>" class="px-3 py-2 text-left font-semibold border-r border-blue-500 whitespace-nowrap" style="min-width:140px">Activity</th>
                            <?php if ($totalAssets > 0): ?>
                            <th colspan="<?= $totalAssets ?>" class="px-3 py-2 text-center font-semibold border-r border-blue-500">Equipment / Assets</th>
                            <?php endif; ?>
                            <th rowspan="<?= $totalAssets > 0 ? 3 : 1 ?>" class="px-3 py-2 text-center font-semibold" style="width:80px">Done</th>
                        </tr>
                        <?php if ($totalAssets > 0): ?>
                        <!-- row 2: location labels -->
                        <tr class="bg-blue-50 text-blue-800">
                            <?php foreach ($locationGroups as $grp): ?>
                            <th colspan="<?= count($grp['assets']) ?>" class="px-2 py-1 text-center font-semibold border-r border-blue-100 text-[10px]"><?= esc($grp['label']) ?></th>
                            <?php endforeach; ?>
                        </tr>
                        <!-- row 3: asset tags -->
                        <tr class="bg-gray-50 text-gray-600">
                            <?php foreach ($locationGroups as $grp): ?>
                                <?php foreach ($grp['assets'] as $asset): ?>
                                <th class="px-2 py-1 text-center border-r border-gray-200 font-mono font-semibold text-[10px]" style="max-width:60px">
                                    <?= esc($asset['asset_tag']) ?><br>
                                    <span class="font-normal text-gray-400" style="font-size:9px"><?= esc(substr($asset['brand_model'] ?? '', 0, 12)) ?></span>
                                </th>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody>
                        <?php foreach ($actBase as $idx => $act): ?>
                        <?php
                            $isOthers = ($act === 'Others');
                            $rowLabel = $isOthers ? ($othersText ?: 'Others') : $act;
                            $bg       = $idx % 2 === 0 ? '' : 'background:#f9fafb';
                        ?>
                        <tr class="border-t border-gray-100 hover:bg-blue-50/30" style="<?= $bg ?>">
                            <td class="px-3 py-2 font-medium text-gray-700 border-r border-gray-100<?= $isOthers ? ' whitespace-pre-line' : ' whitespace-nowrap' ?>"><?= esc($rowLabel) ?></td>
                            <?php if ($totalAssets > 0): ?>
                                <?php foreach ($locationGroups as $grp): ?>
                                    <?php foreach ($grp['assets'] as $asset): ?>
                                    <td class="px-2 py-2 text-center border-r border-gray-100" style="<?= $bg ?>">
                                        <i class="fa-solid fa-check text-blue-500"></i>
                                    </td>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <td class="px-2 py-2 text-center">
                                <i class="fa-solid fa-circle-check text-green-500"></i>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <p class="text-sm text-gray-400 italic">No activities recorded.</p>
            <?php endif; ?>
        </div>

        <!-- Conducted & Verified -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-check text-indigo-400"></i>
                Conducted &amp; Verified By
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Conducted By</p>
                    <p class="font-medium text-gray-700"><?= esc($r['conducted_by'] ?? '') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Date Conducted</p>
                    <p class="font-medium text-gray-700"><?= $r['conducted_date'] ? date('M d, Y', strtotime($r['conducted_date'])) : '' ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Verified By</p>
                    <p class="font-medium text-gray-700"><?= esc($r['verified_by'] ?? '') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Date Verified</p>
                    <p class="font-medium text-gray-700"><?= $r['verified_date'] ? date('M d, Y', strtotime($r['verified_date'])) : '' ?></p>
                </div>
                <?php if (!empty($r['remarks'])): ?>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Remarks</p>
                    <p class="text-gray-700 whitespace-pre-wrap"><?= esc($r['remarks']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Corrective Action -->
        <?php if (!empty($r['corrective_action']) || !empty($r['corrective_date'])): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-yellow-400"></i>
                Corrective Action
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Action Taken</p>
                    <p class="text-gray-700 whitespace-pre-wrap"><?= esc($r['corrective_action'] ?? '') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Date</p>
                    <p class="font-medium text-gray-700"><?= $r['corrective_date'] ? date('M d, Y', strtotime($r['corrective_date'])) : '' ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Responsible Person -->
        <?php if (!empty($r['responsible_person'])): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-tie text-teal-400"></i>
                Responsible Person
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Name</p>
                    <p class="font-medium text-gray-700"><?= esc($r['responsible_person']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Date</p>
                    <p class="font-medium text-gray-700"><?= $r['responsible_date'] ? date('M d, Y', strtotime($r['responsible_date'])) : '' ?></p>
                </div>
                <?php if (!empty($r['responsible_remarks'])): ?>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Remarks</p>
                    <p class="text-gray-700 whitespace-pre-wrap"><?= esc($r['responsible_remarks']) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- RIGHT: Sidebar -->
    <div class="space-y-4">

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Actions</h3>
            <div class="space-y-2">
                <a href="<?= site_url("maintenance/print/{$r['maintenance_id']}") ?>" target="_blank"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-green-700 bg-green-50 hover:bg-green-100 rounded-xl transition font-medium">
                    <i class="fa-solid fa-print w-4 text-center"></i> Print / Save PDF
                </a>
                <a href="<?= site_url("maintenance/checklist/{$r['maintenance_id']}") ?>" target="_blank"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-xl transition font-medium">
                    <i class="fa-solid fa-list-check w-4 text-center"></i> PM Checklist (ICTU-15)
                </a>
                <a href="<?= site_url("maintenance/edit/{$r['maintenance_id']}") ?>"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-xl transition font-medium">
                    <i class="fa-solid fa-pencil w-4 text-center"></i> Edit Record
                </a>
                <?php if (!empty($r['group_id'])): ?>
                <a href="<?= site_url("asset-groups/show/{$r['group_id']}") ?>"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-xl transition font-medium">
                    <i class="fa-solid fa-layer-group w-4 text-center"></i> View Group
                </a>
                <?php endif; ?>
                <a href="<?= site_url("maintenance/delete/{$r['maintenance_id']}") ?>"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition font-medium"
                   onclick="return confirm('Delete this record?')">
                    <i class="fa-solid fa-trash w-4 text-center"></i> Delete Record
                </a>
                <a href="<?= site_url('maintenance') ?>"
                       class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-600 font-medium text-sm transition">
                        <i class="fa-solid fa-arrow-left w-4 text-center"></i>
                        Back to List
                    </a>
            </div>
        </div>

        <!-- Group Info -->
        <?php if (!empty($r['group_name'])): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Asset Group</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-xs text-gray-400">Group Name</p>
                    <p class="font-semibold text-gray-800"><?= esc($r['group_name']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Code</p>
                    <p class="font-mono text-xs text-blue-700 bg-blue-50 px-2 py-0.5 rounded inline-block"><?= esc($r['group_code'] ?? '') ?></p>
                </div>
                <?php if (!empty($r['group_category'])): ?>
                <div>
                    <p class="text-xs text-gray-400">Category</p>
                    <p class="text-gray-700"><?= esc($r['group_category']) ?></p>
                </div>
                <?php endif; ?>
                <?php if (!empty($r['quantity'])): ?>
                <div>
                    <p class="text-xs text-gray-400">Assets in Group</p>
                    <p class="text-gray-700"><?= $r['quantity'] ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($r['asset_tag'])): ?>
                <div class="border-t border-gray-100 pt-3 mt-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Selected Asset</p>
                    <div class="bg-gray-50 rounded-xl p-3 space-y-1.5">
                        <div>
                            <p class="text-xs text-gray-400">Asset Tag</p>
                            <p class="font-mono text-xs font-bold text-gray-800"><?= esc($r['asset_tag']) ?></p>
                        </div>
                        <?php if (!empty($r['brand_model'])): ?>
                        <div>
                            <p class="text-xs text-gray-400">Brand / Model</p>
                            <p class="text-sm text-gray-700"><?= esc($r['brand_model']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($r['serial_number'])): ?>
                        <div>
                            <p class="text-xs text-gray-400">Serial No.</p>
                            <p class="font-mono text-xs text-gray-500"><?= esc($r['serial_number']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($r['asset_status'])): ?>
                        <?php
                        $asc = match($r['asset_status']) {
                            'Active'       => 'bg-green-100 text-green-700',
                            'Under Repair' => 'bg-yellow-100 text-yellow-700',
                            'Inactive'     => 'bg-gray-100 text-gray-600',
                            'Disposed'     => 'bg-red-100 text-red-600',
                            default        => 'bg-blue-100 text-blue-700',
                        };
                        ?>
                        <div>
                            <p class="text-xs text-gray-400">Status</p>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $asc ?>"><?= esc($r['asset_status']) ?></span>
                        </div>
                        <?php endif; ?>
                        <a href="<?= site_url('assets/show/' . $r['asset_id']) ?>"
                           class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 font-medium mt-1">
                            <i class="fa-solid fa-eye text-xs"></i> View Asset
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Timestamps -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Timestamps</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400">Created</p>
                    <p class="text-gray-700"><?= $r['created_at'] ? date('M d, Y h:i A', strtotime($r['created_at'])) : '' ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Updated</p>
                    <p class="text-gray-700"><?= $r['updated_at'] ? date('M d, Y h:i A', strtotime($r['updated_at'])) : '' ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>