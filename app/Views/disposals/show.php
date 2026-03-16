<?php
$pageTitle    = 'Disposal Record';
$pageSubtitle = 'Disposal #' . $record['disposal_id'];

ob_start();
$r = $record;
$condColor = match(strtolower($r['condition_status'] ?? '')) {
    'good'                 => 'bg-green-100 text-green-700',
    'fair'                 => 'bg-yellow-100 text-yellow-700',
    'poor', 'beyond repair'=> 'bg-red-100 text-red-700',
    'lost', 'stolen'       => 'bg-gray-200 text-gray-600',
    default                => 'bg-gray-100 text-gray-600',
};
?>

<nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
    <a href="<?= site_url('disposals') ?>" class="hover:text-red-600 transition">Disposals</a>
    <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
    <span class="text-gray-700 font-medium">Disposal #<?= $r['disposal_id'] ?></span>
</nav>

<style>
#disposal-grid{display:grid;gap:1.5rem;grid-template-columns:1fr}
@media(min-width:768px){#disposal-grid{grid-template-columns:3fr 2fr !important}}
</style>
<div id="disposal-grid">
    <div class="space-y-5">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-trash-can text-white text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-red-200 text-xs font-medium uppercase tracking-wider mb-0.5">Asset Tag</p>
                            <h2 class="text-2xl font-bold text-white font-mono"><?= esc($r['asset_tag'] ?? 'Unknown Asset') ?></h2>
                            <p class="text-red-200 text-sm mt-0.5"><?= esc($r['brand_model'] ?? '') ?></p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold ring-1 <?= $condColor ?> ring-current flex-shrink-0 mt-1">
                        <?= esc($r['condition_status'] ?? 'Unknown') ?>
                    </span>
                </div>
            </div>
            <div class="px-6 py-4 grid grid-cols-2 sm:grid-cols-3 gap-5">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5 flex items-center gap-1">
                        <i class="fa-solid fa-calendar-xmark text-gray-300"></i> Disposed On
                    </p>
                    <p class="text-sm font-semibold text-gray-800">
                        <?= $r['disposal_date'] ? date('F d, Y', strtotime($r['disposal_date'])) : '—' ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5 flex items-center gap-1">
                        <i class="fa-solid fa-user-check text-gray-300"></i> Approved By
                    </p>
                    <p class="text-sm font-semibold text-gray-800">
                        <?= $r['approved_by'] ? 'User #' . $r['approved_by'] : '—' ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5 flex items-center gap-1">
                        <i class="fa-solid fa-hashtag text-gray-300"></i> Disposal ID
                    </p>
                    <p class="text-sm font-semibold text-gray-800 font-mono">#<?= $r['disposal_id'] ?></p>
                </div>
            </div>
        </div>

        <!-- Reason -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
                <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
                Disposal Reason
            </h3>
            <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap"><?= esc($r['disposal_reason'] ?? '—') ?></p>
        </div>

        <!-- Disposal Image -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                <i class="fa-solid fa-image text-red-400"></i> Disposal Image
            </h3>
            <?php if (!empty($r['disposal_image'])): ?>
            <img src="<?= base_url('uploads/disposals/' . esc($r['disposal_image'])) ?>" alt="Disposal Image"
                 class="w-full rounded-xl object-contain max-h-64 border border-gray-100 bg-gray-50">
            <?php else: ?>
            <div class="w-full h-32 rounded-xl bg-gray-50 border border-dashed border-gray-200 flex flex-col items-center justify-center gap-1.5">
                <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
                <p class="text-xs text-gray-400">No image uploaded</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="<?= site_url("disposals/edit/{$r['disposal_id']}") ?>"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-xl transition font-medium">
                    <i class="fa-solid fa-pencil w-4 text-center"></i>
                    Edit Record
                </a>
                <a href="<?= site_url("assets/show/{$r['asset_id']}") ?>"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-xl transition font-medium">
                    <i class="fa-solid fa-box-open w-4 text-center"></i>
                    View Asset
                </a>
                <a href="<?= site_url("disposals/delete/{$r['disposal_id']}") ?>"
                   class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl transition font-medium"
                   onclick="return confirm('Delete this disposal record?')">
                    <i class="fa-solid fa-trash w-4 text-center"></i>
                    Delete Record
                </a>
                <a href="<?= site_url('disposals') ?>"
                       class="flex items-center gap-3 w-full px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-600 font-medium text-sm transition">
                        <i class="fa-solid fa-arrow-left w-4 text-center"></i>
                        Back to List
                    </a>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Timestamp</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400">Recorded</p>
                    <p class="text-sm text-gray-700"><?= $r['created_at'] ? date('M d, Y h:i A', strtotime($r['created_at'])) : '—' ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Updated On</p>
                    <p class="text-sm text-gray-700"><?= !empty($r['updated_at']) ? date('M d, Y h:i A', strtotime($r['updated_at'])) : '—' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$pageContent = ob_get_clean();
echo view('assets/layout', compact('pageTitle', 'pageSubtitle', 'pageContent'));
?>
