<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM Checklist – <?= esc($record['group_name'] ?? '') ?> – <?= esc(date('F Y', strtotime($record['maintenance_date'] ?? 'now'))) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: Arial, Helvetica, sans-serif; box-sizing: border-box; }

        body { background: #e5e7eb; margin: 0; padding: 0; }

        @media print {
            .no-print  { display: none !important; }
            body       { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page      { width: 100% !important; margin: 0 !important; padding: 0.25in 0.3in !important;
                         box-shadow: none !important; }
            @page      { size: A4 landscape; margin: 0.2in; }
        }

        .page {
            width: 1060px;
            margin: 1.5rem auto;
            background: #fff;
            padding: 1rem 1.2rem;
            box-shadow: 0 4px 24px rgba(0,0,0,.12);
        }

        /* ── Document tables ──────────────────────────────── */
        table.doc { border-collapse: collapse; width: 100%; font-size: 9.5px; }
        table.doc th, table.doc td {
            border: 1px solid #1f2937;
            padding: 3px 5px;
            vertical-align: middle;
        }
        table.doc thead th { background: #f3f4f6; font-weight: 700; text-align: center; }

        /* activity / checklist table */
        table.ck { border-collapse: collapse; width: 100%; font-size: 8.5px; }
        table.ck th, table.ck td {
            border: 1px solid #374151;
            padding: 2px 4px;
            text-align: center;
            vertical-align: middle;
        }
        table.ck thead th { background: #e5e7eb; font-weight: 700; }
        table.ck td.act-label { text-align: left; white-space: nowrap; font-size: 8px; padding-left: 6px; }
        table.ck td.grp-span  { background: #dbeafe; font-weight: 700; font-size: 8.5px; }
        table.ck th.loc-span  { background: #dbeafe; font-weight: 700; }
        table.ck td.asset-hd  { background: #eff6ff; font-weight: 600; font-size: 7.5px; max-width: 55px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
        .tick-ok  { color: #1d4ed8; font-weight: 700; }
        .tick-no  { color: #dc2626; font-weight: 700; }
        table.ck td.remark-td { min-width: 80px; width: 80px; }

        /* Checkbox helpers */
        .cbox {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: 9px; margin-right: 10px; white-space: nowrap;
        }
        .cbox .sq {
            width: 10px; height: 10px; border: 1px solid #374151;
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 9px; line-height: 1;
        }
        .cbox.on .sq { background: #1d4ed8; border-color: #1d4ed8; color: white; }

        /* Corrective action table */
        table.ca { border-collapse: collapse; width: 100%; font-size: 8.5px; margin-top: 4px; }
        table.ca th, table.ca td {
            border: 1px solid #374151;
            padding: 3px 6px;
            vertical-align: top;
        }
        table.ca thead th { background: #e5e7eb; font-weight: 700; text-align: center; }
        table.ca td.dt  { width: 60px; }
        table.ca td.ca  { width: 35%; }
        table.ca td.rsp { width: 25%; }
        table.ca tr.body-row td { height: 16px; }
    </style>
</head>
<body>

<?php
$r           = $record;
$activities  = array_filter(array_map('trim', explode(',', $r['activities'] ?? '')));
$actBase     = array_unique(array_map(function($a) {
    return preg_match('/^Others:/i', $a) ? 'Others' : $a;
}, $activities));
$othersText  = '';
foreach ($activities as $a) {
    if (preg_match('/^Others:\s*([\s\S]+)/i', $a, $m)) {
        // Strip bullet characters (•) from each line
        $lines = array_map(function($l) {
            return trim(preg_replace('/^[•\-]\s*/', '', $l));
        }, explode("\n", trim($m[1])));
        $othersText = implode("\n", array_filter($lines));
        break;
    }
}

// Determine frequency flags
$freq     = strtolower($r['frequency'] ?? '');
$isMonth  = str_contains($freq, 'month');
$isQtr    = str_contains($freq, 'quarter');
$isSemi   = str_contains($freq, 'semi');
$isAnnual = $freq === 'annual' || str_contains($freq, 'annual') && !$isSemi;

// Equipment type flags
$eqType = strtolower($r['equipment_type'] ?? '');
$eqOpts = [
    'Vehicle'       => str_contains($eqType, 'vehicle'),
    'ACU'           => str_contains($eqType, 'acu'),
    'MIS Equipment' => str_contains($eqType, 'mis'),
    'Building'      => str_contains($eqType, 'build'),
];
$eqOthersOn   = !in_array(true, $eqOpts, true) && !empty($r['equipment_type']);
$eqOthersText = $eqOthersOn ? $r['equipment_type'] : '';
?>

<!-- ── TOOLBAR (no-print) ───────────────────────────────────── -->
<div class="no-print sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="<?= site_url('maintenance') ?>" class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Maintenance
        </a>
        <span class="text-gray-300">|</span>
        <span class="text-sm font-semibold text-gray-800">
            PM Checklist – <?= esc($r['group_name'] ?? '') ?>
            <?php if (!empty($r['group_code'])): ?><span class="text-gray-400">(<?= esc($r['group_code']) ?>)</span><?php endif; ?>
        </span>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= site_url("maintenance/show/{$r['maintenance_id']}") ?>" class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View Record
        </a>
        <button onclick="window.print()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / Save PDF
        </button>
    </div>
</div>

<div class="page">

    <!-- ── LETTERHEAD ────────────────────────────────────────── -->
    <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:4px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <div style="width:48px; height:48px; border-radius:50%; border:1.5px solid #6b7280; display:flex; align-items:center; justify-content:center; background:#f9fafb; font-size:7px; font-weight:700; color:#374151; text-align:center; line-height:1.2; flex-shrink:0;">
                CSPC<br>LOGO
            </div>
            <div style="font-size:9px; line-height:1.5;">
                <p style="margin:0; font-weight:600; color:#374151;">Republic of the Philippines</p>
                <p style="margin:0; font-weight:700; color:#111827;">CAMARINES SUR POLYTECHNIC COLLEGES</p>
                <p style="margin:0; color:#374151;">Nabua, Camarines Sur</p>
            </div>
        </div>
        <p style="font-size:9px; font-weight:700; color:#374151; margin-top:4px;">CSPC-F-ICTU-15</p>
    </div>

    <!-- ── TITLE ─────────────────────────────────────────────── -->
    <div style="text-align:center; border-bottom:1px solid #374151; padding-bottom:4px; margin-bottom:4px;">
        <p style="margin:0; font-size:13px; font-weight:700; letter-spacing:1px;">PREVENTIVE MAINTENANCE CHECKLIST</p>
    </div>
    <div style="text-align:right; font-size:9px; margin-bottom:4px;">
        Date: (Month/Year) <strong><?= date('F', strtotime($r['maintenance_date'] ?? 'now')) ?></strong>,&nbsp;<strong><?= date('Y', strtotime($r['maintenance_date'] ?? 'now')) ?></strong>
    </div>

    <!-- ── INSTRUCTION + EQ TYPE + FREQUENCY ─────────────────── -->
    <table class="doc" style="margin-bottom:4px;">
        <tbody>
            <tr>
                <td colspan="2" style="font-size:8.5px; font-style:italic; color:#374151;">
                    Tick appropriate box with (<strong>/</strong>) if checked item is ok.&nbsp; Put an (<strong>x</strong>) mark if item is not okay.
                </td>
            </tr>
            <tr>
                <!-- Equipment type -->
                <td style="width:68%; padding:5px 8px;">
                    <div style="display:flex; flex-wrap:wrap; align-items:center; gap:4px 0;">
                        <span style="font-size:9px; font-weight:700; margin-right:8px;">TYPE OF EQUIPMENT/ITEM</span>
                        <?php foreach ($eqOpts as $label => $on): ?>
                        <span class="cbox <?= $on ? 'on' : '' ?>">
                            <span class="sq"><?= $on ? '/' : '' ?></span> <?= esc($label) ?>
                        </span>
                        <?php endforeach; ?>
                        <span class="cbox <?= $eqOthersOn ? 'on' : '' ?>">
                            <span class="sq"><?= $eqOthersOn ? '/' : '' ?></span>
                            Others<?php if ($eqOthersOn && $eqOthersText): ?>, specify: <u><?= esc($eqOthersText) ?></u><?php else: ?>, specify: _______<?php endif; ?>
                        </span>
                    </div>
                </td>
                <!-- Frequency -->
                <td style="width:32%; padding:5px 8px;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:9px; font-weight:700; margin-right:6px;">FREQUENCY</span>
                        <div style="display:flex; gap:8px;">
                            <span class="cbox <?= $isMonth ? 'on' : '' ?>">
                                <span class="sq"><?= $isMonth ? '/' : '' ?></span> MONTH
                            </span>
                            <span class="cbox <?= $isQtr ? 'on' : '' ?>">
                                <span class="sq"><?= $isQtr ? '/' : '' ?></span> QUARTER
                            </span>
                            <span class="cbox <?= $isSemi ? 'on' : '' ?>">
                                <span class="sq"><?= $isSemi ? '/' : '' ?></span> SEMI-ANNUAL
                            </span>
                            <span class="cbox <?= $isAnnual ? 'on' : '' ?>">
                                <span class="sq"><?= $isAnnual ? '/' : '' ?></span> ANNUAL
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- ── CHECKLIST TABLE ────────────────────────────────────── -->
    <?php
    // Build location group → assets map
    // $assets is passed from controller, keyed by location_label => [asset array, ...]
    $locationGroups = $assetGroups ?? [];   // [['label'=>'...','assets'=>[...]], ...]
    $totalAssets    = array_sum(array_map(fn($g) => count($g['assets']), $locationGroups));
    ?>

    <table class="ck" style="margin-bottom:4px;">
        <thead>
            <!-- Row 1: ACTIVITIES | EQUIPMENT NO./ITEMS LOCATION spanning | REMARKS -->
            <tr>
                <th rowspan="3" style="width:110px; min-width:110px; text-align:center; font-size:9px;">ACTIVITIES</th>
                <?php if ($totalAssets > 0): ?>
                <th colspan="<?= $totalAssets ?>" class="loc-span" style="font-size:9px;">
                    EQUIPMENT NO./ITEMS LOCATION
                </th>
                <?php else: ?>
                <th style="font-size:9px;">EQUIPMENT NO./ITEMS LOCATION</th>
                <?php endif; ?>
                <th rowspan="3" class="remark-td" style="font-size:9px;">REMARKS</th>
            </tr>
            <!-- Row 2: Location group names spanning their asset columns -->
            <tr>
                <?php if (!empty($locationGroups)): ?>
                    <?php foreach ($locationGroups as $grp): ?>
                    <th colspan="<?= count($grp['assets']) ?>" class="loc-span" style="font-size:8px;">
                        <?= esc($grp['label']) ?>
                    </th>
                    <?php endforeach; ?>
                <?php else: ?>
                    <th style="font-size:8px; color:#9ca3af; font-style:italic;">No assets linked</th>
                <?php endif; ?>
            </tr>
            <!-- Row 3: Individual asset tags -->
            <tr>
                <?php if (!empty($locationGroups)): ?>
                    <?php foreach ($locationGroups as $grp): ?>
                        <?php foreach ($grp['assets'] as $asset): ?>
                        <th class="asset-hd" title="<?= esc($asset['brand_model'] ?? '') ?>" style="font-size:7px; max-width:55px;">
                            <?= esc($asset['asset_tag']) ?><br>
                            <span style="font-weight:400; color:#6b7280; font-size:6.5px;"><?= esc(substr($asset['brand_model'] ?? '', 0, 14)) ?></span>
                        </th>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <th style="color:#9ca3af;"></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actBase as $idx => $act): ?>
            <?php
            $isOthers = ($act === 'Others');
            $rowLabel = $isOthers ? ($othersText ?: 'Others') : $act;
            $bgRow    = ($idx % 2 === 0) ? '' : 'background:#f9fafb;';
            ?>
            <tr style="<?= $bgRow ?>">
                <td class="act-label"<?= $isOthers ? ' style="white-space:pre-line"' : '' ?>><?= esc($rowLabel) ?></td>
                <?php if (!empty($locationGroups)): ?>
                    <?php foreach ($locationGroups as $grp): ?>
                        <?php foreach ($grp['assets'] as $asset): ?>
                        <td style="<?= $bgRow ?>"><span class="tick-ok">/</span></td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <td><span class="tick-ok">/</span></td>
                <?php endif; ?>
                <td class="remark-td" style="<?= $bgRow ?>"></td>
            </tr>
            <?php endforeach; ?>

            <!-- Extra blank rows for manual use -->
            <?php for ($i = 0; $i < 4; $i++): ?>
            <tr>
                <td class="act-label" style="height:14px;"></td>
                <?php if ($totalAssets > 0): ?>
                    <?php for ($j = 0; $j < $totalAssets; $j++): ?><td></td><?php endfor; ?>
                <?php else: ?>
                    <td></td>
                <?php endif; ?>
                <td class="remark-td"></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- ── CONDUCTED / VERIFIED ──────────────────────────────── -->
    <table class="doc" style="margin-bottom:4px; font-size:9px;">
        <tbody>
            <tr>
                <td style="width:50%; padding:4px 8px;">
                    Conducted by: <strong><?= esc($r['conducted_by'] ?? '') ?></strong>
                </td>
                <td style="width:50%; padding:4px 8px;">
                    Date: <?= $r['conducted_date'] ? date('F d, Y', strtotime($r['conducted_date'])) : '' ?>
                </td>
            </tr>
            <tr>
                <td style="padding:4px 8px;">
                    Verified by: <strong><?= esc($r['verified_by'] ?? '') ?></strong>
                </td>
                <td style="padding:4px 8px;">
                    Date: <?= $r['verified_date'] ? date('F d, Y', strtotime($r['verified_date'])) : '' ?>
                </td>
            </tr>
            <?php if (!empty($r['remarks'])): ?>
            <tr>
                <td colspan="2" style="padding:4px 8px;">
                    Remarks: <?= esc($r['remarks']) ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ── CORRECTIVE ACTION TABLE ────────────────────────────── -->
    <table class="ca">
        <thead>
            <tr>
                <th class="dt">Date</th>
                <th class="ca">Corrective Action</th>
                <th class="rsp">Responsible</th>
                <th class="dt">Date</th>
                <th class="rmk">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($r['corrective_action'])): ?>
            <tr class="body-row">
                <td class="dt"><?= $r['corrective_date'] ? date('m/d/Y', strtotime($r['corrective_date'])) : '' ?></td>
                <td class="ca"><?= esc($r['corrective_action']) ?></td>
                <td class="rsp"><?= esc($r['responsible_person'] ?? '') ?></td>
                <td class="dt"><?= $r['responsible_date'] ? date('m/d/Y', strtotime($r['responsible_date'])) : '' ?></td>
                <td class="rmk"><?= esc($r['responsible_remarks'] ?? '') ?></td>
            </tr>
            <?php endif; ?>
            <?php $filledRows = !empty($r['corrective_action']) ? 1 : 0; ?>
            <?php for ($i = $filledRows; $i < 6; $i++): ?>
            <tr class="body-row"><td class="dt"></td><td class="ca"></td><td class="rsp"></td><td class="dt"></td><td class="rmk"></td></tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- ── FOOTER ────────────────────────────────────────────── -->
    <div style="display:flex; justify-content:space-between; margin-top:4px; font-size:8px; color:#6b7280;">
        <span>CSPC – ICTU</span>
        <span>Rev. 1</span>
        <span>Printed: <?= date('F d, Y') ?> &nbsp;|&nbsp; Page 1 of 1</span>
    </div>

</div><!-- /page -->
</body>
</html>
