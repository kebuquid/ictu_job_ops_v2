<?php
$pageTitle    = 'Asset Details';
$pageSubtitle = 'Full information for asset ' . esc($asset['asset_tag']);

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
    'Active'       => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-500',  'ring' => 'ring-green-200'],
    'Inactive'     => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600',   'dot' => 'bg-gray-400',   'ring' => 'ring-gray-200'],
    'Under Repair' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500', 'ring' => 'ring-yellow-200'],
    'Disposed'     => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-500',    'ring' => 'ring-red-200'],
];
$sc = $statusConfig[$asset['status']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400', 'ring' => 'ring-gray-200'];

ob_start();
?>

<div class="max-w-5xl mx-auto">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="<?= site_url($routePrefix . '/assets') ?>" class="hover:text-blue-600 transition">Assets</a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <span class="text-gray-700 font-medium"><?= esc($asset['asset_tag']) ?></span>
    </nav>

    <style>
    #asset-show-grid{display:grid;gap:1.25rem;grid-template-columns:1fr}
    @media(min-width:768px){#asset-show-grid{grid-template-columns:3fr 2fr !important}}
    </style>
    <div id="asset-show-grid">

        <!-- LEFT: Asset Card -->
        <div class="space-y-5">

            <!-- Hero Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <?php if (!empty($asset['asset_image'])): ?>
                            <img src="<?= base_url('uploads/assets/' . esc($asset['asset_image'])) ?>"
                                 alt="Asset Image"
                                 class="w-20 h-20 rounded-xl object-cover border-2 border-white/30 shadow flex-shrink-0">
                            <?php else: ?>
                            <div class="w-20 h-20 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-image text-white/40 text-2xl"></i>
                            </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-blue-200 text-xs font-medium uppercase tracking-wider mb-1">Asset Tag</p>
                                <h2 class="text-2xl font-bold text-white font-mono"><?= esc($asset['asset_tag']) ?></h2>
                                <p class="text-blue-200 text-sm mt-1"><?= esc($asset['brand_model'] ?? 'No brand/model') ?></p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold ring-1 <?= $sc['bg'] ?> <?= $sc['text'] ?> <?= $sc['ring'] ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $sc['dot'] ?>"></span>
                            <?= esc($asset['status'] ?? '—') ?>
                        </span>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-5">
                    <?php
                    $fields = [
                        ['label' => 'Property No.',   'value' => $asset['property_no']   ?? null, 'icon' => 'fa-hashtag'],
                        ['label' => 'Serial Number',  'value' => $asset['serial_number']  ?? null, 'icon' => 'fa-barcode', 'mono' => true],
                        ['label' => 'Category',       'value' => $asset['category']       ?? null, 'icon' => 'fa-layer-group'],
                        ['label' => 'Section',        'value' => $sectionName ?? ($asset['section_id'] ?? null), 'icon' => 'fa-building'],
                        ['label' => 'Assigned To',    'value' => $assignedToName ?? ($asset['assigned_to'] ?? null), 'icon' => 'fa-user'],
                        ['label' => 'Building',       'value' => $buildingName ?? null,         'icon' => 'fa-house-chimney'],
                        ['label' => 'Org Unit',       'value' => $unitName ?? null,             'icon' => 'fa-sitemap'],
                    ];
                    foreach ($fields as $f): ?>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5 flex items-center gap-1">
                            <i class="fa-solid <?= $f['icon'] ?> text-gray-300"></i>
                            <?= $f['label'] ?>
                        </p>
                        <p class="text-sm font-semibold text-gray-800 <?= ($f['mono'] ?? false) ? 'font-mono' : '' ?>">
                            <?= esc($f['value'] ?? '—') ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Financial -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-peso-sign text-green-500"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Financial &amp; Dates</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Cost row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-green-50 rounded-xl p-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-peso-sign text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-green-600 font-medium">Acquisition Cost</p>
                                <p class="text-lg font-bold text-green-700">
                                    ₱<?= number_format((float)($asset['acquisition_cost'] ?? 0), 2) ?>
                                </p>
                            </div>
                        </div>
                        <div class="bg-orange-50 rounded-xl p-4 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-arrow-trend-down text-orange-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-orange-600 font-medium">Depreciation Cost</p>
                                <p class="text-lg font-bold text-orange-700">
                                    ₱<?= number_format((float)($asset['depreciation_cost'] ?? 0), 2) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Dates row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-calendar-plus text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Date Acquired</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    <?= $asset['date_acquired'] ? date('F d, Y', strtotime($asset['date_acquired'])) : '—' ?>
                                </p>
                            </div>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-shield-halved text-indigo-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Warranty Ends</p>
                                <?php if (!empty($asset['warranty_end'])): ?>
                                    <?php $expired = strtotime($asset['warranty_end']) < time(); ?>
                                    <p class="text-sm font-semibold <?= $expired ? 'text-red-600' : 'text-gray-800' ?> flex items-center gap-1.5 flex-wrap">
                                        <?= date('F d, Y', strtotime($asset['warranty_end'])) ?>
                                        <?php if ($expired): ?>
                                            <span class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full">Expired</span>
                                        <?php else: ?>
                                            <span class="text-xs bg-green-100 text-green-600 px-1.5 py-0.5 rounded-full">Active</span>
                                        <?php endif; ?>
                                    </p>
                                <?php else: ?>
                                    <p class="text-sm font-semibold text-gray-800">—</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Procurement Information -->
            <?php
            $hasProcurement = !empty($asset['supplier']) || !empty($asset['po_number']) || !empty($asset['invoice_number']) || !empty($asset['procurement_mode']) || !empty($asset['fund_source']);
            ?>
            <?php if ($hasProcurement): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-violet-500"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Procurement Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php if (!empty($asset['supplier'])): ?>
                    <div class="sm:col-span-2 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 border border-violet-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-store text-violet-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Supplier / Vendor</p>
                            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['supplier']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($asset['po_number'])): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-file-lines text-blue-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">PO Number</p>
                            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['po_number']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($asset['invoice_number'])): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-receipt text-blue-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Invoice Number</p>
                            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['invoice_number']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($asset['procurement_mode'])): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-gavel text-green-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Mode of Procurement</p>
                            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['procurement_mode']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($asset['fund_source'])): ?>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-yellow-50 border border-yellow-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-coins text-yellow-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Fund Source</p>
                            <p class="text-sm font-semibold text-gray-800"><?= esc($asset['fund_source']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Lifecycle Notes -->
            <?php if (!empty($asset['lifecycle'])): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-note-sticky text-purple-500"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Lifecycle / Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line"><?= esc($asset['lifecycle']) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Software & Operating System -->
            <?php
            $swList = !empty($asset['software_list']) ? json_decode($asset['software_list'], true) : [];
            $swList = is_array($swList) ? $swList : [];
            $hasSwOs = !empty($asset['operating_system']) || !empty($asset['software_installed']) || !empty($asset['os_license_key']) || !empty($asset['software_license']) || !empty($swList);
            ?>
            <?php if ($hasSwOs): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-laptop-code text-violet-500"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Software &amp; Operating System</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Operating System Block -->
                    <?php if (!empty($asset['operating_system'])): ?>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-brands fa-windows text-violet-300"></i> Operating System
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 rounded-xl p-4">
                            <div class="col-span-2 sm:col-span-1">
                                <p class="text-xs text-gray-400">OS Name</p>
                                <p class="text-sm font-semibold text-gray-800"><?= esc($asset['operating_system']) ?></p>
                            </div>
                            <?php if (!empty($asset['os_license_key'])): ?>
                            <div>
                                <p class="text-xs text-gray-400">License Key</p>
                                <p class="text-sm font-mono text-gray-800"><?= esc($asset['os_license_key']) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($asset['os_license_type'])): ?>
                            <div>
                                <p class="text-xs text-gray-400">License Type</p>
                                <p class="text-sm font-medium text-gray-800"><?= esc($asset['os_license_type']) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($asset['os_license_expiry'])): ?>
                            <div>
                                <p class="text-xs text-gray-400">License Expiry</p>
                                <p class="text-sm font-medium text-gray-800"><?= date('M d, Y', strtotime($asset['os_license_expiry'])) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($asset['os_last_updated'])): ?>
                            <div>
                                <p class="text-xs text-gray-400">Last Updated</p>
                                <p class="text-sm font-medium text-gray-800"><?= date('M d, Y', strtotime($asset['os_last_updated'])) ?></p>
                            </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-xs text-gray-400">Up-to-date</p>
                                <?php if (!empty($asset['os_is_updated'])): ?>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded-full ring-1 ring-green-200">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i> Yes
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                    <i class="fa-solid fa-circle-xmark text-[10px]"></i> No
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Software List (per-entry) -->
                    <?php if (!empty($swList)): ?>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-cubes text-violet-300"></i> Installed Software
                        </p>
                        <div class="space-y-3">
                        <?php foreach ($swList as $idx => $sw): ?>
                        <div class="bg-gray-50 rounded-xl p-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="col-span-2 sm:col-span-3">
                                <p class="text-xs text-gray-400">Software Name</p>
                                <p class="text-sm font-semibold text-gray-800"><?= esc($sw['name'] ?? '—') ?></p>
                            </div>
                            <?php if (!empty($sw['license_type'])): ?>
                            <div>
                                <p class="text-xs text-gray-400">License Type</p>
                                <p class="text-sm font-medium text-gray-800"><?= esc($sw['license_type']) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($sw['license_expiry'])): ?>
                            <div>
                                <p class="text-xs text-gray-400">License Expiry</p>
                                <p class="text-sm font-medium text-gray-800"><?= date('M d, Y', strtotime($sw['license_expiry'])) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($sw['last_updated'])): ?>
                            <div>
                                <p class="text-xs text-gray-400">Last Updated</p>
                                <p class="text-sm font-medium text-gray-800"><?= date('M d, Y', strtotime($sw['last_updated'])) ?></p>
                            </div>
                            <?php endif; ?>
                            <div>
                                <p class="text-xs text-gray-400">Up-to-date</p>
                                <?php if (!empty($sw['is_updated'])): ?>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded-full ring-1 ring-green-200">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i> Yes
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                    <i class="fa-solid fa-circle-xmark text-[10px]"></i> No
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($sw['notes'])): ?>
                            <div class="col-span-2 sm:col-span-3">
                                <p class="text-xs text-gray-400">License Key / Notes</p>
                                <p class="text-sm text-gray-700 whitespace-pre-line"><?= esc($sw['notes']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($asset['software_license'])): ?>
                    <div>
                        <p class="text-xs text-gray-400 mb-1 flex items-center gap-1">
                            <i class="fa-solid fa-file-contract text-gray-300"></i> Software License / Notes
                        </p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line"><?= esc($asset['software_license']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT: Sidebar -->
        <div class="space-y-5">

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Actions</h3>
                </div>
                <div class="p-4 space-y-2">
                          <a href="<?= site_url($routePrefix . "/assets/edit/{$asset['asset_id']}") ?>"
                       class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium text-sm transition">
                        <i class="fa-solid fa-pencil w-4 text-center"></i>
                        Edit Asset
                    </a>
                          <a href="<?= site_url($routePrefix . '/assets/create') ?>"
                       class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium text-sm transition">
                        <i class="fa-solid fa-copy w-4 text-center"></i>
                        Duplicate Asset
                    </a>
                          <a href="<?= site_url($routePrefix . "/assets/delete/{$asset['asset_id']}") ?>"
                       onclick="return confirm('Are you sure you want to delete this asset?')"
                       class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-red-50 hover:bg-red-100 text-red-700 font-medium text-sm transition">
                        <i class="fa-solid fa-trash w-4 text-center"></i>
                        Delete Asset
                    </a>
                          <a href="<?= site_url($routePrefix . '/assets') ?>"
                       class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-600 font-medium text-sm transition">
                        <i class="fa-solid fa-arrow-left w-4 text-center"></i>
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Asset Image -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-image text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Asset Image</h3>
                </div>
                <div class="p-4">
                    <?php if (!empty($asset['asset_image'])): ?>
                    <img src="<?= base_url('uploads/assets/' . esc($asset['asset_image'])) ?>"
                         alt="Asset Image"
                         class="w-full rounded-xl object-contain max-h-64 border border-gray-100 bg-gray-50">
                    <?php else: ?>
                    <div class="w-full h-36 rounded-xl bg-gray-50 border border-dashed border-gray-200 flex flex-col items-center justify-center gap-2">
                        <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                        <p class="text-xs text-gray-400">No image uploaded</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm">Record Info</h3>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <p class="text-xs text-gray-400">Date Added</p>
                        <p class="text-sm font-medium text-gray-700">
                            <?= $asset['created_at'] ? date('M d, Y h:i A', strtotime($asset['created_at'])) : '—' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Last Updated</p>
                        <p class="text-sm font-medium text-gray-700">
                            <?= $asset['updated_at'] ? date('M d, Y h:i A', strtotime($asset['updated_at'])) : '—' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Asset ID</p>
                        <p class="text-sm font-mono font-medium text-gray-700">#<?= $asset['asset_id'] ?></p>
                    </div>
                </div>
            </div>

            

        </div>

    </div>
</div>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
