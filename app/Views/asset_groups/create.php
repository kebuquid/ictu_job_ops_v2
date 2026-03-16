<?php
$pageTitle    = 'Create Asset Group';
$pageSubtitle = 'Create a group, then assign existing assets to it';

// Build JS asset data (with costs)
$jsAssetData = [];
foreach (($availableAssets ?? []) as $a) {
    $jsAssetData[(int)$a['asset_id']] = [
        'tag'              => $a['asset_tag'] ?? '',
        'acquisition_cost' => (float)($a['acquisition_cost'] ?? 0),
        'depreciation_cost'=> (float)($a['depreciation_cost'] ?? 0),
    ];
}

ob_start();
?>

<div class="max-w-2xl mx-auto">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?= site_url('asset-groups') ?>" class="hover:text-blue-600 transition">Asset Groups</a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <span class="text-gray-700 font-medium">Create Group</span>
    </nav>

    <!-- Validation errors -->
    <?php if (isset($validation) && $validation->getErrors()): ?>
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <div class="flex items-center gap-2 text-red-700 font-medium text-sm mb-1">
            <i class="fa-solid fa-triangle-exclamation"></i> Please fix the following errors:
        </div>
        <ul class="list-disc list-inside text-sm text-red-600 space-y-0.5">
            <?php foreach ($validation->getErrors() as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
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
            ['icon' => 'fa-layer-group', 'label' => 'Group Info'],
            ['icon' => 'fa-eye',         'label' => 'Review'],
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

    <form action="<?= site_url('asset-groups/store') ?>" method="post" id="group-form">
        <?= csrf_field() ?>
        <!-- Computed costs submitted as hidden fields -->
        <input type="hidden" name="acquisition_cost"  id="computed_acquisition_cost"  value="0">
        <input type="hidden" name="depreciation_cost" id="computed_depreciation_cost" value="0">

        <!-- STEP 1: Group Info + Assignment + Assets -->
        <div class="step-panel" id="panel-1">

            <!-- Group Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800">Group Info</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 1 of 2</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Group Name <span class="text-red-500">*</span></label>
                        <input type="text" name="group_name" id="group_name" required placeholder="e.g. Desktop Computers Batch 2026"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Group Code</label>
                        <input type="text" name="group_code" placeholder="e.g. GRP-2026-01"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                        <select name="category" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value=""> Select Category </option>
                            <?php foreach(['IT Equipment','Furniture','Office Equipment','Vehicle','Machinery','Security and Surveillance Equipment','Other'] as $c): ?>
                                <option value="<?= $c ?>"><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <?php foreach(['Active','Under Repair','Inactive','Disposed'] as $s): ?>
                                <option value="<?= $s ?>"><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tag Prefix</label>
                        <input type="text" name="tag_prefix" placeholder="e.g. IT-PC"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Lifecycle</label>
                        <input type="text" name="lifecycle" placeholder="e.g. 5 years"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <!-- Building / Unit / Assigned To -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Building</label>
                        <select id="building_select" name="building_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value=""> Select Building </option>
                            <?php foreach ($buildings as $b): ?>
                                <option value="<?= $b['building_id'] ?>" <?= set_select('building_id', (string)$b['building_id']) ?>><?= esc($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Organizational Unit</label>
                        <select id="unit_select" name="assigned_unit_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value=""> Select Org Unit </option>
                            <?php foreach ($units as $u): ?>
                                <option value="<?= $u['unit_id'] ?>" data-building="<?= $u['building_id'] ?>" <?= set_select('assigned_unit_id', (string)$u['unit_id']) ?>><?= esc($u['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Assigned To</label>
                        <!-- hidden real value -->
                        <input type="hidden" name="assigned_to" id="ag_assigned_to_id" value="<?= set_value('assigned_to') ?>">
                        <div class="relative">
                            <input type="text" id="ag_assigned_to_search"
                                placeholder="Search user..."
                                autocomplete="off"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                                value="<?php
                                    $preUser = set_value('assigned_to');
                                    if ($preUser) {
                                        foreach ($users as $u) {
                                            if ((string)$u['user_id'] === (string)$preUser) {
                                                echo esc($u['name']);
                                                break;
                                            }
                                        }
                                    }
                                ?>">
                            <ul id="ag_user_dropdown"
                                class="fixed z-[999] bg-white border border-gray-200 rounded-lg shadow-xl max-h-48 overflow-y-auto hidden text-sm">
                                <?php foreach ($users as $u): ?>
                                    <li class="ag-user-option px-3 py-2 cursor-pointer hover:bg-blue-50"
                                        data-id="<?= $u['user_id'] ?>"
                                        data-name="<?= esc($u['name']) ?>"
                                        data-email="<?= esc($u['email']) ?>">
                                        <span class="font-medium text-gray-800"><?= esc($u['name']) ?></span>
                                        <span class="text-xs text-gray-400 ml-1"><?= esc($u['email']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Description</label>
                        <textarea name="description" rows="2" placeholder="Optional description..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"></textarea>
                        <div id="kw-tip" class="hidden"></div>
                    </div>
                </div>
            </div>

            <!-- Select Assets -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-orange-500"></i>
                        <h3 class="font-semibold text-gray-800">Select Assets</h3>
                        <span class="text-xs text-gray-400 font-normal">(ungrouped only)</span>
                    </div>
                    <span id="selected-count" class="text-xs font-semibold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full">0 selected</span>
                </div>
                <div class="p-4">
                    <?php if (empty($availableAssets)): ?>
                        <p class="text-sm text-gray-400 text-center py-4">No ungrouped assets available. All existing assets already belong to a group.</p>
                    <?php else: ?>
                        <div class="mb-3">
                            <input type="text" id="assetSearch" placeholder="Search by tag, model, serial number, or category..."
                                   oninput="filterCreateAssets()"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="flex gap-2 mb-3">
                            <button type="button" onclick="selectAllAssets()" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 transition">Select All</button>
                            <button type="button" onclick="clearAllAssets()" class="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 transition">Clear All</button>
                        </div>
                        <!-- Computed cost summary -->
                        <div id="cost-summary" class="hidden mb-3 bg-green-50 border border-green-100 rounded-xl px-4 py-3 flex flex-wrap gap-4 text-sm">
                            <div><span class="text-gray-500 text-xs">Total Acquisition Cost</span><p class="font-semibold text-gray-800" id="sum-acquisition">&#8369; 0.00</p></div>
                            <div><span class="text-gray-500 text-xs">Total Depreciation Cost</span><p class="font-semibold text-gray-800" id="sum-depreciation">&#8369; 0.00</p></div>
                        </div>
                        <div id="asset-search-hint" class="text-sm text-gray-400 text-center py-6">
                            <i class="fa-solid fa-magnifying-glass mb-1 block text-gray-300 text-lg"></i>
                            Type to search for assets
                        </div>
                        <div id="asset-no-results" class="hidden text-sm text-gray-400 text-center py-6">
                            <i class="fa-solid fa-circle-xmark mb-1 block text-gray-300 text-lg"></i>
                            No assets found
                        </div>
                        <div id="assetList" class="hidden max-h-64 overflow-y-auto divide-y divide-gray-50 border border-gray-100 rounded-xl">
                            <?php foreach ($availableAssets as $a): ?>
                            <label class="asset-row flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition"
                                   data-search="<?= strtolower(esc($a['asset_tag'] . ' ' . $a['brand_model'] . ' ' . $a['serial_number'] . ' ' . $a['category'])) ?>">
                                <input type="checkbox" name="asset_ids[]" value="<?= $a['asset_id'] ?>"
                                       onchange="updateCount()"
                                       class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800"><?= esc($a['asset_tag']) ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?= esc($a['brand_model']) ?><?= !empty($a['serial_number']) ? ' &mdash; S/N: ' . esc($a['serial_number']) : '' ?></p>
                                </div>
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    <?= match($a['category']) {
                                        'IT Equipment'     => 'bg-blue-50 text-blue-600',
                                        'Furniture'        => 'bg-yellow-50 text-yellow-700',
                                        'Office Equipment' => 'bg-purple-50 text-purple-600',
                                        'Machinery'        => 'bg-red-50 text-red-600',
                                        default            => 'bg-gray-100 text-gray-600'
                                    } ?>">
                                    <?= esc($a['category']) ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="<?= site_url('asset-groups') ?>" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="button" onclick="goReview()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Review <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Review -->
        <div class="step-panel hidden" id="panel-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-purple-500"></i>
                    <h3 class="font-semibold text-gray-800">Review &amp; Confirm</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 2 of 2</span>
                </div>
                <div class="p-6 space-y-5">
                    <p class="text-sm text-gray-500">Please review the details before creating the group.</p>

                    <!-- Group Info -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-layer-group text-blue-400"></i> Group Info
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Group Name</span><p class="text-sm font-medium text-gray-800" id="rv-group_name"></p></div>
                            <div><span class="text-xs text-gray-400">Group Code</span><p class="text-sm font-medium text-gray-800" id="rv-group_code"></p></div>
                            <div><span class="text-xs text-gray-400">Category</span><p class="text-sm font-medium text-gray-800" id="rv-category"></p></div>
                            <div><span class="text-xs text-gray-400">Status</span><p class="text-sm font-medium text-gray-800" id="rv-status"></p></div>
                            <div><span class="text-xs text-gray-400">Tag Prefix</span><p class="text-sm font-medium text-gray-800" id="rv-tag_prefix"></p></div>
                            <div><span class="text-xs text-gray-400">Lifecycle</span><p class="text-sm font-medium text-gray-800" id="rv-lifecycle"></p></div>
                            <div><span class="text-xs text-gray-400">Building</span><p class="text-sm font-medium text-gray-800" id="rv-building"></p></div>
                            <div><span class="text-xs text-gray-400">Org Unit</span><p class="text-sm font-medium text-gray-800" id="rv-assigned_unit_id"></p></div>
                            <div><span class="text-xs text-gray-400">Assigned To</span><p class="text-sm font-medium text-gray-800" id="rv-assigned_to"></p></div>
                            <div class="col-span-2"><span class="text-xs text-gray-400">Description</span><p class="text-sm font-medium text-gray-800" id="rv-description"></p></div>
                        </div>
                    </div>

                    <!-- Selected Assets -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-boxes-stacked text-orange-400"></i> Selected Assets
                        </h4>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm font-medium text-gray-800" id="rv-assets">None selected</p>
                        </div>
                    </div>

                    <!-- Computed Financial -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-peso-sign text-green-400"></i> Computed Financial
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Total Acquisition Cost</span><p class="text-sm font-semibold text-gray-800" id="rv-acquisition_cost"></p></div>
                            <div><span class="text-xs text-gray-400">Total Depreciation Cost</span><p class="text-sm font-semibold text-gray-800" id="rv-depreciation_cost"></p></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <button type="button" onclick="goStep(1)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition">
                    <i class="fa-solid fa-layer-group"></i> Create Group
                </button>
            </div>
        </div>

    </form>
</div>

<script>
const assetData  = <?= json_encode($jsAssetData) ?>;
let currentStep  = 1;
const totalSteps = 2;

function goStep(n) {
    document.getElementById('panel-' + currentStep).classList.add('hidden');
    document.getElementById('panel-' + n).classList.remove('hidden');
    updateIndicators(n);
    currentStep = n;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

const requiredFields = [
    { sel: '#group_name',                   label: 'Group Name' },
    { sel: '[name="group_code"]',           label: 'Group Code' },
    { sel: '[name="category"]',             label: 'Category' },
    { sel: '[name="tag_prefix"]',           label: 'Tag Prefix' },
    { sel: '[name="lifecycle"]',            label: 'Lifecycle' },
    { sel: '#building_select',              label: 'Building' },
    { sel: '[name="assigned_unit_id"]',     label: 'Organizational Unit' },
    { sel: '[name="description"]',          label: 'Description' },
];

function clearValidation() {
    document.querySelectorAll('#panel-1 .field-error').forEach(e => e.remove());
    document.querySelectorAll('#panel-1 .input-error').forEach(el => {
        el.classList.remove('border-red-400', 'ring-2', 'ring-red-200', 'input-error');
    });
}

function validateGroup() {
    clearValidation();
    let valid = true;
    let firstBad = null;
    requiredFields.forEach(({ sel, label }) => {
        const el = document.querySelector(sel);
        if (!el) return;
        const empty = (el.tagName === 'SELECT' || el.tagName === 'TEXTAREA')
            ? !el.value.trim()
            : !el.value.trim();
        if (empty) {
            valid = false;
            el.classList.add('border-red-400', 'ring-2', 'ring-red-200', 'input-error');
            const msg = document.createElement('p');
            msg.className = 'field-error text-xs text-red-500 mt-1';
            msg.textContent = label + ' is required.';
            el.parentNode.appendChild(msg);
            if (!firstBad) firstBad = el;
        }
    });
    if (firstBad) firstBad.focus();
    return valid;
}

function goReview() {
    if (!validateGroup()) return;

    function fv(nm) {
        const el = document.querySelector('[name="' + nm + '"]');
        if (!el) return '';
        if (el.tagName === 'SELECT') return el.options[el.selectedIndex]?.text || '';
        return el.value.trim() || '';
    }

    ['group_name','group_code','category','status','tag_prefix','lifecycle','description'].forEach(f => {
        const el = document.getElementById('rv-' + f);
        if (el) el.textContent = fv(f) || '';
    });

    // Assigned To — show selected user name from the search input
    const agAssignedName = document.getElementById('ag_assigned_to_search')?.value?.trim();
    const rvAssigned = document.getElementById('rv-assigned_to');
    if (rvAssigned) rvAssigned.textContent = agAssignedName || '';

    const bSel = document.getElementById('building_select');
    document.getElementById('rv-building').textContent = bSel && bSel.value ? bSel.options[bSel.selectedIndex].text : '';

    const uSel = document.getElementById('unit_select');
    document.getElementById('rv-assigned_unit_id').textContent = uSel && uSel.value ? uSel.options[uSel.selectedIndex].text : '';

    // Checked assets + compute costs
    const checked = Array.from(document.querySelectorAll('#assetList input[type=checkbox]:checked'));
    let totalAcq = 0, totalDep = 0;
    checked.forEach(cb => {
        const d = assetData[cb.value];
        if (d) { totalAcq += d.acquisition_cost; totalDep += d.depreciation_cost; }
    });

    document.getElementById('rv-assets').textContent = checked.length
        ? checked.map(cb => cb.closest('label').querySelector('p')?.textContent).join(', ')
        : 'None selected';

    const fmt = v => ' ' + v.toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('rv-acquisition_cost').textContent  = fmt(totalAcq);
    document.getElementById('rv-depreciation_cost').textContent = fmt(totalDep);

    // Set hidden fields for submission
    document.getElementById('computed_acquisition_cost').value  = totalAcq.toFixed(2);
    document.getElementById('computed_depreciation_cost').value = totalDep.toFixed(2);

    goStep(2);
}

function updateIndicators(n) {
    const icons = ['fa-layer-group','fa-eye'];
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

// Building and Organizational Unit are independent — no cascade filter.


function updateCount() {
    const checked = document.querySelectorAll('#assetList input[type=checkbox]:checked');
    const n = checked.length;
    document.getElementById('selected-count').textContent = n + ' selected';

    // Recompute cost summary
    let totalAcq = 0, totalDep = 0;
    checked.forEach(cb => {
        const d = assetData[cb.value];
        if (d) { totalAcq += d.acquisition_cost; totalDep += d.depreciation_cost; }
    });
    const summary = document.getElementById('cost-summary');
    if (n > 0) {
        const fmt = v => ' ' + v.toLocaleString('en-PH', {minimumFractionDigits:2});
        document.getElementById('sum-acquisition').textContent  = fmt(totalAcq);
        document.getElementById('sum-depreciation').textContent = fmt(totalDep);
        summary.classList.remove('hidden');
    } else {
        summary.classList.add('hidden');
    }
}

function filterCreateAssets() {
    const q         = document.getElementById('assetSearch').value.trim().toLowerCase();
    const hint      = document.getElementById('asset-search-hint');
    const noResults = document.getElementById('asset-no-results');
    const list      = document.getElementById('assetList');

    if (!q) {
        list.classList.add('hidden');
        hint.classList.remove('hidden');
        noResults.classList.add('hidden');
        return;
    }

    let visible = 0;
    document.querySelectorAll('#assetList .asset-row').forEach(row => {
        const match = row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    if (visible > 0) {
        list.classList.remove('hidden');
        hint.classList.add('hidden');
        noResults.classList.add('hidden');
    } else {
        list.classList.add('hidden');
        hint.classList.add('hidden');
        noResults.classList.remove('hidden');
    }
}

function selectAllAssets() {
    document.querySelectorAll('#assetList .asset-row').forEach(row => {
        if (row.style.display !== 'none') row.querySelector('input[type=checkbox]').checked = true;
    });
    updateCount();
}

function clearAllAssets() {
    document.querySelectorAll('#assetList input[type=checkbox]').forEach(cb => cb.checked = false);
    updateCount();
}

// Asset Group — User live-search picker
(function () {
    const searchInput = document.getElementById('ag_assigned_to_search');
    const hiddenInput = document.getElementById('ag_assigned_to_id');
    const dropdown    = document.getElementById('ag_user_dropdown');
    const options     = Array.from(dropdown.querySelectorAll('.ag-user-option'));

    if (!searchInput) return;

    function positionDropdown() {
        const rect = searchInput.getBoundingClientRect();
        const dropH = dropdown.offsetHeight || 192; // max-h-48 = 192px
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;

        if (spaceBelow < dropH && spaceAbove > spaceBelow) {
            // open upward
            dropdown.style.top = (rect.top + window.scrollY - dropH - 4) + 'px';
        } else {
            // open downward
            dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
        }
        dropdown.style.left  = rect.left + 'px';
        dropdown.style.width = rect.width + 'px';
    }

    function showDropdown(filter) {
        const q = filter.toLowerCase();
        let hasVisible = false;
        options.forEach(li => {
            const match = !q || li.dataset.name.toLowerCase().includes(q) || li.dataset.email.toLowerCase().includes(q);
            li.style.display = match ? '' : 'none';
            if (match) hasVisible = true;
        });
        if (hasVisible) {
            positionDropdown();
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    }

    searchInput.addEventListener('input', function () {
        hiddenInput.value = '';
        showDropdown(this.value);
    });

    searchInput.addEventListener('focus', function () {
        showDropdown(this.value);
    });

    window.addEventListener('scroll', function () {
        if (!dropdown.classList.contains('hidden')) positionDropdown();
    }, true);

    options.forEach(li => {
        li.addEventListener('mousedown', function (e) {
            e.preventDefault();
            hiddenInput.value = this.dataset.id;
            searchInput.value = this.dataset.name;
            dropdown.classList.add('hidden');
        });
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
})();

// ── Keyword Tip ──────────────────────────────────────────────
(function () {
    const _esc = function (s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); };
    const _rules = (<?= json_encode($keywordRulesData ?? []) ?>).map(function (r) {
        if (!r.keywords || !r.keywords.length) return null;
        const pat = r.keywords.map(function (k) { return k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&').replace(/\s+/g, '\\s*'); });
        return { re: new RegExp('\\b(' + pat.join('|') + ')\\b', 'i'), sec: r.sectionAcronym, tips: r.tips || {} };
    }).filter(Boolean);
    const _C = { NICM: ['#f0fdf4','#bbf7d0','#166534','🌐'], ICTRAM: ['#fffbeb','#fde68a','#92400e','🖥️'], MIS: ['#faf5ff','#e9d5ff','#6b21a8','🔑'] };
    const _ta = document.querySelector('[name="description"]');
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
