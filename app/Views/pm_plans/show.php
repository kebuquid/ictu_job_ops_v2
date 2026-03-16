<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM Plan <?= esc($plan['plan_year']) ?> – <?= esc($plan['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        * { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }

        @media print {
            .no-print  { display: none !important; }
            body       { background: white !important; }
            .page      { box-shadow: none !important; margin: 0 !important; padding: 0.5in 0.55in !important; }
            table      { page-break-inside: avoid; }
        }

        .page {
            max-width: 820px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem 2.5rem;
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
        }

        table.pm-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
        }
        table.pm-table th,
        table.pm-table td {
            border: 1px solid #374151;
            padding: 4px 6px;
            text-align: center;
            vertical-align: middle;
        }
        table.pm-table td.desc-cell {
            text-align: left;
            white-space: nowrap;
        }
        table.pm-table thead tr th {
            background: #f3f4f6;
            font-weight: 700;
        }
        .q-mark  { font-weight: 700; color: #1e40af; font-size: 12px; }
        .sa-mark { font-weight: 700; color: #065f46; font-size: 12px; }
        .m-mark  { font-weight: 600; color: #92400e; font-size: 10px; }
        .a-mark  { font-weight: 700; color: #6b21a8; font-size: 12px; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Toolbar (hidden on print) -->
<div class="no-print sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="<?= site_url('super-admin/pm-plans') ?>"
           class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-arrow-left"></i> Back to Plans
        </a>
        <span class="text-gray-300">|</span>
        <span class="text-sm font-semibold text-gray-800">
            PM Plan <?= esc($plan['plan_year']) ?> – <?= esc($plan['title']) ?>
        </span>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= site_url('super-admin/pm-plans/edit/' . $plan['plan_id']) ?>"
           class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-pencil text-xs"></i> Edit
        </a>
        <button onclick="window.print()"
                class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-print text-xs"></i> Print
        </button>
        <button onclick="savePdf(this)"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="fa-solid fa-file-pdf text-xs"></i> Save as PDF
        </button>
    </div>
</div>

<div class="page">

    <!-- ── HEADER ──────────────────────────────────────────────────────────── -->
    <div class="flex items-start justify-between mb-1">
        <!-- Logo placeholder -->
        <div class="flex items-center gap-2">
            <div class="w-14 h-14 rounded-full border-2 border-gray-400 flex items-center justify-center bg-gray-100 text-gray-500 text-xs text-center leading-tight font-bold">
                CSPC<br>LOGO
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold">Republic of the Philippines</p>
                <p class="text-xs font-bold text-gray-800">CAMARINES SUR POLYTECHNIC COLLEGES</p>
                <p class="text-xs text-gray-600">Nabua, Camarines Sur</p>
            </div>
        </div>
        <p class="text-xs font-semibold text-gray-700 mt-1"><?= esc($plan['document_code'] ?? 'CSPC-F-ICTU-13') ?></p>
    </div>

    <div class="text-center mb-4 mt-2">
        <p class="text-sm font-bold uppercase tracking-wide">Preventive Maintenance Plan</p>
        <p class="text-sm font-bold uppercase"><?= esc($plan['title']) ?></p>
        <?php if (!empty($plan['department'])): ?>
        <p class="text-sm font-semibold"><?= esc($plan['department']) ?></p>
        <?php endif; ?>
        <p class="text-xs font-semibold">YEAR <?= esc($plan['plan_year']) ?></p>
    </div>

    <!-- ── SCHEDULE TABLE ──────────────────────────────────────────────────── -->
    <?php
    $monthNames  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $freqLabel   = [
        'quarterly'     => 'Q',
        'semi_annually' => 'SA',
        'monthly'       => 'M',
        'annually'      => 'A',
        'as_needed'     => '—',
    ];
    $freqClass   = [
        'quarterly'     => 'q-mark',
        'semi_annually' => 'sa-mark',
        'monthly'       => 'm-mark',
        'annually'      => 'a-mark',
        'as_needed'     => '',
    ];
    ?>

    <table class="pm-table">
        <thead>
            <tr>
                <th rowspan="2" class="desc-cell text-left px-3 py-2" style="min-width:160px;">DESCRIPTION</th>
                <th colspan="12">
                    <?= strtoupper(esc($plan['title'])) ?> EQUIPMENT
                </th>
            </tr>
            <tr>
                <?php foreach ($monthNames as $mn): ?>
                    <th style="min-width:32px;"><?= $mn ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($plan['items'])): ?>
                <tr>
                    <td colspan="13" class="py-4 text-gray-400 italic">No equipment rows added.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($plan['items'] as $item): ?>
                <?php
                    $schedMonths = json_decode($item['schedule_months'] ?? '[]', true) ?: [];
                    $freq        = $item['frequency'] ?? 'quarterly';
                    $mark        = $freqLabel[$freq]  ?? '';
                    $cls         = $freqClass[$freq]  ?? '';
                ?>
                <tr>
                    <td class="desc-cell" style="padding-left: 8px;"><?= esc($item['description']) ?></td>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <td>
                            <?php if (in_array($m, $schedMonths)): ?>
                                <span class="<?= $cls ?>"><?= $mark ?></span>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Nothing follows row -->
            <tr>
                <td class="desc-cell italic text-gray-500 text-xs" style="padding-left: 8px;">--Nothing follows--</td>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <td></td>
                <?php endfor; ?>
            </tr>
        </tbody>
    </table>

    <!-- ── LEGEND ──────────────────────────────────────────────────────────── -->
    <div class="mt-4 text-xs">
        <strong>LEGEND:</strong>
        <span class="ml-3"><strong class="sa-mark">SA</strong> – Semi Annually</span>
        <span class="ml-4"><strong class="q-mark">Q</strong> – Quarterly</span>
        <span class="ml-4"><strong class="m-mark">M</strong> – Monthly</span>
        <span class="ml-4"><strong class="a-mark">A</strong> – Annually</span>
    </div>

    <!-- ── SIGNATORIES ─────────────────────────────────────────────────────── -->
    <table class="pm-table mt-6">
        <tr>
            <td class="w-1/3 py-3 px-4 text-left">
                <p class="text-xs font-semibold text-gray-600 mb-6">Prepared by:</p>
                <p class="font-bold text-xs uppercase"><?= esc($plan['prepared_by'] ?? '') ?></p>
                <p class="text-xs"><?= esc($plan['prepared_title'] ?? '') ?></p>
                <p class="text-xs text-gray-500 mt-1">Date: _______________</p>
            </td>
            <td class="w-1/3 py-3 px-4 text-left">
                <p class="text-xs font-semibold text-gray-600 mb-6">Reviewed by:</p>
                <p class="font-bold text-xs uppercase"><?= esc($plan['reviewed_by'] ?? '') ?></p>
                <p class="text-xs"><?= esc($plan['reviewed_title'] ?? '') ?></p>
                <p class="text-xs text-gray-500 mt-1">Date: _______________</p>
            </td>
            <td class="w-1/3 py-3 px-4 text-left">
                <p class="text-xs font-semibold text-gray-600 mb-6">Approved by:</p>
                <p class="font-bold text-xs uppercase"><?= esc($plan['approved_by'] ?? '') ?></p>
                <p class="text-xs"><?= esc($plan['approved_title'] ?? '') ?></p>
                <p class="text-xs text-gray-500 mt-1">Date: _______________</p>
            </td>
        </tr>
    </table>

    <!-- ── FOOTER ──────────────────────────────────────────────────────────── -->
    <div class="flex items-center justify-between mt-4 text-xs text-gray-400">
        <span>Jan</span>
        <span>Rev.1</span>
        <span>Page: 1 of 1</span>
    </div>

</div><!-- /page -->

<script>
function savePdf(btn) {
    const filename = 'PM-Plan-<?= esc($plan['plan_year']) ?>-<?= esc(preg_replace('/[^A-Za-z0-9]+/', '-', $plan['title'])) ?>.pdf';
    const element  = document.querySelector('.page');

    // Button loading state
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Generating…';

    const opt = {
        margin:       [0.4, 0.45, 0.4, 0.45],
        filename:     filename,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true, logging: false },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
    };

    html2pdf().set(opt).from(element).save().then(function() {
        btn.disabled = false;
        btn.innerHTML = orig;
    });
}
</script>
</body>
</html>
