<?php
$pageTitle    = 'Edit PM Plan';
$pageSubtitle = 'Year ' . $plan['plan_year'] . ' – ' . $plan['title'];
$routePrefix  = str_starts_with(uri_string(), 'admin/') ? 'admin' : 'super-admin';

ob_start();
$errors = $validation->getErrors();
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
?>

<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="<?= site_url($routePrefix . '/pm-plans') ?>" class="hover:text-blue-600 transition">PM Plans</a>
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
    <a href="<?= site_url($routePrefix . '/pm-plans/show/' . $plan['plan_id']) ?>" class="hover:text-blue-600 transition">Plan <?= esc($plan['plan_year']) ?></a>
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
    <span class="text-gray-700 font-medium">Edit</span>
</nav>

<?php if (!empty($errors)): ?>
<div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
    <p class="font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-0.5">
        <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="post" action="<?= site_url($routePrefix . '/pm-plans/update/' . $plan['plan_id']) ?>" id="pm-form">
<?= csrf_field() ?>

<!-- ── PLAN HEADER ─────────────────────────────────────────────────────────── -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
    <h3 class="font-semibold text-gray-800 text-base mb-4 flex items-center gap-2">
        <i class="fa-solid fa-file-lines text-blue-500"></i>
        Plan Information
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Year <span class="text-red-500">*</span></label>
            <input type="number" name="plan_year" min="2000" max="2100"
                   value="<?= old('plan_year', $plan['plan_year']) ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Document Code</label>
            <input type="text" name="document_code"
                   value="<?= old('document_code', $plan['document_code']) ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Equipment Title <span class="text-red-500">*</span></label>
            <input type="text" name="title"
                   value="<?= old('title', $plan['title']) ?>"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Department / Sub-title</label>
            <input type="text" name="department"
                   value="<?= old('department', $plan['department']) ?>"
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
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Prepared By</p>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="prepared_by" value="<?= old('prepared_by', $plan['prepared_by']) ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Title / Designation</label>
                <input type="text" name="prepared_title" value="<?= old('prepared_title', $plan['prepared_title']) ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
        </div>
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Reviewed By</p>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="reviewed_by" value="<?= old('reviewed_by', $plan['reviewed_by']) ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Title / Designation</label>
                <input type="text" name="reviewed_title" value="<?= old('reviewed_title', $plan['reviewed_title']) ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
        </div>
        <div class="space-y-2">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Approved By</p>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="approved_by" value="<?= old('approved_by', $plan['approved_by']) ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Title / Designation</label>
                <input type="text" name="approved_title" value="<?= old('approved_title', $plan['approved_title']) ?>"
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
            <i class="fa-solid fa-plus"></i> Add Row
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
                <?php
                $existingItems = $plan['items'] ?? [];
                if (empty($existingItems)) {
                    echo renderEditRow(0, [], $allAssets, $months);
                } else {
                    foreach ($existingItems as $idx => $item) {
                        echo renderEditRow($idx, $item, $allAssets, $months);
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-400 mt-3">
        <i class="fa-solid fa-circle-info mr-1"></i>
        Tick the months when maintenance is scheduled.
    </p>
</div>

<!-- ── ACTIONS ─────────────────────────────────────────────────────────────── -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
    <a href="<?= site_url($routePrefix . '/pm-plans/show/' . $plan['plan_id']) ?>"
       class="px-5 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">Cancel</a>
    <button type="submit"
            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">
        <i class="fa-solid fa-floppy-disk mr-1"></i> Update Plan
    </button>
</div>

</form>

<?php
function renderEditRow(int $idx, array $item, array $assets, array $months): string
{
    $selectedAsset = $item['asset_id'] ?? '';
    $description   = esc($item['description'] ?? '');
    $frequency     = $item['frequency'] ?? 'quarterly';
    $schedMonths   = json_decode($item['schedule_months'] ?? '[]', true) ?: [];

    $freqOptions = [
        'quarterly'     => 'Quarterly (Q)',
        'semi_annually' => 'Semi-Annually (SA)',
        'monthly'       => 'Monthly',
        'annually'      => 'Annually',
        'as_needed'     => 'As-Needed',
    ];

    $html = '<tr class="border-t border-gray-100 item-row" data-idx="' . $idx . '">';

    $html .= '<td class="px-2 py-2">';
    $html .= '<select name="item_asset_id[]" class="asset-select w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">';
    $html .= '<option value="">— None —</option>';
    foreach ($assets as $a) {
        $sel   = ($a['asset_id'] == $selectedAsset) ? 'selected' : '';
        $label = esc($a['brand_model'] ?? '') . ' (' . esc($a['asset_tag'] ?? '') . ')';
        $html .= '<option value="' . $a['asset_id'] . '" ' . $sel . '>' . $label . '</option>';
    }
    $html .= '</select></td>';

    $html .= '<td class="px-2 py-2">';
    $html .= '<input type="text" name="desc[]" value="' . $description . '" required '
           . 'placeholder="e.g. HP HRIS Server" '
           . 'class="auto-desc w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">';
    $html .= '</td>';

    $html .= '<td class="px-2 py-2">';
    $html .= '<select name="freq[]" class="freq-select w-full border border-gray-200 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">';
    foreach ($freqOptions as $val => $label) {
        $sel   = ($val === $frequency) ? 'selected' : '';
        $html .= '<option value="' . $val . '" ' . $sel . '>' . esc($label) . '</option>';
    }
    $html .= '</select></td>';

    for ($m = 1; $m <= 12; $m++) {
        $checked = in_array($m, $schedMonths) ? 'checked' : '';
        $html .= '<td class="px-1 py-2 text-center">';
        $html .= '<input type="checkbox" name="months[' . $idx . '][]" value="' . $m . '" ' . $checked
               . ' class="w-4 h-4 rounded accent-blue-600 cursor-pointer">';
        $html .= '</td>';
    }

    $html .= '<td class="px-2 py-2 text-center">';
    $html .= '<button type="button" class="remove-row text-red-400 hover:text-red-600 transition" title="Remove row">'
           . '<i class="fa-solid fa-xmark text-sm"></i></button>';
    $html .= '</td>';
    $html .= '</tr>';
    return $html;
}
?>

<script>
(function () {
    let rowIndex = <?= count($plan['items'] ?? []) ?>;

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
        const tbody    = document.getElementById('items-body');
        const template = document.querySelector('.item-row');
        const clone    = template.cloneNode(true);

        clone.querySelectorAll('input[type="text"], select').forEach(el => el.value = el.tagName === 'SELECT' ? el.options[0].value : '');
        clone.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

        tbody.appendChild(clone);
        reindexRows();
    });

    document.getElementById('items-body').addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            const rows = document.querySelectorAll('#items-body .item-row');
            if (rows.length <= 1) { alert('At least one equipment row is required.'); return; }
            e.target.closest('tr').remove();
            reindexRows();
        }
    });

    // Auto-fill description from selected asset
    document.getElementById('items-body').addEventListener('change', function (e) {
        if (!e.target.classList.contains('asset-select')) return;
        const row  = e.target.closest('tr');
        const desc = row.querySelector('.auto-desc');
        if (!desc || desc.value.trim() !== '') return; // don't overwrite manual input
        const text = e.target.options[e.target.selectedIndex].text.trim();
        if (e.target.value === '') { return; }
        desc.value = text.replace(/\s*\([^)]*\)\s*$/, '').trim();
    });

    document.getElementById('items-body').addEventListener('change', function (e) {
        if (!e.target.classList.contains('freq-select')) return;
        const row    = e.target.closest('tr');
        const freq   = e.target.value;
        const checks = row.querySelectorAll('input[type="checkbox"]');

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
