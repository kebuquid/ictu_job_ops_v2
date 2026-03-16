<?php
$pageTitle    = 'Edit Group: ' . esc($group['group_name']);
$pageSubtitle = 'Update group details (individual assets keep their own data)';
$g = $group;
ob_start();
?>

<div class="max-w-2xl mx-auto">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?= site_url('asset-groups') ?>" class="hover:text-blue-600 transition">Asset Groups</a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <a href="<?= site_url('asset-groups/show/' . $g['group_id']) ?>" class="hover:text-blue-600 transition"><?= esc($g['group_name']) ?></a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <span class="text-gray-700 font-medium">Edit</span>
    </nav>

    <!-- Step Indicators -->
    <div class="flex items-center justify-between mb-8 relative">
        <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 z-0">
            <div id="progress-bar" class="h-full bg-blue-500 transition-all duration-500" style="width:0%"></div>
        </div>
        <?php
        $steps = [
            ['icon' => 'fa-layer-group', 'label' => 'Group Info'],
            ['icon' => 'fa-building',    'label' => 'Assignment'],
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

    <form action="<?= site_url('asset-groups/update/' . $g['group_id']) ?>" method="post" id="group-form">
        <?= csrf_field() ?>

        <!-- STEP 1: Group Info + Settings -->
        <div class="step-panel" id="panel-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800">Group Info</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 1 of 3</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Group Name <span class="text-red-500">*</span></label>
                        <input type="text" name="group_name" id="group_name" required value="<?= esc($g['group_name']) ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Group Code</label>
                        <input type="text" name="group_code" value="<?= esc($g['group_code'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                        <select name="category" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value=""> Select Category </option>
                            <?php foreach(['IT Equipment','Furniture','Office Equipment','Vehicle','Machinery','Other'] as $c): ?>
                                <option value="<?= $c ?>" <?= ($g['category'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <?php foreach(['Active','Under Repair','Inactive','Disposed'] as $s): ?>
                                <option value="<?= $s ?>" <?= ($g['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tag Prefix</label>
                        <input type="text" name="tag_prefix" value="<?= esc($g['tag_prefix'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Lifecycle</label>
                        <input type="text" name="lifecycle" value="<?= esc($g['lifecycle'] ?? '') ?>" placeholder="e.g. 5 years"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Quantity <span class="text-gray-400 font-normal text-xs">(read-only)</span></label>
                        <input type="number" value="<?= $g['quantity'] ?>" disabled
                               class="w-full border border-gray-100 rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Description</label>
                        <textarea name="description" rows="2"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none resize-none"><?= esc($g['description'] ?? '') ?></textarea>
                        <div id="kw-tip" class="hidden"></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <a href="<?= site_url('asset-groups/show/' . $g['group_id']) ?>" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="button" onclick="goStep(2)" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Next <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Assignment + Financial -->
        <div class="step-panel hidden" id="panel-2">
            <!-- Assignment -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-building text-indigo-500"></i>
                    <h3 class="font-semibold text-gray-800">Assignment</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 2 of 3</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <?php
                    $currentUnitId   = $g['assigned_unit_id'] ?? null;
                    $currentBuilding = $currentBuildingId ?? '';
                    ?>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Building</label>
                        <select id="building_select" name="building_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value=""> Select Building </option>
                            <?php foreach ($buildings as $b): ?>
                                <option value="<?= $b['building_id'] ?>" <?= (string)$currentBuilding === (string)$b['building_id'] ? 'selected' : '' ?>><?= esc($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Organizational Unit</label>
                        <select id="unit_select" name="assigned_unit_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            <option value=""> Select Org Unit </option>
                            <?php foreach ($units as $u): ?>
                                <option value="<?= $u['unit_id'] ?>" data-building="<?= $u['building_id'] ?>"
                                    <?= (string)$currentUnitId === (string)$u['unit_id'] ? 'selected' : '' ?>><?= esc($u['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Assigned To</label>
                        <input type="text" name="assigned_to" value="<?= esc($g['assigned_to'] ?? '') ?>" placeholder="Person / dept name"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Financial -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-peso-sign text-green-500"></i>
                    <h3 class="font-semibold text-gray-800">Financial &amp; Dates <span class="text-xs text-gray-400 font-normal">(per unit)</span></h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Acquisition Cost / unit</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">&#8369;</span>
                            <input type="number" name="acquisition_cost" step="0.01" min="0"
                                   value="<?= esc($g['acquisition_cost'] ?? '') ?>"
                                   class="w-full border border-gray-200 rounded-xl pl-7 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Depreciation Cost / unit</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">&#8369;</span>
                            <input type="number" name="depreciation_cost" step="0.01" min="0"
                                   value="<?= esc($g['depreciation_cost'] ?? '') ?>"
                                   class="w-full border border-gray-200 rounded-xl pl-7 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date Acquired</label>
                        <input type="date" name="date_acquired" value="<?= esc($g['date_acquired'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warranty End</label>
                        <input type="date" name="warranty_end" value="<?= esc($g['warranty_end'] ?? '') ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
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

        <!-- STEP 3: Review -->
        <div class="step-panel hidden" id="panel-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-purple-500"></i>
                    <h3 class="font-semibold text-gray-800">Review &amp; Confirm</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 3 of 3</span>
                </div>
                <div class="p-6 space-y-5">
                    <p class="text-sm text-gray-500">Please review the details before saving changes.</p>

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
                            <div class="col-span-2"><span class="text-xs text-gray-400">Description</span><p class="text-sm font-medium text-gray-800" id="rv-description"></p></div>
                        </div>
                    </div>

                    <!-- Assignment + Financial -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-building text-indigo-400"></i> Assignment &amp; Financial
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Organizational Unit</span><p class="text-sm font-medium text-gray-800" id="rv-assigned_unit_id"></p></div>
                            <div><span class="text-xs text-gray-400">Assigned To</span><p class="text-sm font-medium text-gray-800" id="rv-assigned_to"></p></div>
                            <div><span class="text-xs text-gray-400">Acquisition Cost / unit</span><p class="text-sm font-medium text-gray-800" id="rv-acquisition_cost"></p></div>
                            <div><span class="text-xs text-gray-400">Depreciation Cost / unit</span><p class="text-sm font-medium text-gray-800" id="rv-depreciation_cost"></p></div>
                            <div><span class="text-xs text-gray-400">Date Acquired</span><p class="text-sm font-medium text-gray-800" id="rv-date_acquired"></p></div>
                            <div><span class="text-xs text-gray-400">Warranty End</span><p class="text-sm font-medium text-gray-800" id="rv-warranty_end"></p></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <button type="button" onclick="goStep(2)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </div>
        </div>

    </form>
</div>

<script>
let currentStep = 1;
const totalSteps = 3;

function goStep(n) {
    if (n > 1 && currentStep === 1) {
        const name = document.getElementById('group_name');
        if (!name.value.trim()) {
            name.focus();
            name.classList.add('border-red-400', 'ring-2', 'ring-red-300');
            setTimeout(() => name.classList.remove('border-red-400', 'ring-2', 'ring-red-300'), 2000);
            return;
        }
    }
    document.getElementById('panel-' + currentStep).classList.add('hidden');
    document.getElementById('panel-' + n).classList.remove('hidden');
    updateIndicators(n);
    currentStep = n;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function goReview() {
    const name = document.getElementById('group_name');
    if (!name.value.trim()) { goStep(1); name.focus(); return; }

    function fv(nm) {
        const el = document.querySelector('[name="' + nm + '"]');
        if (!el) return '';
        if (el.tagName === 'SELECT') return el.options[el.selectedIndex]?.text || '';
        return el.value.trim() || '';
    }

    ['group_name','group_code','category','status','tag_prefix','lifecycle','description','assigned_to','date_acquired','warranty_end'].forEach(f => {
        const el = document.getElementById('rv-' + f);
        if (el) el.textContent = fv(f);
    });

    ['acquisition_cost','depreciation_cost'].forEach(f => {
        const el  = document.getElementById('rv-' + f);
        const raw = document.querySelector('[name="' + f + '"]')?.value;
        if (el) el.textContent = raw ? ' ' + parseFloat(raw).toLocaleString('en-PH', {minimumFractionDigits:2}) : '';
    });

    const unitSel = document.getElementById('unit_select');
    document.getElementById('rv-assigned_unit_id').textContent =
        unitSel && unitSel.value ? unitSel.options[unitSel.selectedIndex].text : '';

    goStep(3);
}

function updateIndicators(n) {
    const icons = ['fa-layer-group','fa-building','fa-eye'];
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

// Building  Unit cascade
(function(){
    const bSel    = document.getElementById('building_select');
    const uSel    = document.getElementById('unit_select');
    if (!bSel || !uSel) return;
    const allOpts = Array.from(uSel.options);
    const savedUnit = uSel.value;
    function filter(){
        const bid = bSel.value;
        uSel.innerHTML = '<option value=""> Select Org Unit </option>';
        allOpts.filter(o => !o.value || o.dataset.building === bid).forEach(o => {
            const clone = o.cloneNode(true);
            if (clone.value === savedUnit) clone.selected = true;
            uSel.appendChild(clone);
        });
    }
    bSel.addEventListener('change', filter);
    if (bSel.value) filter();
})();

// ── Keyword Tip ──────────────────────────────────────────────
(function () {
    const _esc = function (s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); };
    const _rules = (<?= json_encode($keywordRulesData ?? []) ?>).map(function (r) {
        if (!r.keywords || !r.keywords.length) return null;
        const pat = r.keywords.map(function (k) { return k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&').replace(/\s+/g, '\\s*'); });
        return { re: new RegExp('\\b(' + pat.join('|') + ')\\b', 'i'), sec: r.sectionAcronym, tips: r.tips || {} };
    }).filter(Boolean);
    const _C = { NICM: ['#f0fdf4','#bbf7d0','#166534','\uD83C\uDF10'], ICTRAM: ['#fffbeb','#fde68a','#92400e','\uD83D\uDDA5'], MIS: ['#faf5ff','#e9d5ff','#6b21a8','\uD83D\uDD11'] };
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
