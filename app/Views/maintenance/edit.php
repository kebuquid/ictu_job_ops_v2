<?php
$r = $record;

// Parse saved activities — separate standard items, custom items, and "Others"
$activityOptions = ['Repair','Installation','Cleaning','Inspection','Replacement','Calibration','Lubrication','Testing','Updating / Patching','Backup & Restore','Virus Removal','Configuration'];
$savedOthers = '';
$savedActList = array_map('trim', explode(',', $r['activities'] ?? ''));
$savedActivities       = [];   // matches a standard option
$savedCustomActivities = [];   // user-added custom labels
foreach ($savedActList as $sa) {
    if ($sa === '') continue;
    if (stripos($sa, 'Others:') === 0) {
        $savedOthers = trim(substr($sa, 7));
    } elseif (in_array($sa, $activityOptions)) {
        $savedActivities[] = $sa;
    } else {
        $savedCustomActivities[] = $sa;
    }
}

$jsGroupedAssets = [];
foreach (($groupedAssets ?? []) as $gid => $assets) {
    foreach ($assets as $a) {
        $jsGroupedAssets[$gid][] = [
            'asset_id'      => $a['asset_id'],
            'asset_tag'     => $a['asset_tag']     ?? '',
            'brand_model'   => $a['brand_model']   ?? '',
            'serial_number' => $a['serial_number'] ?? '',
            'category'      => $a['category']      ?? '',
        ];
    }
}

// Flat group list for JS (includes building/unit)
$jsGroups = [];
foreach (($groups ?? []) as $g) {
    $jsGroups[] = [
        'group_id'      => (int)$g['group_id'],
        'group_name'    => $g['group_name']    ?? '',
        'group_code'    => $g['group_code']    ?? '',
        'unit_name'     => $g['unit_name']     ?? '',
        'building_name' => $g['building_name'] ?? '',
    ];
}

$pageTitle    = 'Edit Maintenance Record';
$pageSubtitle = 'Update maintenance entry #' . $r['maintenance_id'];
ob_start();
?>

<div class="max-w-2xl mx-auto">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?= site_url('maintenance') ?>" class="hover:text-blue-600 transition">Maintenance</a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <span class="text-gray-700 font-medium">Edit #<?= $r['maintenance_id'] ?></span>
    </nav>

    <?php if (isset($validation) && $validation->getErrors()): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm flex gap-3 items-start">
        <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
        <ul class="list-disc list-inside space-y-0.5">
            <?php foreach ($validation->getErrors() as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Step Indicators -->
    <div class="flex items-center justify-between mb-8 relative">
        <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 z-0">
            <div id="progress-bar" class="h-full bg-blue-500 transition-all duration-500" style="width:0%"></div>
        </div>
        <?php
        $steps = [
            ['icon' => 'fa-layer-group',        'label' => 'Asset'],
            ['icon' => 'fa-screwdriver-wrench', 'label' => 'Details'],
            ['icon' => 'fa-clipboard-list',     'label' => 'Sign-off'],
            ['icon' => 'fa-eye',                'label' => 'Confirm'],
        ];
        foreach ($steps as $i => $step):
            $n = $i + 1;
        ?>
        <div class="relative z-10 flex flex-col items-center">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                <?= $n === 1 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-300 text-gray-400' ?>"
                id="step-circle-<?= $n ?>">
                <i class="fa-solid <?= $step['icon'] ?> text-xs"></i>
            </div>
            <span class="mt-2 text-xs font-medium <?= $n === 1 ? 'text-blue-600' : 'text-gray-400' ?> transition-colors duration-300"
                  id="step-label-<?= $n ?>"><?= $step['label'] ?></span>
        </div>
        <?php endforeach; ?>
    </div>

    <form action="<?= site_url('maintenance/update/' . $r['maintenance_id']) ?>" method="post" id="maint-form">
        <?= csrf_field() ?>
        <input type="hidden" name="activities" id="activities_hidden" value="<?= esc($r['activities'] ?? '') ?>">

        <!-- STEP 1: Asset Group & Asset -->
        <div class="step-panel" id="panel-1">

            <!-- Hidden fields for submission -->
            <input type="hidden" name="group_id" id="group_id_hidden" value="">
            <input type="hidden" name="asset_id" id="asset_id_hidden" value="">

            <!-- Select Asset Group & Asset (merged card) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-orange-500"></i>
                        <h3 class="font-semibold text-gray-800">Asset Group &amp; Asset <span class="text-red-500">*</span></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="selected-group-badge" class="hidden text-xs font-semibold bg-orange-50 text-orange-600 px-2.5 py-1 rounded-full"></span>
                        <button type="button" id="deselect-group-btn"
                                onclick="clearGroup()"
                                title="Change group"
                                class="hidden text-xs text-gray-400 hover:text-red-500 transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <span id="selected-asset-badge" class="text-xs font-semibold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full">None selected</span>
                    </div>
                </div>
                <div class="p-4">
                    <?php if (empty($groups)): ?>
                        <p class="text-sm text-gray-400 text-center py-4">
                            No asset groups found. <a href="<?= site_url('asset-groups/create') ?>" class="text-blue-500 hover:underline">Create one first &rarr;</a>
                        </p>
                    <?php else: ?>

                        <!-- Building & Org Unit filter row -->
                        <div id="building-filter-wrap" class="flex gap-2 mb-3">
                            <div class="flex-1">
                                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Building</label>
                                <select id="buildingFilter" onchange="onBuildingFilterChange()"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none bg-white">
                                    <option value="">All Buildings</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wide mb-1">Org Unit</label>
                                <select id="unitFilter" onchange="filterCurrent()"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-orange-400 outline-none bg-white">
                                    <option value="">All Org Units</option>
                                </select>
                            </div>
                        </div>

                        <!-- Search bar -->
                        <div class="mb-3">
                            <input type="text" id="mainSearch"
                                   placeholder="Search by group, code, or building..."
                                   oninput="filterCurrent()"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 outline-none">
                        </div>

                        <!-- Hint / no-results -->
                        <div id="search-hint" class="text-sm text-gray-400 text-center py-4">
                            <i class="fa-solid fa-magnifying-glass mb-1 block text-gray-300 text-lg"></i>
                            <span id="search-hint-text">Type to search for a group</span>
                        </div>
                        <div id="search-no-results" class="hidden text-sm text-gray-400 text-center py-4">
                            <i class="fa-solid fa-circle-xmark mb-1 block text-gray-300 text-lg"></i>
                            <span id="no-results-text">No groups found</span>
                        </div>

                        <!-- Group list -->
                        <div id="groupList" class="hidden max-h-48 overflow-y-auto divide-y divide-gray-50 border border-gray-100 rounded-xl">
                            <?php foreach ($groups as $g): ?>
                            <div class="group-row flex items-center gap-3 px-4 py-3 hover:bg-orange-50 cursor-pointer transition"
                                 data-group-id="<?= $g['group_id'] ?>"
                                 data-group-name="<?= esc($g['group_name']) ?>"
                                 data-group-code="<?= esc($g['group_code'] ?? '') ?>"
                                 data-building="<?= strtolower(esc($g['building_name'] ?? '')) ?>"
                                 data-unit="<?= esc($g['unit_name'] ?? '') ?>"
                                 data-search="<?= strtolower(esc($g['group_name'] . ' ' . ($g['group_code'] ?? '') . ' ' . ($g['building_name'] ?? '') . ' ' . ($g['unit_name'] ?? ''))) ?>"
                                 onclick="selectGroup(this)">
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-layer-group text-orange-500 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800"><?= esc($g['group_name']) ?></p>
                                    <?php if (!empty($g['building_name'])): ?>
                                    <p class="text-xs text-gray-400">
                                        <i class="fa-solid fa-building text-gray-300 mr-0.5"></i>
                                        <?= esc($g['building_name']) ?><?= !empty($g['unit_name']) ? ' &rsaquo; ' . esc($g['unit_name']) : '' ?>
                                    </p>
                                    <?php elseif (!empty($g['group_code'])): ?>
                                    <p class="text-xs text-gray-400"><?= esc($g['group_code']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <span id="group-check-<?= $g['group_id'] ?>" class="hidden text-green-500"><i class="fa-solid fa-circle-check"></i></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Asset list (shown after group picked) -->
                        <div id="assetList" class="hidden max-h-64 overflow-y-auto divide-y divide-gray-50 border border-gray-100 rounded-xl">
                            <!-- populated by JS -->
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="<?= site_url('maintenance') ?>" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="button" onclick="goStep(2)" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Next <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Details -->
        <div class="step-panel hidden" id="panel-2">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-screwdriver-wrench text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800">Maintenance Info</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 2 of 4</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Read-only location context (synced from selected group) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Building</label>
                        <div id="info-building" class="text-sm bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-gray-400 italic min-h-[42px]">&mdash;</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Org Unit / Office</label>
                        <div id="info-unit" class="text-sm bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-gray-400 italic min-h-[42px]">&mdash;</div>
                    </div>

                    <!-- Frequency -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Frequency</label>
                        <select name="frequency" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">&mdash; Select &mdash;</option>
                            <?php foreach (['Monthly','Quarterly','Semi-Annual','Annual'] as $f): ?>
                                <option value="<?= $f ?>" <?= ($r['frequency'] ?? '') === $f ? 'selected' : '' ?>><?= $f ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Maintenance Date -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Maintenance Date <span class="text-red-500">*</span></label>
                        <input type="date" name="maintenance_date" required value="<?= esc($r['maintenance_date'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Activities -->
                    <div class="sm:col-span-2">
                        <!-- Step A: Activity Selector -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-3">
                            <label class="block text-xs font-semibold text-gray-700 mb-3 flex items-center gap-1.5">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold">1</span>
                                Select Activities Performed
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="act-selector">
                                <?php foreach ($activityOptions as $act): ?>
                                <label class="act-pill flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition text-xs select-none">
                                    <input type="checkbox" class="act-selector-chk w-3.5 h-3.5 text-blue-600 rounded border-gray-300 flex-shrink-0 pointer-events-none"
                                           value="<?= esc($act) ?>" onchange="onActivitySelectorChange()"
                                           <?= in_array($act, $savedActivities) ? 'checked' : '' ?>>
                                    <span class="text-gray-700 leading-tight"><?= esc($act) ?></span>
                                </label>
                                <?php endforeach; ?>
                                <?php foreach ($savedCustomActivities as $ca): ?>
                                <label class="act-pill custom-act-pill flex items-center gap-2 px-3 py-2 rounded-xl border border-blue-200 bg-blue-50 hover:border-blue-400 cursor-pointer transition text-xs select-none relative">
                                    <input type="checkbox" class="act-selector-chk w-3.5 h-3.5 text-blue-600 rounded border-gray-300 flex-shrink-0 pointer-events-none"
                                           value="<?= esc($ca) ?>" onchange="onActivitySelectorChange()" checked>
                                    <span class="text-gray-700 leading-tight flex-1"><?= esc($ca) ?></span>
                                    <button type="button" onclick="removeCustomActivity(this)" class="ml-auto text-gray-400 hover:text-red-500 transition leading-none" tabindex="-1">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                </label>
                                <?php endforeach; ?>
                                <label class="act-pill flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition text-xs select-none">
                                    <input type="checkbox" id="act_others_chk" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300 flex-shrink-0 pointer-events-none" onchange="toggleOthers()"
                                           <?= $savedOthers !== '' ? 'checked' : '' ?>>
                                    <span class="text-gray-700">Others</span>
                                </label>
                            </div>
                            <!-- Add Custom Activity -->
                            <div class="flex gap-2 mt-3">
                                <input type="text" id="custom-act-input" maxlength="60"
                                       placeholder="Add custom activity..."
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomActivity();}"
                                       class="flex-1 border border-gray-200 rounded-xl px-3 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 outline-none" />
                                <button type="button" onclick="addCustomActivity()"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 active:bg-blue-800 transition">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Add
                                </button>
                            </div>

                            <div id="others_box" class="<?= $savedOthers !== '' ? '' : 'hidden' ?> mt-3">
                                <textarea id="act_others_text" rows="3"
                                    placeholder="• Activity 1&#10;• Activity 2"
                                    onfocus="othersInit()"
                                    onkeydown="othersBullet(event)"
                                    oninput="syncActivities(); buildActivityTable();"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-y font-mono"><?= esc($savedOthers) ?></textarea>
                                <p class="text-xs text-gray-400 mt-1">Each line becomes a bullet item.</p>
                            </div>
                        </div>

                        <!-- Step B: Per-asset Checklist Table -->
                        <div id="act-checklist-section" class="hidden">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold">2</span>
                                    Per-equipment Checklist
                                    <span class="font-normal text-gray-400">&mdash; uncheck for equipment that did not receive the activity</span>
                                </label>
                                <button type="button" onclick="toggleCkTable()" class="flex items-center gap-1 text-xs text-gray-400 hover:text-gray-600 transition flex-shrink-0">
                                    <i id="ck-chevron" class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                                    <span id="ck-toggle-label">Collapse</span>
                                </button>
                            </div>
                            <div id="ck-table-body">
                                <div id="act-table-wrapper" class="overflow-x-auto rounded-xl border border-gray-200 min-h-[56px]">
                                    <div class="text-sm text-gray-400 text-center py-4">Loading&hellip;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="goStep(1)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="button" onclick="goReview()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Review <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: Sign-off -->
        <div class="step-panel hidden" id="panel-3">

            <!-- Conducted & Verified -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-user-check text-indigo-500"></i>
                    <h3 class="font-semibold text-gray-800">Conducted &amp; Verified By</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 3 of 4</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Conducted By</label>
                        <input type="text" name="conducted_by" value="<?= esc($r['conducted_by'] ?? '') ?>" placeholder="Technician name"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date Conducted</label>
                        <input type="date" name="conducted_date" value="<?= esc($r['conducted_date'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Verified By</label>
                        <input type="text" name="verified_by" value="<?= esc($r['verified_by'] ?? '') ?>" placeholder="Supervisor name"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date Verified</label>
                        <input type="date" name="verified_date" value="<?= esc($r['verified_date'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Remarks</label>
                        <textarea name="remarks" rows="2" placeholder="General remarks / observations&hellip;"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= esc($r['remarks'] ?? '') ?></textarea>
                        <div id="kw-tip" class="hidden"></div>
                    </div>
                </div>
            </div>

            <!-- Corrective Action & Responsible Person -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
                    <h3 class="font-semibold text-gray-800">Corrective Action &amp; Responsible Person</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Corrective Action Taken</label>
                        <textarea name="corrective_action" rows="2" placeholder="Describe corrective actions taken&hellip;"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= esc($r['corrective_action'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Corrective Action Date</label>
                        <input type="date" name="corrective_date" value="<?= esc($r['corrective_date'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Responsible Person</label>
                        <input type="text" name="responsible_person" value="<?= esc($r['responsible_person'] ?? '') ?>" placeholder="Person responsible"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Responsible Date</label>
                        <input type="date" name="responsible_date" value="<?= esc($r['responsible_date'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Remarks</label>
                        <textarea name="responsible_remarks" rows="2" placeholder="Additional remarks&hellip;"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= esc($r['responsible_remarks'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="goStep(2)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="button" onclick="goStep3Review()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Next <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 4: Review & Confirm -->
        <div class="step-panel hidden" id="panel-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-purple-500"></i>
                    <h3 class="font-semibold text-gray-800">Review &amp; Confirm</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 4 of 4</span>
                </div>
                <div class="p-6 space-y-5">
                    <p class="text-sm text-gray-500">Please review all details before saving changes.</p>

                    <!-- Asset -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-layer-group text-orange-400"></i> Asset
                        </h4>
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                            <div><span class="text-xs text-gray-400">Group</span><p class="text-sm font-medium text-gray-800" id="rv-group_id"></p></div>
                            <div><span class="text-xs text-gray-400">Building</span><p class="text-sm font-medium text-gray-800" id="rv-building"></p></div>
                            <div><span class="text-xs text-gray-400">Org Unit / Office</span><p class="text-sm font-medium text-gray-800" id="rv-unit"></p></div>
                            <div><span class="text-xs text-gray-400">Selected Asset</span><p class="text-sm font-medium text-gray-800" id="rv-asset_id"></p></div>
                        </div>
                    </div>

                    <!-- Maintenance Info -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-screwdriver-wrench text-blue-400"></i> Maintenance Info
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Frequency</span><p class="text-sm font-medium text-gray-800" id="rv-frequency"></p></div>
                            <div><span class="text-xs text-gray-400">Maintenance Date</span><p class="text-sm font-medium text-gray-800" id="rv-maintenance_date"></p></div>
                            <div class="col-span-2">
                                <span class="text-xs text-gray-400">Activities Performed</span>
                                <div id="rv-activities" class="flex flex-wrap gap-2 mt-1.5"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks on Activities -->
                    <div id="rv-others-block" class="hidden">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-comment-dots text-amber-400"></i> Remarks on Activities
                        </h4>
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                            <ul id="rv-others-list" class="flex flex-wrap gap-2"></ul>
                        </div>
                    </div>

                    <!-- Per-Equipment Checklist -->
                    <div id="rv-checklist" class="hidden">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-clipboard-check text-teal-400"></i> Per-Equipment Checklist
                        </h4>
                        <div class="bg-gray-50 rounded-xl p-4 overflow-x-auto">
                            <div id="rv-checklist-table"></div>
                        </div>
                    </div>

                    <!-- Conducted & Verified -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-check text-indigo-400"></i> Conducted &amp; Verified
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Conducted By</span><p class="text-sm font-medium text-gray-800" id="rv-conducted_by"></p></div>
                            <div><span class="text-xs text-gray-400">Date Conducted</span><p class="text-sm font-medium text-gray-800" id="rv-conducted_date"></p></div>
                            <div><span class="text-xs text-gray-400">Verified By</span><p class="text-sm font-medium text-gray-800" id="rv-verified_by"></p></div>
                            <div><span class="text-xs text-gray-400">Date Verified</span><p class="text-sm font-medium text-gray-800" id="rv-verified_date"></p></div>
                            <div class="col-span-2"><span class="text-xs text-gray-400">Remarks</span><p class="text-sm font-medium text-gray-800 whitespace-pre-line" id="rv-remarks"></p></div>
                        </div>
                    </div>

                    <!-- Corrective Action & Responsible -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-triangle-exclamation text-yellow-400"></i> Corrective Action &amp; Responsible
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div class="col-span-2"><span class="text-xs text-gray-400">Corrective Action Taken</span><p class="text-sm font-medium text-gray-800 whitespace-pre-line" id="rv-corrective_action"></p></div>
                            <div><span class="text-xs text-gray-400">Corrective Date</span><p class="text-sm font-medium text-gray-800" id="rv-corrective_date"></p></div>
                            <div><span class="text-xs text-gray-400">Responsible Person</span><p class="text-sm font-medium text-gray-800" id="rv-responsible_person"></p></div>
                            <div><span class="text-xs text-gray-400">Responsible Date</span><p class="text-sm font-medium text-gray-800" id="rv-responsible_date"></p></div>
                            <div class="col-span-2"><span class="text-xs text-gray-400">Additional Remarks</span><p class="text-sm font-medium text-gray-800 whitespace-pre-line" id="rv-responsible_remarks"></p></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <button type="button" onclick="goStep(3)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>

<script>
const groupedAssets = <?= json_encode($jsGroupedAssets) ?>;
const allGroups     = <?= json_encode($jsGroups) ?>;
const savedGroupId  = <?= (int)($r['group_id'] ?? 0) ?>;
const savedAssetId  = <?= (int)($r['asset_id']  ?? 0) ?>;

let currentStep = 1;
const totalSteps = 4;

// ── Checklist state preservation ─────────────────────────
let ckState = {};

function saveCkState() {
    ckState = {};
    document.querySelectorAll('#act-table-wrapper .act-check').forEach(function(cb) {
        const aid = cb.dataset.asset;
        const act = cb.dataset.activity;
        if (!ckState[aid]) ckState[aid] = {};
        const row      = cb.closest('tr');
        const remarkEl = row ? row.querySelector('.act-remark') : null;
        ckState[aid][act] = { checked: cb.checked, remark: remarkEl ? remarkEl.value : '' };
    });
}

function restoreCkState() {
    if (!Object.keys(ckState).length) return;
    document.querySelectorAll('#act-table-wrapper .act-check').forEach(function(cb) {
        const aid   = cb.dataset.asset;
        const act   = cb.dataset.activity;
        const saved = ckState[aid] && ckState[aid][act];
        if (!saved) return;
        cb.checked = saved.checked;
        const row      = cb.closest('tr');
        const remarkEl = row ? row.querySelector('.act-remark') : null;
        if (remarkEl && saved.remark) remarkEl.value = saved.remark;
    });
}

// ── Category colour helper ────────────────────────────────
function catClass(cat) {
    const map = {
        'IT Equipment':     'bg-blue-50 text-blue-600',
        'Furniture':        'bg-yellow-50 text-yellow-700',
        'Office Equipment': 'bg-purple-50 text-purple-600',
        'Machinery':        'bg-red-50 text-red-600'
    };
    return map[cat] || 'bg-gray-100 text-gray-600';
}

// ── Error helpers ─────────────────────────────────────────
function clearMaintErrors(panelId) {
    document.querySelectorAll('#' + panelId + ' .field-error').forEach(e => e.remove());
    document.querySelectorAll('#' + panelId + ' .input-error').forEach(el => {
        el.classList.remove('border-red-400', 'ring-2', 'ring-red-200', 'input-error');
    });
}

function markError(el, label) {
    el.classList.add('border-red-400', 'ring-2', 'ring-red-200', 'input-error');
    const msg = document.createElement('p');
    msg.className = 'field-error text-xs text-red-500 mt-1';
    msg.textContent = label + ' is required.';
    el.parentNode.appendChild(msg);
}

// ── Validation ────────────────────────────────────────────
function validateStep1() {
    clearMaintErrors('panel-1');
    const gid = document.getElementById('group_id_hidden').value;
    if (!gid) {
        const inp = document.getElementById('mainSearch');
        if (inp) { inp.classList.add('border-red-400','ring-2','ring-red-200','input-error'); }
        let errMsg = document.getElementById('group-field-error');
        if (!errMsg) {
            errMsg = document.createElement('p');
            errMsg.id = 'group-field-error';
            errMsg.className = 'field-error text-xs text-red-500 mt-2';
            errMsg.textContent = 'Please select an Asset Group.';
            inp && inp.parentNode.appendChild(errMsg);
        }
        inp && inp.focus();
        return false;
    }
    return true;
}

function validateStep2() {
    clearMaintErrors('panel-2');
    const fields = [
        { sel: '[name="frequency"]',        label: 'Frequency' },
        { sel: '[name="maintenance_date"]', label: 'Maintenance Date' },
    ];
    let valid = true;
    let firstBad = null;
    fields.forEach(({ sel, label }) => {
        const el = document.querySelector(sel);
        if (!el) return;
        const empty = (el.tagName === 'SELECT') ? !el.value : !el.value.trim();
        if (empty) {
            valid = false;
            markError(el, label);
            if (!firstBad) firstBad = el;
        }
    });
    const selectedActs = document.querySelectorAll('#act-selector .act-selector-chk:checked');
    const othersChk    = document.getElementById('act_others_chk');
    if (selectedActs.length === 0 && !othersChk?.checked) {
        valid = false;
        const actWrap = document.getElementById('act-selector');
        if (actWrap && !actWrap.parentNode.querySelector('.act-field-error')) {
            const msg = document.createElement('p');
            msg.className = 'act-field-error field-error text-xs text-red-500 mt-1';
            msg.textContent = 'Please select at least one activity.';
            actWrap.parentNode.appendChild(msg);
            if (!firstBad) firstBad = actWrap;
        }
    }
    if (firstBad) firstBad.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return valid;
}

function validateStep3() {
    clearMaintErrors('panel-3');
    const fields = [
        { sel: '[name="conducted_by"]',        label: 'Conducted By' },
        { sel: '[name="conducted_date"]',      label: 'Date Conducted' },
        { sel: '[name="verified_by"]',         label: 'Verified By' },
        { sel: '[name="verified_date"]',       label: 'Date Verified' },
        { sel: '[name="remarks"]',             label: 'Remarks' },
        { sel: '[name="corrective_action"]',   label: 'Corrective Action' },
        { sel: '[name="corrective_date"]',     label: 'Corrective Date' },
        { sel: '[name="responsible_person"]',  label: 'Responsible Person' },
        { sel: '[name="responsible_date"]',    label: 'Responsible Date' },
        { sel: '[name="responsible_remarks"]', label: 'Responsible Remarks' },
    ];
    let valid = true;
    let firstBad = null;
    fields.forEach(({ sel, label }) => {
        const el = document.querySelector(sel);
        if (!el) return;
        const empty = (el.tagName === 'SELECT') ? !el.value : !el.value.trim();
        if (empty) {
            valid = false;
            markError(el, label);
            if (!firstBad) firstBad = el;
        }
    });
    if (firstBad) firstBad.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return valid;
}

// ── Step navigation ───────────────────────────────────────
function goStep(n) {
    if (n > currentStep) {
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !validateStep2()) return;
        if (currentStep === 3 && !validateStep3()) return;
    } else {
        clearMaintErrors('panel-' + currentStep);
    }
    if (currentStep === 2) saveCkState();
    document.getElementById('panel-' + currentStep).classList.add('hidden');
    document.getElementById('panel-' + n).classList.remove('hidden');
    if (n === 2) { updatePillStyles(); buildActivityTable(); restoreCkState(); }
    updateIndicators(n);
    currentStep = n;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function goStep3Review() {
    if (!validateStep3()) return;

    function fv(nm) {
        const el = document.querySelector('[name="' + nm + '"]');
        if (!el) return '';
        return el.value.trim() || '';
    }
    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    ['conducted_by','conducted_date','verified_by','verified_date','remarks',
     'corrective_action','corrective_date',
     'responsible_person','responsible_date','responsible_remarks'
    ].forEach(f => setText('rv-' + f, fv(f)));

    goStep(4);
}

function goReview() {
    if (!validateStep2()) return;
    syncActivities();

    function fv(nm) {
        const el = document.querySelector('[name="' + nm + '"]');
        if (!el) return '';
        if (el.tagName === 'SELECT') return el.options[el.selectedIndex]?.text || '';
        return el.value.trim() || '';
    }
    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    ['frequency','maintenance_date'].forEach(f => setText('rv-' + f, fv(f)));

    // Build activity badge pills
    const acts          = document.getElementById('activities_hidden')?.value || '';
    const rvActs        = document.getElementById('rv-activities');
    const rvOthersBlock = document.getElementById('rv-others-block');
    const rvOthersList  = document.getElementById('rv-others-list');
    const regularActs   = acts.split(',').map(a => a.trim()).filter(a => a && !a.startsWith('Others'));

    if (rvActs) {
        rvActs.innerHTML = '';
        if (regularActs.length) {
            regularActs.forEach(function(act) {
                const span = document.createElement('span');
                span.className = 'inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full';
                span.innerHTML = '<i class="fa-solid fa-circle-check text-[10px]"></i>' + act;
                rvActs.appendChild(span);
            });
        } else if (!document.getElementById('act_others_chk')?.checked) {
            rvActs.innerHTML = '<span class="text-sm text-gray-400">None selected</span>';
        }
    }

    // Others / Remarks on Activities
    const othersChk  = document.getElementById('act_others_chk');
    const othersText = document.getElementById('act_others_text');
    if (rvOthersBlock && rvOthersList) {
        const othersChecked = othersChk && othersChk.checked;
        const rawOthers     = othersText ? othersText.value.trim() : '';
        if (othersChecked || rawOthers) {
            const lines = rawOthers.split('\n')
                .map(l => l.replace(/^[•\-]\s*/, '').trim()).filter(Boolean);
            rvOthersList.innerHTML = lines.length
                ? lines.map(l => '<li class="inline-flex items-center gap-1.5 text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-full"><i class="fa-solid fa-pen-to-square text-[10px]"></i>' + esc(l) + '</li>').join('')
                : '<li class="text-xs text-gray-400 italic">No remarks entered</li>';
            rvOthersBlock.classList.remove('hidden');
        } else {
            rvOthersBlock.classList.add('hidden');
            rvOthersList.innerHTML = '';
        }
    }

    setText('rv-group_id', window._selectedGroupName || '');
    setText('rv-building', window._selectedBuilding  || '\u2014');
    setText('rv-unit',     window._selectedUnit      || '\u2014');

    // Sync selected asset hidden input + review label
    const selectedRow = document.querySelector('#assetList .asset-row.selected');
    const assetHidden = document.getElementById('asset_id_hidden');
    if (selectedRow) {
        if (assetHidden) assetHidden.value = selectedRow.dataset.assetId;
        setText('rv-asset_id', selectedRow.dataset.assetLabel.split(' \u2014 ')[0]);
    } else {
        if (assetHidden) assetHidden.value = '';
        setText('rv-asset_id', 'No specific asset');
    }

    // Build read-only per-equipment checklist for review
    saveCkState();
    const rvCkWrap  = document.getElementById('rv-checklist');
    const rvCkTable = document.getElementById('rv-checklist-table');
    if (rvCkWrap && rvCkTable) {
        const selRow = document.querySelector('#assetList .asset-row.selected');
        if (selRow && regularActs.length) {
            let t = '<table class="w-full text-xs border-collapse">';
            t += '<thead><tr>';
            t += '<th class="bg-blue-700 text-white text-left px-3 py-2 border border-blue-600 font-semibold">Equipment</th>';
            regularActs.forEach(function(a) {
                t += '<th class="bg-blue-700 text-white text-center px-2 py-2 border border-blue-600 font-semibold">' + esc(a) + '</th>';
            });
            t += '<th class="bg-blue-700 text-white text-center px-2 py-2 border border-blue-600 font-semibold min-w-[120px]">Remarks</th>';
            t += '</tr></thead><tbody><tr class="bg-white">';
            const aid   = selRow.dataset.assetId;
            const label = selRow.dataset.assetLabel.split(' \u2014 ')[0];
            t += '<td class="px-3 py-2 border border-gray-200 font-medium text-gray-800">' + esc(label) + '</td>';
            const assetState = ckState[aid] || {};
            regularActs.forEach(function(a) {
                const s       = assetState[a];
                const checked = s ? s.checked : true;
                t += checked
                    ? '<td class="text-center px-2 py-2 border border-gray-200"><span class="text-green-600 font-bold"><i class="fa-solid fa-circle-check"></i></span></td>'
                    : '<td class="text-center px-2 py-2 border border-gray-200"><span class="text-red-400"><i class="fa-solid fa-circle-xmark"></i></span></td>';
            });
            const allRemarks = [];
            Object.keys(assetState).forEach(function(k) { if (assetState[k].remark) allRemarks.push(assetState[k].remark); });
            t += '<td class="px-2 py-2 border border-gray-200 text-gray-600">' + esc(allRemarks.join('; ')) + '</td>';
            t += '</tr></tbody></table>';
            rvCkTable.innerHTML = t;
            rvCkWrap.classList.remove('hidden');
        } else {
            rvCkWrap.classList.add('hidden');
            rvCkTable.innerHTML = '';
        }
    }

    goStep(3);
}

// ── Step indicators ───────────────────────────────────────
function updateIndicators(n) {
    const icons = ['fa-layer-group','fa-screwdriver-wrench','fa-clipboard-list','fa-eye'];
    for (let i = 1; i <= totalSteps; i++) {
        const circle = document.getElementById('step-circle-' + i);
        const label  = document.getElementById('step-label-' + i);
        if (i < n) {
            circle.className = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-green-500 border-green-500 text-white';
            circle.innerHTML = '<i class="fa-solid fa-check text-xs"></i>';
            label.className  = 'mt-2 text-xs font-medium text-green-600 transition-colors duration-300';
        } else if (i === n) {
            circle.className = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-blue-600 border-blue-600 text-white';
            circle.innerHTML = '<i class="fa-solid ' + icons[i-1] + ' text-xs"></i>';
            label.className  = 'mt-2 text-xs font-medium text-blue-600 transition-colors duration-300';
        } else {
            circle.className = 'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 bg-white border-gray-300 text-gray-400';
            circle.innerHTML = '<i class="fa-solid ' + icons[i-1] + ' text-xs"></i>';
            label.className  = 'mt-2 text-xs font-medium text-gray-400 transition-colors duration-300';
        }
    }
    document.getElementById('progress-bar').style.width = ((n - 1) / (totalSteps - 1) * 100) + '%';
}

// ── Group / Asset search ──────────────────────────────────
let searchMode = 'group'; // 'group' | 'asset'

function filterCurrent() {
    if (searchMode === 'group') filterGroups();
    else filterAssets();
}

function populateBuildingFilter() {
    const bSel = document.getElementById('buildingFilter');
    const uSel = document.getElementById('unitFilter');
    if (!bSel || !uSel) return;
    const buildings = [...new Set(allGroups.map(g => g.building_name).filter(Boolean))].sort();
    buildings.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b;
        opt.textContent = b;
        bSel.appendChild(opt);
    });
    repopulateUnitFilter('');
}

function repopulateUnitFilter(building) {
    const uSel = document.getElementById('unitFilter');
    if (!uSel) return;
    uSel.innerHTML = '<option value="">All Org Units</option>';
    const units = [...new Set(
        allGroups
            .filter(g => !building || g.building_name === building)
            .map(g => g.unit_name)
            .filter(Boolean)
    )].sort();
    units.forEach(u => {
        const opt = document.createElement('option');
        opt.value = u;
        opt.textContent = u;
        uSel.appendChild(opt);
    });
}

function onBuildingFilterChange() {
    const bld = document.getElementById('buildingFilter').value;
    repopulateUnitFilter(bld);
    filterCurrent();
}

function filterGroups() {
    const q       = document.getElementById('mainSearch').value.trim().toLowerCase();
    const bFilter = document.getElementById('buildingFilter')?.value || '';
    const uFilter = document.getElementById('unitFilter')?.value    || '';
    const hint    = document.getElementById('search-hint');
    const noRes   = document.getElementById('search-no-results');
    const list    = document.getElementById('groupList');
    if (!q && !bFilter && !uFilter) {
        list.classList.add('hidden'); hint.classList.remove('hidden'); noRes.classList.add('hidden'); return;
    }
    let visible = 0;
    document.querySelectorAll('#groupList .group-row').forEach(row => {
        const matchQ = !q      || row.dataset.search.includes(q);
        const matchB = !bFilter || row.dataset.building === bFilter.toLowerCase();
        const matchU = !uFilter || (row.dataset.unit || '') === uFilter;
        const match  = matchQ && matchB && matchU;
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    if (visible > 0) { list.classList.remove('hidden'); hint.classList.add('hidden'); noRes.classList.add('hidden'); }
    else             { list.classList.add('hidden');    hint.classList.add('hidden'); noRes.classList.remove('hidden'); }
}

function clearGroup() {
    document.getElementById('group_id_hidden').value = '';
    document.getElementById('asset_id_hidden').value = '';
    window._selectedGroupName = '';

    const badge = document.getElementById('selected-group-badge');
    badge.textContent = ''; badge.classList.add('hidden');
    document.getElementById('deselect-group-btn').classList.add('hidden');
    document.getElementById('selected-asset-badge').textContent = 'None selected';
    document.getElementById('assetList').innerHTML = '';
    document.getElementById('assetList').classList.add('hidden');

    document.querySelectorAll('#groupList .group-row').forEach(r => {
        r.classList.remove('bg-orange-50','ring-1','ring-orange-300');
        const chk = document.getElementById('group-check-' + r.dataset.groupId);
        if (chk) chk.classList.add('hidden');
    });

    window._selectedBuilding = '';
    window._selectedUnit     = '';
    const bldEl  = document.getElementById('info-building');
    const unitEl = document.getElementById('info-unit');
    if (bldEl)  { bldEl.textContent  = '\u2014'; bldEl.classList.add('text-gray-400','italic');  bldEl.classList.remove('text-gray-800'); }
    if (unitEl) { unitEl.textContent = '\u2014'; unitEl.classList.add('text-gray-400','italic'); unitEl.classList.remove('text-gray-800'); }

    searchMode = 'group';
    const inp = document.getElementById('mainSearch');
    inp.value = '';
    inp.placeholder = 'Search by group, code, or building...';

    const bfSel  = document.getElementById('buildingFilter');
    const uSel   = document.getElementById('unitFilter');
    const bfWrap = document.getElementById('building-filter-wrap');
    if (bfSel)  bfSel.value = '';
    if (uSel)   { repopulateUnitFilter(''); uSel.value = ''; }
    if (bfWrap) bfWrap.classList.remove('hidden');

    document.getElementById('groupList').classList.add('hidden');
    document.getElementById('search-hint').classList.remove('hidden');
    document.getElementById('search-hint-text').textContent = 'Type to search for a group';
    document.getElementById('no-results-text').textContent  = 'No groups found';
    document.getElementById('search-no-results').classList.add('hidden');
}

function selectGroup(row) {
    const gid  = row.dataset.groupId;
    const name = row.dataset.groupName;
    const code = row.dataset.groupCode;

    document.getElementById('group_id_hidden').value = gid;
    window._selectedGroupName = name + (code ? ' (' + code + ')' : '');

    const gdata = allGroups.find(function(g) { return g.group_id === parseInt(gid); });
    window._selectedBuilding = (gdata && gdata.building_name) ? gdata.building_name : '';
    window._selectedUnit     = (gdata && gdata.unit_name)     ? gdata.unit_name     : '';
    const bldEl  = document.getElementById('info-building');
    const unitEl = document.getElementById('info-unit');
    if (bldEl)  { bldEl.textContent  = window._selectedBuilding || '\u2014'; bldEl.classList.toggle('text-gray-400', !window._selectedBuilding);  bldEl.classList.toggle('italic', !window._selectedBuilding);  bldEl.classList.toggle('text-gray-800', !!window._selectedBuilding); }
    if (unitEl) { unitEl.textContent = window._selectedUnit    || '\u2014'; unitEl.classList.toggle('text-gray-400', !window._selectedUnit); unitEl.classList.toggle('italic', !window._selectedUnit); unitEl.classList.toggle('text-gray-800', !!window._selectedUnit); }

    document.querySelectorAll('#groupList .group-row').forEach(r => {
        r.classList.remove('bg-orange-50','ring-1','ring-orange-300');
        const chk = document.getElementById('group-check-' + r.dataset.groupId);
        if (chk) chk.classList.add('hidden');
    });
    row.classList.add('bg-orange-50','ring-1','ring-orange-300');
    const chk = document.getElementById('group-check-' + gid);
    if (chk) chk.classList.remove('hidden');

    const badge = document.getElementById('selected-group-badge');
    badge.textContent = name; badge.classList.remove('hidden');
    document.getElementById('deselect-group-btn').classList.remove('hidden');

    // Clear previous asset selection
    document.getElementById('asset_id_hidden').value = '';
    document.getElementById('selected-asset-badge').textContent = 'None selected';

    buildAssetList(parseInt(gid), null);

    searchMode = 'asset';
    const inp = document.getElementById('mainSearch');
    inp.value = '';
    inp.placeholder = 'Search asset by tag, model or serial number...';

    const bfWrap = document.getElementById('building-filter-wrap');
    if (bfWrap) bfWrap.classList.add('hidden');

    document.getElementById('groupList').classList.add('hidden');
    document.getElementById('search-hint').classList.add('hidden');
    document.getElementById('search-no-results').classList.add('hidden');
}

function buildAssetList(gid, preselectId) {
    const assets = groupedAssets[gid] || [];
    const list   = document.getElementById('assetList');
    list.innerHTML = '';

    if (assets.length === 0) {
        list.innerHTML = '<div class="text-sm text-gray-400 text-center py-4">No assets in this group.</div>';
    } else {
        assets.forEach(function(a) {
            const row = document.createElement('div');
            row.className = 'asset-row flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition';
            row.dataset.search     = (a.asset_tag + ' ' + a.brand_model + ' ' + a.serial_number + ' ' + (a.category || '')).toLowerCase();
            row.dataset.assetId    = a.asset_id;
            row.dataset.assetLabel = a.asset_tag + ' \u2014 ' + (a.brand_model || '') + (a.serial_number ? ' (S/N: ' + a.serial_number + ')' : '');
            row.onclick = function() { selectAsset(this); };
            const cat = a.category || '';
            row.innerHTML =
                '<div class="w-4 h-4 flex-shrink-0 rounded-full border-2 border-gray-300 asset-radio flex items-center justify-center transition"></div>'
                + '<div class="flex-1 min-w-0">'
                + '<p class="text-sm font-semibold text-gray-800">' + esc(a.asset_tag) + '</p>'
                + '<p class="text-xs text-gray-500 truncate">' + esc(a.brand_model) + (a.serial_number ? ' \u2014 S/N: ' + esc(a.serial_number) : '') + '</p>'
                + '</div>'
                + '<span class="text-xs px-2 py-0.5 rounded-full ' + catClass(cat) + '">' + esc(cat) + '</span>';

            if (preselectId && a.asset_id == preselectId) {
                row.classList.add('selected','bg-blue-50');
                const radio = row.querySelector('.asset-radio');
                if (radio) { radio.classList.remove('border-gray-300'); radio.classList.add('border-blue-500','bg-blue-500'); radio.innerHTML = '<div class="w-2 h-2 rounded-full bg-white"></div>'; }
                document.getElementById('asset_id_hidden').value = a.asset_id;
                document.getElementById('selected-asset-badge').textContent = a.asset_tag;
            }
            list.appendChild(row);
        });
    }
    list.classList.remove('hidden');
}

function selectAsset(row) {
    const wasSelected = row.classList.contains('selected');
    // Deselect all (single-select behaviour)
    document.querySelectorAll('#assetList .asset-row').forEach(function(r) {
        r.classList.remove('selected','bg-blue-50');
        const radio = r.querySelector('.asset-radio');
        if (radio) { radio.classList.remove('border-blue-500','bg-blue-500'); radio.innerHTML = ''; radio.classList.add('border-gray-300'); }
    });
    if (!wasSelected) {
        row.classList.add('selected','bg-blue-50');
        const radio = row.querySelector('.asset-radio');
        if (radio) { radio.classList.remove('border-gray-300'); radio.classList.add('border-blue-500','bg-blue-500'); radio.innerHTML = '<div class="w-2 h-2 rounded-full bg-white"></div>'; }
        document.getElementById('asset_id_hidden').value = row.dataset.assetId;
        document.getElementById('selected-asset-badge').textContent = row.dataset.assetLabel.split(' \u2014 ')[0];
    } else {
        document.getElementById('asset_id_hidden').value = '';
        document.getElementById('selected-asset-badge').textContent = 'None selected';
    }
    buildActivityTable();
}

function filterAssets() {
    const q     = document.getElementById('mainSearch').value.trim().toLowerCase();
    const list  = document.getElementById('assetList');
    const hint  = document.getElementById('search-hint');
    const noRes = document.getElementById('search-no-results');
    if (!q) {
        document.querySelectorAll('#assetList .asset-row').forEach(r => r.style.display = '');
        list.classList.remove('hidden'); hint.classList.add('hidden'); noRes.classList.add('hidden');
        return;
    }
    let visible = 0;
    document.querySelectorAll('#assetList .asset-row').forEach(function(row) {
        const match = row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    if (visible > 0) { list.classList.remove('hidden'); hint.classList.add('hidden'); noRes.classList.add('hidden'); }
    else             { list.classList.add('hidden');    hint.classList.add('hidden'); noRes.classList.remove('hidden'); }
}

// ── Activity pill + checklist ─────────────────────────────
function onActivitySelectorChange() {
    updatePillStyles();
    buildActivityTable();
    syncActivities();
}

function updatePillStyles() {
    document.querySelectorAll('#act-selector .act-pill').forEach(function(pill) {
        const chk  = pill.querySelector('input');
        const span = pill.querySelector('span');
        if (chk && chk.checked) {
            pill.classList.add('border-blue-400','bg-blue-50');
            pill.classList.remove('border-gray-200','bg-white');
            if (span) { span.classList.add('text-blue-700','font-semibold'); span.classList.remove('text-gray-700'); }
        } else {
            pill.classList.remove('border-blue-400','bg-blue-50');
            pill.classList.add('border-gray-200','bg-white');
            if (span) { span.classList.remove('text-blue-700','font-semibold'); span.classList.add('text-gray-700'); }
        }
    });
}

let ckTableOpen = true;

function toggleCkTable() {
    ckTableOpen = !ckTableOpen;
    const body    = document.getElementById('ck-table-body');
    const chevron = document.getElementById('ck-chevron');
    const lbl     = document.getElementById('ck-toggle-label');
    if (body)    body.style.display      = ckTableOpen ? '' : 'none';
    if (chevron) chevron.style.transform = ckTableOpen ? 'rotate(0deg)' : 'rotate(-90deg)';
    if (lbl)     lbl.textContent         = ckTableOpen ? 'Collapse' : 'Expand';
}

function buildActivityTable() {
    const wrapper  = document.getElementById('act-table-wrapper');
    const section  = document.getElementById('act-checklist-section');
    if (!wrapper || !section) return;

    ckTableOpen = true;
    const ckBody = document.getElementById('ck-table-body');
    const ckChev = document.getElementById('ck-chevron');
    const ckLbl  = document.getElementById('ck-toggle-label');
    if (ckBody) ckBody.style.display = '';
    if (ckChev) ckChev.style.transform = 'rotate(0deg)';
    if (ckLbl)  ckLbl.textContent = 'Collapse';

    const selectedActs = Array.from(document.querySelectorAll('#act-selector .act-selector-chk:checked'))
        .map(c => c.value);
    const othersChk = document.getElementById('act_others_chk');
    if (othersChk && othersChk.checked) selectedActs.push('Others');

    const selRow = document.querySelector('#assetList .asset-row.selected');

    if (selectedActs.length === 0 || !selRow) {
        section.classList.add('hidden');
        wrapper.innerHTML = '<div class="text-sm text-gray-400 text-center py-4">Select an asset and at least one activity to see the checklist.</div>';
        return;
    }

    section.classList.remove('hidden');

    const gid          = document.getElementById('group_id_hidden')?.value;
    const groupName    = window._selectedGroupName || ('Group #' + gid);
    const buildingName = window._selectedBuilding  || '';
    const unitName     = window._selectedUnit      || '';
    const locationParts = [buildingName, unitName].filter(Boolean);
    const locationLabel = locationParts.length ? locationParts.map(esc).join(' &rsaquo; ') : 'Location';

    const othersTxtEl = document.getElementById('act_others_text');
    const rawOthers   = othersTxtEl ? othersTxtEl.value : '';
    const cleanOthers = rawOthers.trim().split('\n')
        .map(l => l.replace(/^[\u2022]\s*/, '').trim()).filter(Boolean).join('\n');
    function actLabel(act) { return (act === 'Others' && cleanOthers) ? cleanOthers : act; }

    const parts = selRow.dataset.assetLabel.split(' \u2014 ');
    const tag   = parts[0] || '';
    const model = parts[1] || '';
    const aid   = selRow.dataset.assetId;

    const colW = Math.max(80, Math.floor(280 / Math.max(selectedActs.length, 1)));

    let thead = '<thead>'
        + '<tr class="bg-blue-700 text-white text-xs">'
        + '<th colspan="2" class="px-3 py-2 text-left font-semibold whitespace-nowrap border-r border-blue-600">EQUIPMENT NO. / ITEMS &mdash; LOCATION</th>'
        + '<th colspan="' + selectedActs.length + '" class="px-3 py-2 text-center font-semibold border-r border-blue-600">ACTIVITIES PERFORMED</th>'
        + '<th class="px-3 py-2 text-center font-semibold">Remarks</th>'
        + '</tr>'
        + '<tr class="bg-blue-50 text-xs">'
        + '<td colspan="2" class="px-3 py-1.5 text-gray-500 border-r border-gray-200">' + locationLabel + ' &mdash; ' + esc(groupName) + '</td>'
        + '<td colspan="' + selectedActs.length + '" class="px-3 py-1.5 border-r border-gray-200"></td>'
        + '<td></td>'
        + '</tr>'
        + '<tr class="bg-gray-50 text-xs font-semibold text-gray-600 border-b border-gray-200">'
        + '<th class="px-3 py-2 text-left whitespace-nowrap border-r border-gray-200" style="min-width:90px">Tag No.</th>'
        + '<th class="px-3 py-2 text-left whitespace-nowrap border-r border-gray-200" style="min-width:130px">Model / Description</th>';
    selectedActs.forEach(function(act) {
        thead += '<th class="px-2 py-2 text-center border-r border-gray-100 leading-tight" style="min-width:' + colW + 'px;max-width:120px">' + esc(actLabel(act)) + '</th>';
    });
    thead += '<th class="px-3 py-2 text-left" style="min-width:140px">Remarks</th></tr></thead>';

    let tbody = '<tbody><tr class="border-b border-gray-100 hover:bg-blue-50/40 text-xs">'
        + '<td class="px-3 py-2 font-semibold text-gray-800 whitespace-nowrap border-r border-gray-100">' + esc(tag) + '</td>'
        + '<td class="px-3 py-2 text-gray-600 border-r border-gray-100">' + esc(model) + '</td>';
    selectedActs.forEach(function(act) {
        tbody += '<td class="px-2 py-2 text-center border-r border-gray-100">'
            + '<input type="checkbox" class="act-check w-4 h-4 text-blue-600 rounded border-gray-400 cursor-pointer focus:ring-blue-500"'
            + ' data-asset="' + esc(aid) + '" data-activity="' + esc(act) + '" checked>'
            + '</td>';
    });
    tbody += '<td class="px-2 py-1.5">'
        + '<input type="text" class="act-remark w-full border border-gray-200 rounded-lg px-2 py-1 text-xs focus:ring-1 focus:ring-blue-400 outline-none" placeholder="Remarks&hellip;">'
        + '</td></tr></tbody>';

    wrapper.innerHTML = '<table class="w-full text-xs border-collapse min-w-[480px]">' + thead + tbody + '</table>';
    restoreCkState();
}

// ── Custom activities ─────────────────────────────────────
function addCustomActivity() {
    const inp = document.getElementById('custom-act-input');
    if (!inp) return;
    const val = inp.value.trim();
    if (!val) return;
    inp.value = '';
    const selector = document.getElementById('act-selector');
    if (!selector) return;
    const id    = 'custom-act-' + Date.now();
    const label = document.createElement('label');
    label.className = 'act-pill flex items-center gap-2 px-3 py-2 rounded-xl border border-blue-400 bg-blue-50 cursor-pointer transition text-xs select-none';
    label.innerHTML = '<input type="checkbox" id="' + id + '" class="act-selector-chk w-3.5 h-3.5 text-blue-600 rounded border-gray-300 flex-shrink-0 pointer-events-none" value="' + esc(val) + '" onchange="onActivitySelectorChange()" checked>'
        + '<span class="text-blue-700 font-semibold leading-tight flex-1">' + esc(val) + '</span>'
        + '<button type="button" onclick="removeCustomActivity(this)" class="ml-auto text-gray-400 hover:text-red-500 pointer-events-auto flex-shrink-0"><i class="fa-solid fa-xmark text-[10px]"></i></button>';
    selector.appendChild(label);
    onActivitySelectorChange();
}

function removeCustomActivity(btn) {
    const label = btn.closest('.act-pill');
    if (label) label.remove();
    onActivitySelectorChange();
}

// ── Custom Activities ────────────────────────────────────
function _escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function addCustomActivity() {
    const inp = document.getElementById('custom-act-input');
    const val = inp.value.trim();
    if (!val) return;

    // Prevent duplicates (case-insensitive)
    const existing = Array.from(document.querySelectorAll('#act-selector .act-selector-chk'))
        .map(c => c.value.toLowerCase());
    if (existing.includes(val.toLowerCase())) {
        inp.value = '';
        inp.focus();
        return;
    }

    const grid = document.getElementById('act-selector');
    // Insert before the last pill ("Others")
    const othersLabel = grid.querySelector('label:last-child');

    const lbl = document.createElement('label');
    lbl.className = 'act-pill custom-act-pill flex items-center gap-2 px-3 py-2 rounded-xl border border-blue-200 bg-blue-50 hover:border-blue-400 cursor-pointer transition text-xs select-none relative';
    lbl.innerHTML =
        '<input type="checkbox" class="act-selector-chk w-3.5 h-3.5 text-blue-600 rounded border-gray-300 flex-shrink-0 pointer-events-none"' +
        ' value="' + _escHtml(val) + '" onchange="onActivitySelectorChange()" checked>' +
        '<span class="text-gray-700 leading-tight flex-1">' + _escHtml(val) + '</span>' +
        '<button type="button" onclick="removeCustomActivity(this)"' +
        ' class="ml-auto text-gray-400 hover:text-red-500 transition leading-none" tabindex="-1">' +
        '<i class="fa-solid fa-xmark text-[10px]"></i></button>';

    grid.insertBefore(lbl, othersLabel);
    inp.value = '';
    inp.focus();
    onActivitySelectorChange();
    syncActivities();
    buildActivityTable();
}

function removeCustomActivity(btn) {
    const lbl = btn.closest('label');
    if (!lbl) return;
    lbl.remove();
    syncActivities();
    buildActivityTable();
}

// ── syncActivities ────────────────────────────────────────
function syncActivities() {
    const checked = Array.from(document.querySelectorAll('#act-selector .act-selector-chk:checked'))
        .map(c => c.value);
    const othersChk = document.getElementById('act_others_chk');
    const others    = document.getElementById('act_others_text');
    if (othersChk?.checked) {
        const rawOthers   = others ? others.value : '';
        const cleanOthers = rawOthers.trim().split('\n')
            .map(l => l.replace(/^[\u2022]\s*/, '').trim()).filter(Boolean).join('\n');
        if (cleanOthers) checked.push('Others: ' + cleanOthers);
        else             checked.push('Others');
    }
    document.getElementById('activities_hidden').value = checked.join(', ');
}

function toggleOthers() {
    const chk = document.getElementById('act_others_chk');
    document.getElementById('others_box').classList.toggle('hidden', !chk.checked);
    syncActivities();
    buildActivityTable();
}

function othersInit() {
    const ta = document.getElementById('act_others_text');
    if (!ta) return;
    if (!ta.value.trim()) ta.value = '\u2022 ';
}

function othersBullet(e) {
    const ta = document.getElementById('act_others_text');
    if (!ta) return;
    if (e.key === 'Enter') {
        e.preventDefault();
        const start  = ta.selectionStart;
        const before = ta.value.substring(0, start);
        const after  = ta.value.substring(ta.selectionEnd);
        const insert = '\n\u2022 ';
        if (before.endsWith('\u2022 ') || before.endsWith('\u2022')) {
            const trimLen = before.endsWith('\u2022 ') ? 2 : 1;
            ta.value = before.slice(0, -trimLen) + after;
            ta.selectionStart = ta.selectionEnd = start - trimLen;
        } else {
            ta.value = before + insert + after;
            ta.selectionStart = ta.selectionEnd = start + insert.length;
        }
        syncActivities();
        buildActivityTable();
    }
}

function esc(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Restore saved group + asset on page load ──────────────
(function() {
    populateBuildingFilter();
    if (!savedGroupId) return;

    const groupRow = document.querySelector('#groupList .group-row[data-group-id="' + savedGroupId + '"]');
    if (!groupRow) return;

    // Set hidden group field
    document.getElementById('group_id_hidden').value = savedGroupId;
    const name = groupRow.dataset.groupName;
    const code = groupRow.dataset.groupCode;
    window._selectedGroupName = name + (code ? ' (' + code + ')' : '');

    // Set building/unit info
    const gdata = allGroups.find(function(g) { return g.group_id === parseInt(savedGroupId); });
    window._selectedBuilding = (gdata && gdata.building_name) ? gdata.building_name : '';
    window._selectedUnit     = (gdata && gdata.unit_name)     ? gdata.unit_name     : '';
    const bldEl  = document.getElementById('info-building');
    const unitEl = document.getElementById('info-unit');
    if (bldEl)  { bldEl.textContent  = window._selectedBuilding || '\u2014'; bldEl.classList.toggle('text-gray-400', !window._selectedBuilding);  bldEl.classList.toggle('italic', !window._selectedBuilding);  bldEl.classList.toggle('text-gray-800', !!window._selectedBuilding); }
    if (unitEl) { unitEl.textContent = window._selectedUnit    || '\u2014'; unitEl.classList.toggle('text-gray-400', !window._selectedUnit); unitEl.classList.toggle('italic', !window._selectedUnit); unitEl.classList.toggle('text-gray-800', !!window._selectedUnit); }

    // Visual feedback on group row
    groupRow.classList.add('bg-orange-50','ring-1','ring-orange-300');
    const chkEl = document.getElementById('group-check-' + savedGroupId);
    if (chkEl) chkEl.classList.remove('hidden');

    // Show group badge + deselect button
    const badge = document.getElementById('selected-group-badge');
    badge.textContent = name; badge.classList.remove('hidden');
    document.getElementById('deselect-group-btn').classList.remove('hidden');

    // Switch to asset search mode
    searchMode = 'asset';
    const inp = document.getElementById('mainSearch');
    inp.value = '';
    inp.placeholder = 'Search asset by tag, model or serial number...';
    const bfWrap = document.getElementById('building-filter-wrap');
    if (bfWrap) bfWrap.classList.add('hidden');
    document.getElementById('search-hint').classList.add('hidden');

    // Build asset list with pre-selected asset
    buildAssetList(parseInt(savedGroupId), savedAssetId || null);

    // Init activity pills and checklist
    updatePillStyles();
    buildActivityTable();
    syncActivities();
})();


// ── Keyword Tip ───────────────────────────────────────────
(function () {
    const _esc = function (s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); };
    const _rules = (<?= json_encode($keywordRulesData ?? []) ?>).map(function (r) {
        if (!r.keywords || !r.keywords.length) return null;
        const pat = r.keywords.map(function (k) { return k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&').replace(/\s+/g, '\\s*'); });
        return { re: new RegExp('\\b(' + pat.join('|') + ')\\b', 'i'), sec: r.sectionAcronym, tips: r.tips || {} };
    }).filter(Boolean);
    const _C = { NICM: ['#f0fdf4','#bbf7d0','#166534','\uD83C\uDF10'], ICTRAM: ['#fffbeb','#fde68a','#92400e','\uD83D\uDDA5'], MIS: ['#faf5ff','#e9d5ff','#6b21a8','\uD83D\uDD11'] };
    const _ta  = document.querySelector('[name="remarks"]');
    const _box = document.getElementById('kw-tip');
    if (!_ta || !_box) return;
    let _t;
    _ta.addEventListener('input', function () { clearTimeout(_t); _t = setTimeout(function () { _run(_ta.value.trim()); }, 600); });
    function _run(text) {
        if (text.length < 3) { _box.classList.add('hidden'); return; }
        let hit = null, kws = [];
        for (const r of _rules) {
            const m = text.match(new RegExp(r.re.source, 'gi'));
            if (m && m.length) { kws = [...new Set(m.map(function (x) { return x.toLowerCase(); }))]; hit = r; break; }
        }
        if (!hit) { _box.classList.add('hidden'); return; }
        const td = hit.tips[kws[0]] || hit.tips['default'];
        if (!td || (!td.title && !td.body)) { _box.classList.add('hidden'); return; }
        const col = _C[hit.sec] || _C.NICM;
        const bg = col[0], bd = col[1], tx = col[2], ic = col[3];
        _box.style.cssText = 'background:'+bg+';border:1px solid '+bd+';border-radius:.75rem;padding:.7rem 1rem;margin-top:.4rem';
        _box.innerHTML = '<div style="display:flex;align-items:flex-start;gap:.55rem"><span style="font-size:1.1rem;flex-shrink:0;line-height:1.3">'+ic+'</span><div style="flex:1;min-width:0"><p style="margin:0;font-size:.8rem;font-weight:700;color:'+tx+'">'+_esc(td.title||'')+'</p><p style="margin:.2rem 0 0;font-size:.75rem;color:'+tx+';opacity:.8;line-height:1.5">'+_esc(td.body||'')+'</p><div style="margin-top:.35rem;display:flex;flex-wrap:wrap;gap:.25rem">'+kws.map(function(k){return'<span style="display:inline-block;padding:.1rem .45rem;background:rgba(255,255,255,.6);font-size:.7rem;font-weight:700;color:'+tx+';border-radius:9999px;border:1px solid '+bd+'">'+_esc(k)+'</span>';}).join('')+'</div></div><button type="button" onclick="document.getElementById(\'kw-tip\').classList.add(\'hidden\')" style="background:none;border:none;cursor:pointer;font-size:.75rem;color:'+tx+';opacity:.6;padding:0;margin-left:.2rem;line-height:1;flex-shrink:0" title="Dismiss">&#x2715;</button></div>';
        _box.classList.remove('hidden');
    }
})();
</script>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
