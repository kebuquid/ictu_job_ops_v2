<?php
$pageTitle    = 'Edit Disposal Record';
$pageSubtitle = 'Update disposal details';
$routePrefix  = $routePrefix ?? (str_starts_with(uri_string(), 'admin/') ? 'admin' : 'super-admin');

ob_start();
$r = $record;
?>

<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="<?= site_url($routePrefix . '/disposals') ?>" class="hover:text-red-600 transition">Disposals</a>
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
    <a href="<?= site_url($routePrefix . "/disposals/show/{$r['disposal_id']}") ?>" class="hover:text-red-600 transition">Record #<?= $r['disposal_id'] ?></a>
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
    <span class="text-gray-700 font-medium">Edit</span>
</nav>

<?php if (isset($errors) && count($errors)): ?>
<div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm flex gap-3 items-start">
    <i class="fa-solid fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
    <ul class="list-disc list-inside space-y-0.5">
        <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= site_url($routePrefix . "/disposals/update/{$r['disposal_id']}") ?>" method="post" enctype="multipart/form-data" class="space-y-6">
    <?= csrf_field() ?>
    <input type="hidden" name="existing_disposal_image" value="<?= esc($r['disposal_image'] ?? '') ?>">
    <input type="file" name="disposal_image" id="disposal_image_input" accept="image/*" class="hidden" onchange="previewImage(this)">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center font-bold">1</span>
            Disposal Info
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Asset <span class="text-red-500">*</span></label>
                <select name="asset_id" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Asset --</option>
                    <?php foreach ($assets as $a): ?>
                        <option value="<?= $a['asset_id'] ?>"
                            <?= set_select('asset_id', (string)$a['asset_id'], $r['asset_id'] == $a['asset_id']) ?>>
                            <?= esc($a['asset_tag']) ?> — <?= esc($a['brand_model'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Disposal Date <span class="text-red-500">*</span></label>
                <input type="date" name="disposal_date"
                       value="<?= set_value('disposal_date', $r['disposal_date'] ?? '') ?>"
                       required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Condition at Disposal</label>
                <select name="condition_status"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Condition --</option>
                    <?php foreach (['Good', 'Fair', 'Poor', 'Beyond Repair', 'Lost', 'Stolen'] as $c): ?>
                        <option value="<?= $c ?>"
                            <?= set_select('condition_status', $c, ($r['condition_status'] ?? '') === $c) ?>>
                            <?= $c ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Approved By</label>
                <input type="hidden" name="approved_by" id="disp_approved_by_id"
                       value="<?= set_value('approved_by', $r['approved_by'] ?? '') ?>">
                <div class="relative">
                    <input type="text" id="disp_approved_by_search"
                        placeholder="Search user..."
                        autocomplete="off"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                        value="<?php
                            $pre = set_value('approved_by', $r['approved_by'] ?? '');
                            if ($pre) foreach ($users as $u) {
                                if ((string)$u['user_id'] === (string)$pre) { echo esc($u['name']); break; }
                            }
                        ?>">
                    <ul id="disp_approved_by_dropdown"
                        class="fixed z-[999] bg-white border border-gray-200 rounded-lg shadow-xl max-h-48 overflow-y-auto hidden text-sm">
                        <?php foreach ($users as $u): ?>
                            <li class="disp-user-option px-3 py-2 cursor-pointer hover:bg-red-50"
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
        </div>
        <div class="mt-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">Disposal Reason <span class="text-red-500">*</span></label>
            <textarea name="disposal_reason" required rows="4"
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"><?= set_value('disposal_reason', $r['disposal_reason'] ?? '') ?></textarea>
            <div id="kw-tip" class="hidden"></div>
        </div>
        <!-- Disposal Image -->
        <div class="mt-4">
            <label class="block text-xs font-medium text-gray-600 mb-1">Disposal Image <span class="text-gray-400 font-normal">(leave blank to keep existing)</span></label>
            <?php if (!empty($r['disposal_image'])): ?>
            <div class="mb-3 flex items-center gap-3">
                <img src="<?= base_url('uploads/disposals/' . esc($r['disposal_image'])) ?>" alt="Current"
                     class="h-20 w-20 rounded-xl object-cover border border-gray-200 shadow-sm">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Current image</p>
                    <p class="text-xs text-gray-400"><?= esc($r['disposal_image']) ?></p>
                </div>
            </div>
            <?php endif; ?>
            <div id="image-drop-zone"
                 class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-red-400 hover:bg-red-50 transition"
                 onclick="document.getElementById('disposal_image_input').click()"
                 ondragover="event.preventDefault();this.classList.add('border-red-400','bg-red-50')"
                 ondragleave="this.classList.remove('border-red-400','bg-red-50')"
                 ondrop="handleImageDrop(event)">
                <img id="image-preview" src="" alt="" class="hidden mx-auto mb-3 max-h-40 rounded-lg object-contain shadow">
                <div id="image-placeholder">
                    <i class="fa-solid fa-image text-3xl text-gray-300 mb-2 block"></i>
                    <p class="text-sm text-gray-400">Click or drag &amp; drop a new image</p>
                    <p class="text-xs text-gray-300 mt-1">JPG, PNG, WEBP &mdash; max 2MB</p>
                </div>
                <p id="image-filename" class="hidden text-xs text-gray-500 mt-2 truncate"></p>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
        <a href="<?= site_url($routePrefix . "/disposals/show/{$r['disposal_id']}") ?>"
           class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-800 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
            Cancel
        </a>
        <button type="submit"
                class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
            <i class="fa-solid fa-floppy-disk mr-1.5"></i>
            Update Disposal
        </button>
    </div>
</form>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
<script>
function previewImage(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('image-preview');
        prev.src = e.target.result;
        prev.classList.remove('hidden');
        document.getElementById('image-placeholder').classList.add('hidden');
        document.getElementById('image-filename').textContent = file.name;
        document.getElementById('image-filename').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}
function handleImageDrop(event) {
    event.preventDefault();
    event.currentTarget.classList.remove('border-red-400','bg-red-50');
    const file = event.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    const input = document.getElementById('disposal_image_input');
    input.files = dt.files;
    previewImage(input);
}

// Approved By user search picker
(function () {
    const searchInput = document.getElementById('disp_approved_by_search');
    const hiddenInput = document.getElementById('disp_approved_by_id');
    const dropdown    = document.getElementById('disp_approved_by_dropdown');
    const options     = Array.from(dropdown.querySelectorAll('.disp-user-option'));
    if (!searchInput) return;

    function positionDropdown() {
        const rect = searchInput.getBoundingClientRect();
        const dropH = dropdown.offsetHeight || 192;
        const spaceBelow = window.innerHeight - rect.bottom;
        dropdown.style.top   = (spaceBelow < dropH && rect.top > spaceBelow)
            ? (rect.top + window.scrollY - dropH - 4) + 'px'
            : (rect.bottom + window.scrollY + 4) + 'px';
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
        if (hasVisible) { positionDropdown(); dropdown.classList.remove('hidden'); }
        else dropdown.classList.add('hidden');
    }
    searchInput.addEventListener('input',  function () { hiddenInput.value = ''; showDropdown(this.value); });
    searchInput.addEventListener('focus',  function () { showDropdown(this.value); });
    window.addEventListener('scroll', function () { if (!dropdown.classList.contains('hidden')) positionDropdown(); }, true);
    options.forEach(li => {
        li.addEventListener('mousedown', function (e) {
            e.preventDefault();
            hiddenInput.value = this.dataset.id;
            searchInput.value = this.dataset.name;
            dropdown.classList.add('hidden');
        });
    });
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.add('hidden');
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
    const _C = { NICM: ['#f0fdf4','#bbf7d0','#166534','\uD83C\uDF10'], ICTRAM: ['#fffbeb','#fde68a','#92400e','\uD83D\uDDA5'], MIS: ['#faf5ff','#e9d5ff','#6b21a8','\uD83D\uDD11'] };
    const _ta = document.querySelector('[name="disposal_reason"]');
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
