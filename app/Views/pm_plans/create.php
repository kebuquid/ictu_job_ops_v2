<?php
$pageTitle    = 'Create PM Plan';
$pageSubtitle = 'New Preventive Maintenance Plan';
$routePrefix  = str_starts_with(uri_string(), 'admin/') ? 'admin' : 'super-admin';

ob_start();
$errors = $validation->getErrors();
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
?>

<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="<?= site_url($routePrefix . '/pm-plans') ?>" class="hover:text-blue-600 transition">PM Plans</a>
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
    <span class="text-gray-700 font-medium">Create New Plan</span>
</nav>

<?php if (!empty($errors)): ?>
<div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
    <p class="font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-0.5">
        <?php foreach ($errors as $e): ?>
            <li><?= esc($e) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" action="<?= site_url($routePrefix . '/pm-plans/store') ?>" id="pm-form">
<?= csrf_field() ?>

<!-- ── PLAN HEADER ─────────────────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
    <h3 class="font-semibold text-gray-800 text-base mb-4 flex items-center gap-2">
        <i class="fa-solid fa-file-lines text-blue-500"></i>
        Plan Information
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Year -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Year <span class="text-red-500">*</span></label>
            <input type="number" name="plan_year" min="2000" max="2100"
                   value="<?= old('plan_year', $curYear) ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <!-- Document Code -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Document Code</label>
            <input type="text" name="document_code"
                   value="<?= old('document_code', 'CSPC-F-ICTU-13') ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <!-- Title -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Equipment Title <span class="text-red-500">*</span></label>
            <input type="text" name="title"
                   value="<?= old('title', 'ICTU Equipment') ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <!-- Department -->
        <div class="md:col-span-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Department / Sub-title</label>
            <input type="text" name="department"
                   value="<?= old('department', 'Management Information System') ?>"
                   placeholder="e.g. Management Information System"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
    </div>
</div>

<!-- ── SIGNATORIES ─────────────────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
    <h3 class="font-semibold text-gray-800 text-base mb-4 flex items-center gap-2">
        <i class="fa-solid fa-signature text-purple-500"></i>
        Signatories
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Prepared By -->
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Prepared By</p>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="prepared_by" value="<?= old('prepared_by') ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="Full name">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Title / Designation</label>
                <input type="text" name="prepared_title" value="<?= old('prepared_title', 'Information Systems Analyst II') ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
        </div>
        <!-- Reviewed By -->
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Reviewed By</p>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="reviewed_by" value="<?= old('reviewed_by') ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="Full name">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Title / Designation</label>
                <input type="text" name="reviewed_title" value="<?= old('reviewed_title', 'Head, ICTU') ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
        </div>
        <!-- Approved By -->
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Approved By</p>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="approved_by" value="<?= old('approved_by') ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="Full name">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Title / Designation</label>
                <input type="text" name="approved_title" value="<?= old('approved_title', 'Vice President Admin & Finance') ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
        </div>
    </div>
</div>

<!-- ── EQUIPMENT ROWS ──────────────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <h3 class="font-semibold text-gray-800 text-base flex items-center gap-2">
            <i class="fa-solid fa-server text-green-500"></i>
            Equipment / Assets
        </h3>
        <button type="button" id="add-row"
                class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-2 rounded-lg transition">
            <i class="fa-solid fa-plus"></i>
            Add Row
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-sm" id="items-table">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <th class="px-3 py-2 text-left w-48">Linked Asset</th>
                    <th class="px-3 py-2 text-left">Description</th>
                    <th class="px-3 py-2 text-left w-32">Frequency</th>
                    <th class="px-3 py-2 text-center" colspan="12">Schedule Months</th>
                    <th class="px-3 py-2 w-8"></th>
                </tr>
                <tr class="bg-gray-50 text-gray-400 text-xs">
                    <th colspan="3"></th>
                    <?php foreach ($months as $mn): ?>
                        <th class="px-1 py-1 text-center font-normal"><?= $mn ?></th>
                    <?php endforeach; ?>
                    <th></th>
                </tr>
            </thead>
            <tbody id="items-body">
                <!-- starter row -->
                <?= renderItemRow(0, [], $allAssets, $months) ?>
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-400 mt-3">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Tick the months when maintenance is scheduled. If no months are ticked, defaults are auto-assigned based on frequency.
    </p>
</div>

<!-- ── ACTIONS ─────────────────────────────────────────────────────────────── -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
    <a href="<?= site_url($routePrefix . '/pm-plans') ?>"
       class="px-5 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">Cancel</a>
    <button type="submit"
            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">
        <i class="fa-solid fa-floppy-disk mr-1"></i> Save Plan
    </button>
</div>

</form>

<?php
/**
 * Helper – render one item/equipment row
 */
function renderItemRow(int $idx, array $item, array $assets, array $months): string
{
    $selectedAsset  = $item['asset_id'] ?? '';
    $description    = esc($item['description'] ?? '');
    $frequency      = $item['frequency'] ?? '';
    $schedMonths    = json_decode($item['schedule_months'] ?? '[]', true) ?: [];

    $freqOptions = [
        'quarterly'     => 'Quarterly (Q)',
        'semi_annually' => 'Semi-Annually (SA)',
        'monthly'       => 'Monthly',
        'annually'      => 'Annually',
        'as_needed'     => 'As-Needed',
    ];

    $html = '<tr class="border-t border-gray-100 item-row" data-idx="' . $idx . '">';

    // Asset search picker
    $assetLabel = '';
    foreach ($assets as $a) {
        if ($a['asset_id'] == $selectedAsset) {
            $assetLabel = ($a['brand_model'] ?? '') . ' (' . ($a['asset_tag'] ?? '') . ')';
            break;
        }
    }
    $html .= '<td class="px-2 py-2 asset-td" style="position:relative;min-width:200px">';
    $html .= '<input type="hidden" name="item_asset_id[]" class="asset-id-hidden" value="' . esc($selectedAsset) . '">';
    $html .= '<div class="relative">';
    $html .= '<input type="text" class="asset-search w-full border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400 pr-7" placeholder="Search asset…" autocomplete="off" value="' . esc($assetLabel) . '">';
    $html .= '<i class="fa-solid fa-magnifying-glass text-[10px] text-gray-300 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>';
    $html .= '</div>';
    $html .= '</td>';

    // Description
    $html .= '<td class="px-2 py-2">';
    $html .= '<input type="text" name="desc[]" value="' . $description . '" required '
           . 'placeholder="e.g. HP HRIS Server" '
           . 'class="auto-desc w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">';
    $html .= '</td>';

    // Frequency
    $html .= '<td class="px-2 py-2">';
    $html .= '<select name="freq[]" class="freq-select w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">';
    $html .= '<option value="">&mdash; Select &mdash;</option>';
    foreach ($freqOptions as $val => $label) {
        $sel   = ($val === $frequency) ? 'selected' : '';
        $html .= '<option value="' . $val . '" ' . $sel . '>' . esc($label) . '</option>';
    }
    $html .= '</select></td>';

    // Month checkboxes
    for ($m = 1; $m <= 12; $m++) {
        $checked = in_array($m, $schedMonths) ? 'checked' : '';
        $html .= '<td class="px-1 py-2 text-center">';
        $html .= '<input type="checkbox" name="months[' . $idx . '][]" value="' . $m . '" ' . $checked . ' '
               . 'class="w-4 h-4 rounded accent-blue-600 cursor-pointer">';
        $html .= '</td>';
    }

    // Remove
    $html .= '<td class="px-2 py-2 text-center">';
    $html .= '<button type="button" class="remove-row text-red-400 hover:text-red-600 transition" title="Remove row">'
           . '<i class="fa-solid fa-xmark text-sm"></i></button>';
    $html .= '</td>';

    $html .= '</tr>';
    return $html;
}
?>

<script>
window._pmAssets = <?= json_encode(array_map(function($a) {
    return [
        'id'    => $a['asset_id'],
        'label' => ($a['brand_model'] ?? '') . ' (' . ($a['asset_tag'] ?? '') . ')',
        'model' => $a['brand_model'] ?? '',
        'tag'   => $a['asset_tag'] ?? '',
    ];
}, $allAssets), JSON_HEX_TAG) ?>;
window._scheduledByYear = <?= json_encode(array_map(function($ids) {
    return array_values(array_unique($ids));
}, $scheduledByYear ?? []), JSON_HEX_TAG) ?>;
</script>

<script>
(function () {

    // ── Searchable asset picker (portal dropdown, no scroll clipping) ──
    let _activeSearch = null;

    // Shared portal dropdown appended once to body
    const _portal = document.createElement('div');
    _portal.id = 'asset-portal';
    _portal.style.cssText = 'display:none;position:fixed;z-index:99999;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.13);min-width:260px;max-height:280px;overflow-y:auto';
    document.body.appendChild(_portal);

    function _positionPortal(input) {
        const rect = input.getBoundingClientRect();
        _portal.style.width = Math.max(260, rect.width) + 'px';
        // Decide above or below
        const spaceBelow = window.innerHeight - rect.bottom;
        if (spaceBelow < 300 && rect.top > 300) {
            _portal.style.top  = (rect.top - _portal.offsetHeight - 4 + window.scrollY) + 'px';
        } else {
            _portal.style.top  = (rect.bottom + 4 + window.scrollY) + 'px';
        }
        _portal.style.left = (rect.left + window.scrollX) + 'px';
    }

    function _escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    function _getSelectedYear() {
        const inp = document.querySelector('[name="plan_year"]');
        return inp ? parseInt(inp.value, 10) || 0 : 0;
    }

    function _scheduledIds() {
        const yr  = _getSelectedYear();
        const map = window._scheduledByYear || {};
        return new Set((map[yr] || []).map(Number));
    }

    function _renderPortal(q, input, hidden) {
        const lower     = q.toLowerCase();
        const scheduled = _scheduledIds();
        const all = (window._pmAssets || []).filter(a =>
            a.label.toLowerCase().includes(lower) ||
            a.tag.toLowerCase().includes(lower) ||
            a.model.toLowerCase().includes(lower)
        );
        const available = all.filter(a => !scheduled.has(Number(a.id))).slice(0, 40);
        const taken     = all.filter(a =>  scheduled.has(Number(a.id))).slice(0, 20);

        if (!all.length && q.trim()) {
            _portal.innerHTML = '<div class="text-center py-5 text-gray-400 text-xs">'
                + '<i class="fa-solid fa-circle-xmark block text-gray-300 text-base mb-1"></i>No assets found</div>';
        } else if (!all.length) {
            _portal.innerHTML = '<div class="text-center py-5 text-gray-400 text-xs">'
                + '<i class="fa-solid fa-magnifying-glass block text-gray-300 text-base mb-1"></i>Type to search&hellip;</div>';
        } else {
            let html = '<div class="divide-y divide-gray-50">';
            // None option
            html += '<div class="asset-pick flex items-center gap-2.5 px-3 py-2.5 hover:bg-gray-50 cursor-pointer transition" data-id="" data-label="" data-model="">'
                + '<div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">'
                + '<i class="fa-solid fa-xmark text-gray-400 text-[9px]"></i></div>'
                + '<span class="text-xs text-gray-400">&mdash; None &mdash;</span></div>';
            // Available assets
            available.forEach(function(a) {
                html += '<div class="asset-pick flex items-center gap-2.5 px-3 py-2.5 hover:bg-blue-50 cursor-pointer transition"'
                    + ' data-id="' + a.id + '" data-label="' + _escHtml(a.label) + '" data-model="' + _escHtml(a.model) + '">'
                    + '<div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">'
                    + '<i class="fa-solid fa-desktop text-blue-400 text-[9px]"></i></div>'
                    + '<div class="flex-1 min-w-0">'
                    + '<p class="text-xs font-semibold text-gray-800 truncate">' + _escHtml(a.model) + '</p>'
                    + '<p class="text-[10px] text-gray-400 truncate">' + _escHtml(a.tag) + '</p>'
                    + '</div></div>';
            });
            // Already-scheduled assets (disabled)
            if (taken.length) {
                html += '<div class="px-3 pt-2 pb-1"><span class="text-[10px] font-semibold uppercase tracking-wider text-amber-500">'
                    + '<i class="fa-solid fa-calendar-check mr-1"></i>Already scheduled for ' + (_getSelectedYear() || 'this year') + '</span></div>';
                taken.forEach(function(a) {
                    html += '<div class="asset-pick-disabled flex items-center gap-2.5 px-3 py-2 opacity-50 cursor-not-allowed select-none" data-scheduled="1">'
                        + '<div class="w-6 h-6 rounded-full bg-amber-50 flex items-center justify-center flex-shrink-0">'
                        + '<i class="fa-solid fa-desktop text-amber-400 text-[9px]"></i></div>'
                        + '<div class="flex-1 min-w-0">'
                        + '<p class="text-xs font-semibold text-gray-500 truncate">' + _escHtml(a.model) + '</p>'
                        + '<p class="text-[10px] text-gray-400 truncate">' + _escHtml(a.tag) + '</p>'
                        + '</div>'
                        + '<span class="text-[9px] bg-amber-100 text-amber-600 rounded px-1.5 py-0.5 font-medium flex-shrink-0">Scheduled</span>'
                        + '</div>';
                });
            }
            html += '</div>';
            _portal.innerHTML = html;
        }
        _portal.style.display = '';
        _positionPortal(input);
    }

    _portal.addEventListener('mousedown', function(e) {
        const opt = e.target.closest('.asset-pick');
        if (!opt || opt.dataset.scheduled === '1') return;
        e.preventDefault();
        if (_activeSearch) {
            const { input, hidden } = _activeSearch;
            hidden.value  = opt.dataset.id;
            input.value   = opt.dataset.label || '';
            // Auto-fill description
            const row  = input.closest('tr');
            const desc = row ? row.querySelector('.auto-desc') : null;
            if (desc && opt.dataset.model) desc.value = opt.dataset.model;
        }
        _portal.style.display = 'none';
        _activeSearch = null;
    });

    document.addEventListener('click', function(e) {
        if (_activeSearch && !_activeSearch.td.contains(e.target)) {
            _portal.style.display = 'none';
            _activeSearch = null;
        }
    }, true);

    window.addEventListener('scroll', function() {
        if (_activeSearch) _positionPortal(_activeSearch.input);
    }, true);

    function initAssetSearch(td) {
        const hidden = td.querySelector('.asset-id-hidden');
        const input  = td.querySelector('.asset-search');
        if (!hidden || !input) return;

        input.addEventListener('focus', function() {
            _activeSearch = { td, input, hidden };
            _renderPortal(input.value, input, hidden);
        });
        input.addEventListener('input', function() {
            _activeSearch = { td, input, hidden };
            _renderPortal(input.value, input, hidden);
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { _portal.style.display = 'none'; _activeSearch = null; input.blur(); }
        });
    }

    // Init all existing rows
    document.querySelectorAll('#items-body .asset-td').forEach(initAssetSearch);
    let rowIndex = 1;

    // Rebuild month checkbox names after DOM changes so indexes stay correct
    function reindexRows() {
        document.querySelectorAll('#items-body .item-row').forEach(function(row, i) {
            row.dataset.idx = i;
            row.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
                cb.name = 'months[' + i + '][]';
            });
        });
        rowIndex = document.querySelectorAll('#items-body .item-row').length;
    }

    document.getElementById('add-row').addEventListener('click', function () {
        const tbody = document.getElementById('items-body');
        const template = document.querySelector('.item-row');
        const clone    = template.cloneNode(true);

        // Clear inputs in clone
        clone.querySelectorAll('input[type="text"]').forEach(el => el.value = '');
        clone.querySelectorAll('input[type="hidden"].asset-id-hidden').forEach(el => el.value = '');
        clone.querySelectorAll('select').forEach(el => el.value = el.options[0].value);
        clone.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

        tbody.appendChild(clone);
        reindexRows();
        // Init search on new row
        const newTd = clone.querySelector('.asset-td');
        if (newTd) initAssetSearch(newTd);
    });

    document.getElementById('items-body').addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            const rows = document.querySelectorAll('#items-body .item-row');
            if (rows.length <= 1) { alert('At least one equipment row is required.'); return; }
            e.target.closest('tr').remove();
            reindexRows();
        }
    });

    // Re-render portal when plan_year changes so scheduled filter updates
    const yearInput = document.querySelector('[name="plan_year"]');
    if (yearInput) {
        yearInput.addEventListener('change', function() {
            if (_activeSearch) {
                _renderPortal(_activeSearch.input.value, _activeSearch.input, _activeSearch.hidden);
            }
        });
    }

    // Auto-fill description from selected asset (handled inside initAssetSearch pickItem)
    // Kept as no-op for legacy safety

    // Auto-tick months based on frequency selection
    document.getElementById('items-body').addEventListener('change', function (e) {
        if (!e.target.classList.contains('freq-select')) return;
        const row   = e.target.closest('tr');
        const freq  = e.target.value;
        const idx   = parseInt(row.dataset.idx);
        const checks = row.querySelectorAll('input[type="checkbox"]');

        // Uncheck all
        checks.forEach(cb => cb.checked = false);

        const defaults = {
            'quarterly':     [1, 4, 7, 10],
            'semi_annually': [1, 7],
            'monthly':       [1,2,3,4,5,6,7,8,9,10,11,12],
            'annually':      [1],
            'as_needed':     [],
        };
        const months = defaults[freq] || [];
        checks.forEach(function(cb) {
            if (months.includes(parseInt(cb.value))) cb.checked = true;
        });
    });
})();
</script>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
