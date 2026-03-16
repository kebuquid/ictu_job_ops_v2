<?php
$pageTitle    = 'Add Preventive/Corrective Maintenance Record';
$pageSubtitle = 'Log a maintenance for an asset';

$activityOptions = ['Repair','Installation','Cleaning','Inspection','Replacement','Calibration','Lubrication','Testing','Updating / Patching','Backup & Restore','Virus Removal','Configuration'];
$savedActivities = array_map('trim', explode(',', set_value('activities', '')));

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

// Flat group list for search
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

ob_start();
?>

<div class="max-w-2xl mx-auto">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?= site_url('maintenance') ?>" class="hover:text-blue-600 transition">Maintenance</a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <span class="text-gray-700 font-medium">New Record</span>
    </nav>

    <?php if (isset($errors) && count($errors)): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm flex gap-3 items-start">
        <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
        <ul class="list-disc list-inside space-y-0.5">
            <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
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

    <form action="<?= site_url('maintenance/store') ?>" method="post" id="maint-form">
        <?= csrf_field() ?>
        <input type="hidden" name="activities" id="activities_hidden" value="<?= set_value('activities') ?>">

        <!-- STEP 1: Asset Group & Asset -->
        <div class="step-panel" id="panel-1">

            <!-- Hidden fields for submission -->
            <input type="hidden" name="group_id" id="group_id_hidden" value="">
            <div id="asset_ids_container"></div>

            <!-- Select Asset Group & Assets (merged card) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-orange-500"></i>
                        <h3 class="font-semibold text-gray-800">Asset Group &amp; Assets <span class="text-red-500">*</span></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="selected-group-badge" class="hidden text-xs font-semibold bg-orange-50 text-orange-600 px-2.5 py-1 rounded-full"></span>
                        <button type="button" id="deselect-group-btn"
                                onclick="clearGroup()"
                                title="Change group"
                                class="hidden text-xs text-gray-400 hover:text-red-500 transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <span id="selected-asset-badge" class="text-xs font-semibold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full">0 selected</span>
                    </div>
                </div>
                <div class="p-4">
                    <?php if (empty($groups)): ?>
                        <p class="text-sm text-gray-400 text-center py-4">
                            No asset groups found. <a href="<?= site_url('asset-groups/create') ?>" class="text-blue-500 hover:underline">Create one first &rarr;</a>
                        </p>
                    <?php else: ?>


                        <!-- Single shared search bar -->
                        <div class="mb-3">
                            <input type="text" id="mainSearch"
                                   placeholder="Search by group, code, or building..."
                                   oninput="filterCurrent()"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-400 outline-none">
                        </div>

                        <!-- Select All / Clear All (asset mode only) -->
                        <div id="asset-controls" class="hidden flex gap-2 mb-3">
                            <button type="button" onclick="selectAllAssets()" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 transition">Select All</button>
                            <button type="button" onclick="clearAllAssets()" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 transition">Clear All</button>
                        </div>

                        <!-- Hint / no-results (shared) -->
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

            <!-- Building Asset Summary Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 hidden" id="building-chart-card">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-400"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Assets by Building</h3>
                    <span class="ml-auto text-xs text-gray-400">Total inventory</span>
                </div>
                <div class="px-6 py-4 overflow-x-auto" id="building-chart-body"></div>
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

            <!-- Maintenance Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-screwdriver-wrench text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800">Maintenance Info</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 2 of 3</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Read-only location context (auto-filled from selected group) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Building</label>
                        <div id="info-building" class="text-sm bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-gray-400 italic min-h-[42px]">&mdash;</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Org Unit / Office</label>
                        <div id="info-unit" class="text-sm bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-gray-400 italic min-h-[42px]">&mdash;</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Frequency</label>
                        <select name="frequency" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value="">&mdash; Select &mdash;</option>
                            <?php foreach (['Monthly','Quarterly','Semi-Annual','Annual'] as $f): ?>
                                <option value="<?= $f ?>" <?= set_select('frequency', $f) ?>><?= $f ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Maintenance Date <span class="text-red-500">*</span></label>
                        <input type="date" name="maintenance_date" required value="<?= set_value('maintenance_date', date('Y-m-d')) ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <!-- ── Step A: Activity Selector ─────────────────────────────── -->
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
                                <label class="act-pill flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 bg-white hover:border-blue-400 hover:bg-blue-50 cursor-pointer transition text-xs select-none">
                                    <input type="checkbox" id="act_others_chk" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300 flex-shrink-0 pointer-events-none" onchange="toggleOthers()">
                                    <span class="text-gray-700">Others</span>
                                </label>
                            </div>
                            <!-- Add custom activity -->
                            <div class="flex items-center gap-2 mt-3">
                                <input type="text" id="custom-act-input"
                                    placeholder="Add custom activity…"
                                    class="flex-1 border border-gray-200 rounded-lg px-3 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 outline-none"
                                    onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomActivity();}">
                                <button type="button" onclick="addCustomActivity()"
                                    class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition flex-shrink-0">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Add
                                </button>
                            </div>
                            <div id="others_box" class="hidden mt-3">
                                <textarea id="act_others_text" rows="3"
                                    placeholder="• Activity 1&#10;• Activity 2"
                                    onfocus="othersInit()"
                                    onkeydown="othersBullet(event)"
                                    oninput="syncActivities(); buildActivityTable();"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-y font-mono"></textarea>
                                <p class="text-xs text-gray-400 mt-1">Each line becomes a bullet item.</p>
                            </div>
                        </div>

                        <!-- ── Step B: Per-asset Checklist Table ──────────────────────── -->
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
                    Next <i class="fa-solid fa-arrow-right text-xs"></i>
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
                        <input type="text" name="conducted_by" value="<?= set_value('conducted_by') ?>" placeholder="Technician name"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date Conducted</label>
                        <input type="date" name="conducted_date" value="<?= set_value('conducted_date') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Verified By</label>
                        <input type="text" name="verified_by" value="<?= set_value('verified_by') ?>" placeholder="Supervisor name"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date Verified</label>
                        <input type="date" name="verified_date" value="<?= set_value('verified_date') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Remarks</label>
                        <textarea name="remarks" rows="2" placeholder="General remarks / observations&hellip;"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= set_value('remarks') ?></textarea>
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
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= set_value('corrective_action') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Corrective Action Date</label>
                        <input type="date" name="corrective_date" value="<?= set_value('corrective_date') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Responsible Person</label>
                        <input type="text" name="responsible_person" value="<?= set_value('responsible_person') ?>" placeholder="Person responsible"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Responsible Date</label>
                        <input type="date" name="responsible_date" value="<?= set_value('responsible_date') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Remarks</label>
                        <textarea name="responsible_remarks" rows="2" placeholder="Additional remarks&hellip;"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= set_value('responsible_remarks') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="goStep(2)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="button" onclick="goStep3Review()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Review <i class="fa-solid fa-arrow-right text-xs"></i>
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
                    <p class="text-sm text-gray-500">Please review all details before saving.</p>

                    <!-- Asset -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-layer-group text-orange-400"></i> Asset
                        </h4>
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                            <div><span class="text-xs text-gray-400">Group</span><p class="text-sm font-medium text-gray-800" id="rv-group_id"></p></div>
                            <div><span class="text-xs text-gray-400">Building</span><p class="text-sm font-medium text-gray-800" id="rv-building"></p></div>
                            <div><span class="text-xs text-gray-400">Org Unit / Office</span><p class="text-sm font-medium text-gray-800" id="rv-unit"></p></div>
                            <div><span class="text-xs text-gray-400">Selected Assets</span><p class="text-sm font-medium text-gray-800" id="rv-asset_id"></p></div>
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

                    <!-- Corrective Action & Responsible Person -->
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
                <button type="button" onclick="if(validateStep3()) document.getElementById('maint-form').submit()"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition">
                    <i class="fa-solid fa-floppy-disk"></i> Save Record
                </button>
            </div>
        </div>

    </form>
</div>

<script>
const groupedAssets = <?= json_encode($jsGroupedAssets) ?>;
const allGroups     = <?= json_encode($jsGroups) ?>;

let currentStep = 1;
const totalSteps = 4;

// ── Checklist state preservation ─────────────────────────
// Keyed as ckState[assetId][activity] = { checked, remark }
let ckState = {};

function saveCkState() {
    ckState = {};
    document.querySelectorAll('#act-table-wrapper .act-check').forEach(function(cb) {
        const aid = cb.dataset.asset;
        const act = cb.dataset.activity;
        if (!ckState[aid]) ckState[aid] = {};
        const row = cb.closest('tr');
        const remarkEl = row ? row.querySelector('.act-remark') : null;
        ckState[aid][act] = {
            checked: cb.checked,
            remark:  remarkEl ? remarkEl.value : ''
        };
    });
}

function restoreCkState() {
    if (!Object.keys(ckState).length) return;
    document.querySelectorAll('#act-table-wrapper .act-check').forEach(function(cb) {
        const aid = cb.dataset.asset;
        const act = cb.dataset.activity;
        const saved = ckState[aid] && ckState[aid][act];
        if (!saved) return;
        cb.checked = saved.checked;
        const row = cb.closest('tr');
        const remarkEl = row ? row.querySelector('.act-remark') : null;
        if (remarkEl && saved.remark) remarkEl.value = saved.remark;
    });
}

// Category colour helper (mirrors create.php)
function catClass(cat) {
    const map = {'IT Equipment':'bg-blue-50 text-blue-600','Furniture':'bg-yellow-50 text-yellow-700','Office Equipment':'bg-purple-50 text-purple-600','Machinery':'bg-red-50 text-red-600'};
    return map[cat] || 'bg-gray-100 text-gray-600';
}

// ── Building asset summary bar chart (vertical columns) ───
function buildBuildingChart() {
    const body = document.getElementById('building-chart-body');
    if (!body) return;

    // Tally assets per building
    const tally = {};
    allGroups.forEach(function(g) {
        const bname = g.building_name || 'Unassigned';
        const count = (groupedAssets[g.group_id] || []).length;
        tally[bname] = (tally[bname] || 0) + count;
    });

    const entries = Object.entries(tally).sort((a, b) => b[1] - a[1]);
    if (!entries.length) {
        body.innerHTML = '<p class="text-xs text-gray-400 text-center py-2">No building data available.</p>';
        return;
    }

    const max   = entries[0][1] || 1;
    const total = entries.reduce((s, e) => s + e[1], 0);
    const palette = [
        '#60a5fa','#34d399','#a78bfa','#fbbf24',
        '#f87171','#22d3ee','#e879f9','#fb923c'
    ];

    const chartH = 140; // px height of column area

    // Y-axis grid lines & labels
    const ySteps = 4;
    let gridHtml = '';
    for (let i = ySteps; i >= 0; i--) {
        const val   = Math.round((i / ySteps) * max);
        const top   = Math.round(((ySteps - i) / ySteps) * chartH);
        gridHtml += '<div style="position:absolute;left:0;right:0;top:' + top + 'px;border-top:1px dashed #e5e7eb;z-index:0">'
            + '<span style="position:absolute;left:-28px;top:-8px;font-size:10px;color:#9ca3af;width:26px;text-align:right">' + val + '</span>'
            + '</div>';
    }

    // Columns
    let colsHtml = '';
    const colW = Math.min(80, Math.floor((100 / entries.length) * 0.72));
    entries.forEach(function([bname, count], i) {
        const pct   = (count / max) * 100;
        const color = palette[i % palette.length];
        const colH  = Math.round((count / max) * chartH);
        // Abbreviate long names
        const shortName = bname;
        colsHtml += '<div style="display:flex;flex-direction:column;align-items:center;flex:1;min-width:' + colW + 'px;max-width:96px;cursor:default" title="' + bname + ': ' + count + ' assets">'
            // value label on top
            + '<span style="font-size:11px;font-weight:700;color:#374151;margin-bottom:3px">' + count + '</span>'
            // bar wrapper (bottom-aligned)
            + '<div style="width:100%;height:' + chartH + 'px;display:flex;align-items:flex-end;position:relative;z-index:1">'
            + '<div style="width:100%;height:' + colH + 'px;background:' + color + ';border-radius:6px 6px 0 0;transition:height .5s;position:relative" class="chart-bar">'
            + '</div>'
            + '</div>'
            // x label
            + '<div style="margin-top:6px;font-size:9.5px;color:#6b7280;text-align:center;line-height:1.3;word-break:break-word;white-space:normal;max-width:' + (colW + 16) + 'px">' + shortName + '</div>'
            + '</div>';
    });

    let html = '<div style="position:relative;padding-left:32px">'
        // Grid
        + '<div style="position:absolute;left:32px;right:0;top:0;height:' + chartH + 'px">' + gridHtml + '</div>'
        // Flex columns
        + '<div style="display:flex;align-items:flex-end;gap:6px;height:' + (chartH + 40) + 'px;padding-top:18px">'
        + colsHtml
        + '</div>'
        + '</div>'
        + '<p style="font-size:11px;color:#9ca3af;margin-top:8px;text-align:right">'
        + 'Total: <strong style="color:#374151">' + total + '</strong> assets &nbsp;·&nbsp; '
        + '<strong style="color:#374151">' + entries.length + '</strong> building' + (entries.length !== 1 ? 's' : '')
        + '</p>';

    body.innerHTML = html;
}

// Build chart on load
// (triggered by selectGroup instead)

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

function validateStep1() {
    clearMaintErrors('panel-1');
    const gid = document.getElementById('group_id_hidden').value;
    if (!gid) {
        const inp = document.getElementById('mainSearch');
        if (inp) { inp.classList.add('border-red-400','ring-2','ring-red-200','input-error'); }
        const hint = document.getElementById('search-hint');
        // show inline error
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
    // Activities: at least one activity selected in the selector
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

function goStep(n) {
    if (n > currentStep) {
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !validateStep2()) return;
        if (currentStep === 3 && !validateStep3()) return;
    } else {
        clearMaintErrors('panel-' + currentStep);
    }
    // Save checklist state before leaving Step 2
    if (currentStep === 2) saveCkState();
    document.getElementById('panel-' + currentStep).classList.add('hidden');
    document.getElementById('panel-' + n).classList.remove('hidden');
    if (n === 2) { updatePillStyles(); buildActivityTable(); restoreCkState(); }
    updateIndicators(n);
    currentStep = n;
    window.scrollTo({top: 0, behavior: 'smooth'});
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

    const acts = document.getElementById('activities_hidden')?.value || '';
    const rvActs = document.getElementById('rv-activities');
    const rvOthersBlock = document.getElementById('rv-others-block');
    const rvOthersList  = document.getElementById('rv-others-list');
    // Regular activities (exclude the synthetic Others: … entry)
    const regularActs = acts.split(',').map(a => a.trim()).filter(a => a && !a.startsWith('Others'));
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
        const rawOthers = othersText ? othersText.value.trim() : '';
        if (othersChecked || rawOthers) {
            const lines = rawOthers.split('\n')
                .map(l => l.replace(/^[•\-]\s*/, '').trim()).filter(Boolean);
            rvOthersList.innerHTML = lines.length
                ? lines.map(l => '<li class="inline-flex items-center gap-1.5 text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-full"><i class="fa-solid fa-pen-to-square text-[10px]"></i>' + l + '</li>').join('')
                : '<li class="text-xs text-gray-400 italic">No remarks entered</li>';
            rvOthersBlock.classList.remove('hidden');
        } else {
            rvOthersBlock.classList.add('hidden');
            rvOthersList.innerHTML = '';
        }
    }

    const grpSel = document.getElementById('group_id_hidden');
    setText('rv-group_id', grpSel && grpSel.value
        ? (window._selectedGroupName || grpSel.value) : '');
    setText('rv-building', window._selectedBuilding || '\u2014');
    setText('rv-unit',     window._selectedUnit     || '\u2014');

    // Sync checked assets into hidden inputs + review
    syncAssetHiddenInputs();
    const selectedTags = Array.from(document.querySelectorAll('#assetList .asset-row.selected')).map(r => r.dataset.assetLabel.split(' — ')[0]);
    setText('rv-asset_id', selectedTags.length ? selectedTags.join(', ') : 'All assets in group');

    // ── Build read-only per-equipment checklist for review ────
    saveCkState();
    const rvCkWrap  = document.getElementById('rv-checklist');
    const rvCkTable = document.getElementById('rv-checklist-table');
    if (rvCkWrap && rvCkTable) {
        const selRows = Array.from(document.querySelectorAll('#assetList .asset-row.selected'));
        if (selRows.length && regularActs.length) {
            let t = '<table class="w-full text-xs border-collapse">';
            t += '<thead><tr>';
            t += '<th class="bg-blue-700 text-white text-left px-3 py-2 border border-blue-600 font-semibold">Equipment</th>';
            regularActs.forEach(function(a) {
                t += '<th class="bg-blue-700 text-white text-center px-2 py-2 border border-blue-600 font-semibold">' + esc(a) + '</th>';
            });
            t += '<th class="bg-blue-700 text-white text-center px-2 py-2 border border-blue-600 font-semibold min-w-[120px]">Remarks</th>';
            t += '</tr></thead><tbody>';
            selRows.forEach(function(row, idx) {
                const aid   = row.dataset.assetId;
                const label = row.dataset.assetLabel.split(' — ')[0];
                const rb    = (idx % 2 === 0) ? 'bg-white' : 'bg-gray-50';
                t += '<tr class="' + rb + '">';
                t += '<td class="px-3 py-2 border border-gray-200 font-medium text-gray-800">' + esc(label) + '</td>';
                const assetState = ckState[aid] || {};
                let remarkText = '';
                regularActs.forEach(function(a) {
                    const s = assetState[a];
                    const checked = s && s.checked;
                    if (s && s.remark) remarkText = s.remark;
                    if (checked) {
                        t += '<td class="text-center px-2 py-2 border border-gray-200"><span class="text-green-600 font-bold"><i class="fa-solid fa-circle-check"></i></span></td>';
                    } else {
                        t += '<td class="text-center px-2 py-2 border border-gray-200"><span class="text-red-400"><i class="fa-solid fa-circle-xmark"></i></span></td>';
                    }
                });
                // Collect remark from any activity column for this asset
                let allRemarks = [];
                Object.keys(assetState).forEach(function(k) {
                    if (assetState[k].remark) allRemarks.push(assetState[k].remark);
                });
                t += '<td class="px-2 py-2 border border-gray-200 text-gray-600">' + esc(allRemarks.join('; ')) + '</td>';
                t += '</tr>';
            });
            t += '</tbody></table>';
            rvCkTable.innerHTML = t;
            rvCkWrap.classList.remove('hidden');
        } else {
            rvCkWrap.classList.add('hidden');
            rvCkTable.innerHTML = '';
        }
    }

    goStep(3);
}

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

// ── Group search ──────────────────────────────────────────
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
    // Populate units (all at first)
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
    const bFilter = (document.getElementById('buildingFilter')?.value || '');
    const uFilter = (document.getElementById('unitFilter')?.value || '');
    const hint    = document.getElementById('search-hint');
    const noRes   = document.getElementById('search-no-results');
    const list    = document.getElementById('groupList');
    if (!q && !bFilter && !uFilter) {
        list.classList.add('hidden'); hint.classList.remove('hidden'); noRes.classList.add('hidden'); return;
    }
    let visible = 0;
    document.querySelectorAll('#groupList .group-row').forEach(row => {
        const matchQ = !q || row.dataset.search.includes(q);
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
    // Reset hidden inputs
    document.getElementById('group_id_hidden').value = '';
    window._selectedGroupName = '';

    // Hide badge + deselect button
    const badge = document.getElementById('selected-group-badge');
    badge.textContent = ''; badge.classList.add('hidden');
    document.getElementById('deselect-group-btn').classList.add('hidden');

    // Clear asset selection
    document.getElementById('asset_ids_container').innerHTML = '';
    document.getElementById('selected-asset-badge').textContent = '0 selected';
    document.getElementById('assetList').innerHTML = '';
    document.getElementById('assetList').classList.add('hidden');

    // Remove group row highlights
    document.querySelectorAll('#groupList .group-row').forEach(r => {
        r.classList.remove('bg-orange-50','ring-1','ring-orange-300');
        const chk = document.getElementById('group-check-' + r.dataset.groupId);
        if (chk) chk.classList.add('hidden');
    });

    // Clear building / org unit display fields
    window._selectedBuilding = '';
    window._selectedUnit     = '';
    // Hide building chart
    const chartCard = document.getElementById('building-chart-card');
    if (chartCard) chartCard.classList.add('hidden');
    const bld2  = document.getElementById('info-building');
    const unit2 = document.getElementById('info-unit');
    if (bld2)  { bld2.textContent  = '\u2014'; bld2.classList.add('text-gray-400','italic');  bld2.classList.remove('text-gray-800'); }
    if (unit2) { unit2.textContent = '\u2014'; unit2.classList.add('text-gray-400','italic'); unit2.classList.remove('text-gray-800'); }

    // Switch back to group search mode
    searchMode = 'group';
    const inp = document.getElementById('mainSearch');
    inp.value = '';
    inp.placeholder = 'Search by group, code, or building...';
    inp.className = inp.className.replace('focus:ring-blue-500','focus:ring-orange-400');
    inp.classList.remove('border-red-400','ring-2','ring-red-300');

    // Reset + show building filter
    const bfSel  = document.getElementById('buildingFilter');
    const uSel   = document.getElementById('unitFilter');
    const bfWrap = document.getElementById('building-filter-wrap');
    if (bfSel)  { bfSel.value = ''; }
    if (uSel)   { repopulateUnitFilter(''); uSel.value = ''; }
    if (bfWrap) bfWrap.classList.remove('hidden');

    // Hide asset controls, show group UI
    document.getElementById('asset-controls').classList.add('hidden');
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

    // Populate building / org unit display fields
    const gdata = allGroups.find(function(g) { return g.group_id === parseInt(gid); });
    window._selectedBuilding = (gdata && gdata.building_name) ? gdata.building_name : '';
    window._selectedUnit     = (gdata && gdata.unit_name)     ? gdata.unit_name     : '';
    const bldEl  = document.getElementById('info-building');
    const unitEl = document.getElementById('info-unit');
    if (bldEl)  { bldEl.textContent  = window._selectedBuilding || '\u2014'; bldEl.classList.toggle('text-gray-400', !window._selectedBuilding);  bldEl.classList.toggle('italic', !window._selectedBuilding);  bldEl.classList.toggle('text-gray-800', !!window._selectedBuilding); }
    if (unitEl) { unitEl.textContent = window._selectedUnit    || '\u2014'; unitEl.classList.toggle('text-gray-400', !window._selectedUnit); unitEl.classList.toggle('italic', !window._selectedUnit); unitEl.classList.toggle('text-gray-800', !!window._selectedUnit); }

    // Visual feedback on group rows
    document.querySelectorAll('#groupList .group-row').forEach(r => {
        r.classList.remove('bg-orange-50','ring-1','ring-orange-300');
        const chk = document.getElementById('group-check-' + r.dataset.groupId);
        if (chk) chk.classList.add('hidden');
    });
    row.classList.add('bg-orange-50','ring-1','ring-orange-300');
    const chk = document.getElementById('group-check-' + gid);
    if (chk) chk.classList.remove('hidden');

    // Update badge + show deselect button
    const badge = document.getElementById('selected-group-badge');
    badge.textContent = name; badge.classList.remove('hidden');
    document.getElementById('deselect-group-btn').classList.remove('hidden');

    // Clear asset selection
    document.getElementById('asset_ids_container').innerHTML = '';
    document.getElementById('selected-asset-badge').textContent = '0 selected';

    // Build asset list
    buildAssetList(parseInt(gid));

    // Show building chart
    const chartCard = document.getElementById('building-chart-card');
    if (chartCard) { chartCard.classList.remove('hidden'); buildBuildingChart(); }

    // Switch search bar to asset mode
    searchMode = 'asset';
    const inp = document.getElementById('mainSearch');
    inp.value = '';
    inp.placeholder = 'Search a asset by tag, model, serial number, or category...';
    inp.className = inp.className.replace('focus:ring-orange-400', 'focus:ring-blue-500');

    // Hide building filter in asset mode
    const bfWrap = document.getElementById('building-filter-wrap');
    if (bfWrap) bfWrap.classList.add('hidden');

    // Hide group list, show asset controls + hint
    document.getElementById('groupList').classList.add('hidden');
    document.getElementById('asset-controls').classList.remove('hidden');
    document.getElementById('search-hint').classList.remove('hidden');
    document.getElementById('search-hint-text').textContent = 'Type to search for assets';
    document.getElementById('no-results-text').textContent  = 'No assets found';
    document.getElementById('search-no-results').classList.add('hidden');
    document.getElementById('assetList').classList.add('hidden');
}

function buildAssetList(gid) {
    const assets = groupedAssets[gid] || [];
    const list   = document.getElementById('assetList');
    list.innerHTML = '';

    if (assets.length === 0) {
        list.innerHTML = '<div class="text-sm text-gray-400 text-center py-4">No assets in this group.</div>';
    } else {
        assets.forEach(a => {
            const label = document.createElement('div');
            label.className = 'asset-row flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition';
            label.dataset.search = (a.asset_tag + ' ' + a.brand_model + ' ' + a.serial_number + ' ' + (a.category||'')).toLowerCase();
            label.dataset.assetId    = a.asset_id;
            label.dataset.assetLabel = a.asset_tag + ' — ' + (a.brand_model || '') + (a.serial_number ? ' (S/N: ' + a.serial_number + ')' : '');
            label.onclick = function(){ toggleAsset(this); };
            const cat = a.category || '';
            label.innerHTML = `
                <input type="checkbox" class="asset-cb w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 pointer-events-none">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">${esc(a.asset_tag)}</p>
                    <p class="text-xs text-gray-500 truncate">${esc(a.brand_model)}${a.serial_number ? ' &mdash; S/N: ' + esc(a.serial_number) : ''}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full ${catClass(cat)}">${esc(cat)}</span>`;
            list.appendChild(label);
        });
    }

    // Reset asset search UI — already handled by selectGroup
    list.classList.add('hidden');
}

function toggleAsset(row) {
    const cb = row.querySelector('.asset-cb');
    cb.checked = !cb.checked;
    row.classList.toggle('selected', cb.checked);
    row.classList.toggle('bg-blue-50', cb.checked);
    updateAssetCount();
}

function updateAssetCount() {
    const count = document.querySelectorAll('#assetList .asset-row.selected').length;
    document.getElementById('selected-asset-badge').textContent = count + ' selected';
}

function syncAssetHiddenInputs() {
    const container = document.getElementById('asset_ids_container');
    container.innerHTML = '';
    document.querySelectorAll('#assetList .asset-row.selected').forEach(row => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'asset_ids[]';
        inp.value = row.dataset.assetId;
        container.appendChild(inp);
    });
}

function selectAllAssets() {
    document.querySelectorAll('#assetList .asset-row').forEach(row => {
        if (row.style.display !== 'none') {
            row.classList.add('selected','bg-blue-50');
            const cb = row.querySelector('.asset-cb');
            if (cb) cb.checked = true;
        }
    });
    updateAssetCount();
}

function clearAllAssets() {
    document.querySelectorAll('#assetList .asset-row').forEach(row => {
        row.classList.remove('selected','bg-blue-50');
        const cb = row.querySelector('.asset-cb');
        if (cb) cb.checked = false;
    });
    updateAssetCount();
}

function filterAssets() {
    const q     = document.getElementById('mainSearch').value.trim().toLowerCase();
    const hint  = document.getElementById('search-hint');
    const noRes = document.getElementById('search-no-results');
    const list  = document.getElementById('assetList');
    if (!q) {
        list.classList.add('hidden'); hint.classList.remove('hidden'); noRes.classList.add('hidden'); return;
    }
    let visible = 0;
    document.querySelectorAll('#assetList .asset-row').forEach(row => {
        const match = row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    if (visible > 0) { list.classList.remove('hidden'); hint.classList.add('hidden'); noRes.classList.add('hidden'); }
    else             { list.classList.add('hidden');    hint.classList.add('hidden'); noRes.classList.remove('hidden'); }
}

function esc(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Activity checklist table ───────────────────────────────
let ckTableOpen = true;

function toggleCkTable() {
    ckTableOpen = !ckTableOpen;
    const body    = document.getElementById('ck-table-body');
    const chevron = document.getElementById('ck-chevron');
    const lbl     = document.getElementById('ck-toggle-label');
    if (body)    body.style.display    = ckTableOpen ? '' : 'none';
    if (chevron) chevron.style.transform = ckTableOpen ? 'rotate(0deg)' : 'rotate(-90deg)';
    if (lbl)     lbl.textContent       = ckTableOpen ? 'Collapse' : 'Expand';
}

// Called when an activity pill checkbox changes
function onActivitySelectorChange() {
    updatePillStyles();
    buildActivityTable();
    syncActivities();
}

// Highlight selected pills
function updatePillStyles() {
    document.querySelectorAll('#act-selector .act-pill').forEach(function(pill) {
        const chk  = pill.querySelector('input');
        const span = pill.querySelector('span');
        if (chk && chk.checked) {
            pill.classList.add('border-blue-500','bg-blue-50');
            pill.classList.remove('border-gray-200','bg-white');
            if (span) { span.classList.add('text-blue-700','font-semibold'); span.classList.remove('text-gray-700'); }
        } else {
            pill.classList.remove('border-blue-500','bg-blue-50');
            pill.classList.add('border-gray-200','bg-white');
            if (span) { span.classList.remove('text-blue-700','font-semibold'); span.classList.add('text-gray-700'); }
        }
    });
}

function buildActivityTable() {
    const wrapper      = document.getElementById('act-table-wrapper');
    const section      = document.getElementById('act-checklist-section');
    // Always re-expand when table rebuilds
    ckTableOpen = true;
    const ckBody = document.getElementById('ck-table-body');
    const ckChev = document.getElementById('ck-chevron');
    const ckLbl  = document.getElementById('ck-toggle-label');
    if (ckBody) ckBody.style.display = '';
    if (ckChev) ckChev.style.transform = 'rotate(0deg)';
    if (ckLbl)  ckLbl.textContent = 'Collapse';

    const selectedRows = Array.from(document.querySelectorAll('#assetList .asset-row.selected'));

    // Gather selected activities (from the selector)
    const selectedActs = Array.from(document.querySelectorAll('#act-selector .act-selector-chk:checked'))
        .map(c => c.value);
    const othersChk = document.getElementById('act_others_chk');
    if (othersChk && othersChk.checked) selectedActs.push('Others');

    if (selectedActs.length === 0 || selectedRows.length === 0) {
        section.classList.add('hidden');
        return;
    }

    section.classList.remove('hidden');

    const gid          = document.getElementById('group_id_hidden').value;
    const groupName    = window._selectedGroupName || ('Group #' + gid);
    const buildingName = window._selectedBuilding  || '';
    const unitName     = window._selectedUnit      || '';

    // Build location label: Building › Org Unit
    const locationParts = [buildingName, unitName].filter(Boolean);
    const locationLabel = locationParts.length ? locationParts.map(esc).join(' &rsaquo; ') : 'Location';

    // Resolve Others label once
    const othersTxtEl  = document.getElementById('act_others_text');
    const rawOthers    = othersTxtEl ? othersTxtEl.value : '';
    const cleanOthers  = rawOthers.trim().split('\n')
        .map(l => l.replace(/^[\u2022]\s*/, '').trim()).filter(Boolean).join('\n');

    function actLabel(act) {
        return (act === 'Others' && cleanOthers) ? cleanOthers : act;
    }

    // Build table  — rows = equipment, columns = activities
    let html = '<table class="w-full text-xs border-collapse">';

    // ── Header row 1: spanning titles ─────────────────────────
    html += '<thead>';
    html += '<tr>';
    html += '<th colspan="2" class="sticky left-0 z-10 bg-blue-700 text-white text-center px-3 py-2 border border-blue-600 font-semibold">EQUIPMENT NO. / ITEMS &mdash; LOCATION</th>';
    html += '<th colspan="' + selectedActs.length + '" class="bg-blue-700 text-white text-center px-3 py-2 border border-blue-600 font-semibold">ACTIVITIES PERFORMED</th>';
    html += '<th class="bg-blue-700 text-white text-center px-3 py-2 border border-blue-600 font-semibold min-w-[130px]">Remarks</th>';
    html += '</tr>';

    // ── Header row 2: location label ──────────────────────────
    html += '<tr>';
    html += '<th colspan="2" class="sticky left-0 z-10 bg-blue-50 text-blue-800 text-center px-3 py-1.5 border border-gray-200 font-medium">' + locationLabel + ' &mdash; ' + esc(groupName) + '</th>';
    html += '<th colspan="' + selectedActs.length + '" class="bg-blue-50 border border-gray-200"></th>';
    html += '<th class="bg-blue-50 border border-gray-200"></th>';
    html += '</tr>';

    // ── Header row 3: Tag / Model | one column per activity ───
    html += '<tr>';
    html += '<th class="sticky left-0 z-10 bg-gray-50 text-gray-600 text-center px-3 py-2 border border-gray-200 font-semibold min-w-[90px]">Tag No.</th>';
    html += '<th class="bg-gray-50 text-gray-600 text-center px-3 py-2 border border-gray-200 font-semibold min-w-[110px]">Model / Description</th>';
    selectedActs.forEach(function(act) {
        const isOthers = (act === 'Others');
        const lbl = actLabel(act);
        const labelStyle = isOthers ? 'style="white-space:pre-line;"' : '';
        html += '<th class="bg-gray-50 text-gray-700 text-center px-2 py-2 border border-gray-200 font-medium min-w-[80px]" ' + labelStyle + '>' + esc(lbl) + '</th>';
    });
    html += '<th class="bg-gray-50 border border-gray-200 min-w-[130px]"></th>';
    html += '</tr>';
    html += '</thead>';

    // ── Body rows: one per selected asset ─────────────────────
    html += '<tbody>';
    selectedRows.forEach(function(row, idx) {
        const rowBg = (idx % 2 === 0) ? 'bg-white' : 'bg-gray-50/60';
        const parts = row.dataset.assetLabel.split(' — ');
        const tag   = parts[0] || '';
        const model = parts[1] || '';
        const aid   = row.dataset.assetId;
        html += '<tr class="' + rowBg + ' hover:bg-blue-50/40 transition">';
        html += '<td class="sticky left-0 z-10 ' + rowBg + ' px-3 py-2 border border-gray-200 font-semibold text-gray-800">' + esc(tag) + '</td>';
        html += '<td class="px-3 py-2 border border-gray-200 text-gray-500 text-[11px]">' + esc(model) + '</td>';
        selectedActs.forEach(function(act) {
            html += '<td class="text-center px-2 py-2 border border-gray-200">';
            html += '<input type="checkbox" class="act-check w-4 h-4 text-blue-600 rounded border-gray-300 cursor-pointer focus:ring-blue-500" data-asset="' + esc(aid) + '" data-activity="' + esc(act) + '">';
            html += '</td>';
        });
        html += '<td class="px-2 py-1.5 border border-gray-200">';
        html += '<input type="text" class="act-remark w-full border border-gray-200 rounded-lg px-2 py-1 text-xs focus:ring-1 focus:ring-blue-400 outline-none placeholder-gray-300" placeholder="Remark…">';
        html += '</td>';
        html += '</tr>';
    });
    html += '</tbody></table>';
    wrapper.innerHTML = html;
}

// ── Bullet helpers for Others textarea ────────────────────
function othersInit() {
    const el = document.getElementById('act_others_text');
    if (!el) return;
    if (el.value === '') {
        el.value = '\u2022 ';
        el.selectionStart = el.selectionEnd = 2;
    }
}
function othersBullet(e) {
    const el = e.target;
    if (e.key === 'Enter') {
        e.preventDefault();
        const pos = el.selectionStart;
        const val = el.value;
        const insert = '\n\u2022 ';
        el.value = val.substring(0, pos) + insert + val.substring(el.selectionEnd);
        el.selectionStart = el.selectionEnd = pos + insert.length;
        syncActivities();
        buildActivityTable();
    } else if (e.key === 'Backspace' && el.selectionStart === el.selectionEnd) {
        // If cursor is immediately after the bullet+space at the start of a line, remove both
        const pos = el.selectionStart;
        const val = el.value;
        const before = val.substring(0, pos);
        if (before.endsWith('\u2022 ') || before.endsWith('\u2022')) {
            const trimLen = before.endsWith('\u2022 ') ? 2 : 1;
            e.preventDefault();
            el.value = val.substring(0, pos - trimLen) + val.substring(pos);
            el.selectionStart = el.selectionEnd = pos - trimLen;
            syncActivities();
            buildActivityTable();
        }
    }
}

// ── Add custom activity pill ───────────────────────────────
function addCustomActivity() {
    const input = document.getElementById('custom-act-input');
    const val   = input.value.trim();
    if (!val) return;

    // Prevent duplicates (case-insensitive)
    const existing = Array.from(document.querySelectorAll('#act-selector .act-selector-chk'))
        .map(c => c.value.toLowerCase());
    if (existing.includes(val.toLowerCase())) {
        input.value = '';
        return;
    }

    // Build pill with a remove button
    const grid  = document.getElementById('act-selector');
    const label = document.createElement('label');
    label.className = 'act-pill custom-act-pill flex items-center gap-2 px-3 py-2 rounded-xl border border-blue-200 bg-blue-50 hover:border-blue-400 cursor-pointer transition text-xs select-none relative group';
    label.innerHTML =
        '<input type="checkbox" class="act-selector-chk w-3.5 h-3.5 text-blue-600 rounded border-gray-300 flex-shrink-0 pointer-events-none" checked'
        + ' value="' + val.replace(/"/g, '&quot;') + '" onchange="onActivitySelectorChange()">'
        + '<span class="text-blue-700 leading-tight flex-1">' + val + '</span>'
        + '<button type="button" onclick="removeCustomActivity(this)" title="Remove"'
        + ' class="ml-auto text-blue-400 hover:text-red-500 pointer-events-auto transition">'
        + '<i class="fa-solid fa-xmark text-[10px]"></i></button>';

    // Insert before the "Others" label (last child)
    const othersLabel = grid.querySelector(':scope > label:last-child');
    grid.insertBefore(label, othersLabel);

    input.value = '';
    onActivitySelectorChange();
    buildActivityTable();
}

function removeCustomActivity(btn) {
    const label = btn.closest('.custom-act-pill');
    if (label) label.remove();
    onActivitySelectorChange();
    buildActivityTable();
}

// ── Sync activities ────────────────────────────────────────
function syncActivities() {
    // Serialize from the selector (unique activity names)
    const checked = Array.from(document.querySelectorAll('#act-selector .act-selector-chk:checked'))
        .map(c => c.value);
    const othersChk  = document.getElementById('act_others_chk');
    const othersText = document.getElementById('act_others_text');
    if (othersChk && othersChk.checked) {
        const raw = othersText ? othersText.value.trim() : '';
        const clean = raw.split('\n').map(l => l.replace(/^[\u2022]\s*/, '').trim()).filter(Boolean).join('\n');
        checked.push(clean ? 'Others: ' + clean : 'Others');
    }
    document.getElementById('activities_hidden').value = checked.join(', ');
}

function toggleOthers() {
    const chk = document.getElementById('act_others_chk');
    document.getElementById('others_box').classList.toggle('hidden', !chk.checked);
    updatePillStyles();
    buildActivityTable();
    syncActivities();
}

// ── Init ────────────────────────────────────────────────────
populateBuildingFilter();

// ── Keyword Tip ──────────────────────────────────────────────
(function () {
    const _esc = function (s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); };
    const _rules = (<?= json_encode($keywordRulesData ?? []) ?>).map(function (r) {
        if (!r.keywords || !r.keywords.length) return null;
        const pat = r.keywords.map(function (k) { return k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&').replace(/\s+/g, '\\s*'); });
        return { re: new RegExp('\\b(' + pat.join('|') + ')\\b', 'i'), sec: r.sectionAcronym, tips: r.tips || {} };
    }).filter(Boolean);
    const _C = { NICM: ['#f0fdf4','#bbf7d0','#166534','\uD83C\uDF10'], ICTRAM: ['#fffbeb','#fde68a','#92400e','\uD83D\uDDA5'], MIS: ['#faf5ff','#e9d5ff','#6b21a8','\uD83D\uDD11'] };
    const _ta = document.querySelector('[name="remarks"]');
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
        _box.innerHTML = '<div style="display:flex;align-items:flex-start;gap:.55rem"><span style="font-size:1.1rem;flex-shrink:0;line-height:1.3">'+ic+'</span><div style="flex:1;min-width:0"><p style="margin:0;font-size:.8rem;font-weight:700;color:'+tx+'">'+_esc(td.title||'')+'</p><p style="margin:.2rem 0 0;font-size:.75rem;color:'+tx+';opacity:.8;line-height:1.5">'+_esc(td.body||'')+'</p><div style="margin-top:.35rem;display:flex;flex-wrap:wrap;gap:.25rem">'+kws.map(function(k){return'<span style="display:inline-block;padding:.1rem .45rem;background:rgba(255,255,255,.6);font-size:.7rem;font-weight:700;color:'+tx+';border-radius:9999px;border:1px solid '+bd+'">'+_esc(k)+'</span>';}).join('')+'</div></div><button type="button" onclick="document.getElementById(\'kw-tip\').classList.add(\'hidden\')" style="background:none;border:none;cursor:pointer;font-size:.75rem;color:'+tx+';opacity:.6;padding:0;margin-left:.2rem;line-height:1;flex-shrink:0" title="Dismiss">✕</button></div>';
        _box.classList.remove('hidden');
    }
})();
</script>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
