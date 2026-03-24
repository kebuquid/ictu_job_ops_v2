<?php
$pageTitle    = 'Edit Asset  ' . esc($asset['asset_tag']);
$pageSubtitle = 'Update asset information';
$a = $asset;

$routePrefix = 'super-admin';
if (str_starts_with(uri_string(), 'admin/')) {
    $routePrefix = 'admin';
} elseif (!str_starts_with(uri_string(), 'super-admin/')) {
    $sess = session()->get('user');
    if (isset($sess['role_id']) && (int) $sess['role_id'] === 2) {
        $routePrefix = 'admin';
    }
}

ob_start();
?>

<div class="max-w-2xl mx-auto">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="<?= site_url($routePrefix . '/assets') ?>" class="hover:text-blue-600 transition">Assets</a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <a href="<?= site_url($routePrefix . "/assets/show/{$a['asset_id']}") ?>" class="hover:text-blue-600 transition"><?= esc($a['asset_tag']) ?></a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
        <span class="text-gray-700 font-medium">Edit</span>
    </nav>

    <!-- Validation errors -->
    <?php if (isset($validation) && $validation->getErrors()): ?>
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <div class="flex items-center gap-2 text-red-700 font-medium text-sm mb-1">
            <i class="fa-solid fa-triangle-exclamation"></i> Please fix the following errors:
        </div>
        <ul class="list-disc list-inside text-sm text-red-600 space-y-0.5">
            <?php foreach ($validation->getErrors() as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Step Indicators -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8 relative">
        <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 z-0">
            <div id="progress-bar" class="h-full bg-blue-500 transition-all duration-500" style="width:0%"></div>
        </div>
        <?php
        $steps = [
            ['icon' => 'fa-tag',       'label' => 'Identification'],
            ['icon' => 'fa-peso-sign', 'label' => 'Financial'],
            ['icon' => 'fa-user-tag',  'label' => 'Assignment'],
            ['icon' => 'fa-eye',       'label' => 'Review'],
        ];
        foreach ($steps as $i => $step):
            $n = $i + 1;
        ?>
        <div class="relative z-10 flex flex-col items-center" data-step="<?= $n ?>">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                <?= $n === 1 ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-300 text-gray-400' ?>"
                id="step-circle-<?= $n ?>">
                <i class="fa-solid <?= $step['icon'] ?> text-xs" id="step-icon-<?= $n ?>"></i>
            </div>
            <span class="mt-2 text-xs font-medium <?= $n === 1 ? 'text-blue-600' : 'text-gray-400' ?> transition-colors duration-300"
                  id="step-label-<?= $n ?>"><?= $step['label'] ?></span>
        </div>
        <?php endforeach; ?>
    </div>

    <form action="<?= site_url($routePrefix . "/assets/update/{$a['asset_id']}") ?>" method="post" id="asset-form" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <!-- Preserve existing image filename so it is never lost if no new file is chosen -->
        <input type="hidden" name="existing_asset_image" value="<?= esc($a['asset_image'] ?? '') ?>">
        <!-- File input lives here (outside all step panels) so it is never reset by display:none -->
        <input type="file" name="asset_image" id="asset_image_input" accept="image/*" class="hidden" onchange="previewImage(this)">

        <!-- STEP 1: Identification -->
        <div class="step-panel" id="panel-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-tag text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800">Identification</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 1 of 4</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Asset Tag <span class="text-red-500">*</span></label>
                        <input type="text" name="asset_tag" value="<?= set_value('asset_tag', $a['asset_tag']) ?>" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Property No.</label>
                        <input type="text" name="property_no" value="<?= set_value('property_no', $a['property_no']) ?>"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Brand / Model</label>
                        <input type="text" name="brand_model" value="<?= set_value('brand_model', $a['brand_model']) ?>"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Serial Number</label>
                        <input type="text" name="serial_number" value="<?= set_value('serial_number', $a['serial_number']) ?>"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                        <input type="text" name="category" value="<?= set_value('category', $a['category']) ?>"
                            oninput="toggleSoftwareSection(this.value)"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Software & Operating System (hardware only) -->
            <?php
            $swListData   = $softwares ?? [];
            $swListData   = is_array($swListData) ? array_values($swListData) : [];
            $hasSoftwareData = !empty($a['operating_system']) || !empty($a['os_license_key'])
                             || !empty($a['os_license_type'])  || !empty($a['os_license_expiry'])
                             || !empty($a['os_last_updated'])  || !empty($a['os_is_updated'])
                             || !empty($a['software_installed']) || !empty($a['software_license'])
                             || !empty($swListData);
            ?>
            <div id="software-os-section" <?= $hasSoftwareData ? '' : 'class="hidden"' ?>>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-laptop-code text-violet-500"></i>
                    <h3 class="font-semibold text-gray-800">Software &amp; Operating System</h3>
                    <span class="ml-auto text-xs text-gray-400 font-normal">Optional</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Operating System</label>
                        <input type="text" name="operating_system"
                            value="<?= set_value('operating_system', $a['operating_system'] ?? '') ?>"
                            placeholder="e.g. Windows 11 Pro, Ubuntu 22.04"
                            list="os-list"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <datalist id="os-list">
                            <option value="Windows 11 Pro">
                            <option value="Windows 11 Home">
                            <option value="Windows 10 Pro">
                            <option value="Windows 10 Home">
                            <option value="macOS Sonoma">
                            <option value="macOS Ventura">
                            <option value="Ubuntu 22.04 LTS">
                            <option value="Ubuntu 20.04 LTS">
                            <option value="Debian 12">
                            <option value="Red Hat Enterprise Linux">
                            <option value="No OS">
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">OS License Key</label>
                        <input type="text" name="os_license_key"
                            value="<?= set_value('os_license_key', $a['os_license_key'] ?? '') ?>"
                            placeholder="XXXXX-XXXXX-XXXXX-XXXXX"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                    <!-- OS License Type -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">OS License Type</label>
                        <select name="os_license_type"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                            <option value="">-- Select Type --</option>
                            <?php foreach (['Subscription','Perpetual','Open Source','Freeware','Trial'=>'Trial / Evaluation','Volume'=>'Volume License','OEM'] as $val => $label):
                                if (is_int($val)) { $val = $label; }
                                $sel = (set_value('os_license_type', $a['os_license_type'] ?? '') === $val) ? 'selected' : ''; ?>
                            <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- OS Dates -->
                    <div id="os-expiry-wrap">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">OS License Expiration</label>
                        <input type="date" name="os_license_expiry"
                            value="<?= set_value('os_license_expiry', $a['os_license_expiry'] ?? '') ?>"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                    <div id="os-lastupdated-wrap">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">OS Last Updated</label>
                        <input type="date" name="os_last_updated"
                            value="<?= set_value('os_last_updated', $a['os_last_updated'] ?? '') ?>"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                    </div>
                    <!-- OS Updated toggle -->
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input type="hidden" name="os_is_updated" value="0">
                            <input type="checkbox" name="os_is_updated" value="1"
                                <?= !empty($a['os_is_updated']) ? 'checked' : '' ?>
                                class="w-4 h-4 accent-violet-600 rounded">
                            <span class="text-sm font-medium text-gray-700">OS is currently up-to-date</span>
                        </label>
                    </div>

                    <!-- Divider -->
                    <div class="sm:col-span-2 border-t border-gray-100 pt-1">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Installed Software</p>
                            <button type="button" onclick="promptAddSoftware()"
                                class="flex items-center gap-1.5 text-xs font-semibold text-violet-600 hover:text-violet-800 transition">
                                <i class="fa-solid fa-plus"></i> Add Software
                            </button>
                        </div>
                    </div>

                    <!-- Shared datalist for software names -->
                    <datalist id="software-name-list">
                        <option value="Microsoft Office 365">
                        <option value="Microsoft Office 2021">
                        <option value="Microsoft Office 2019">
                        <option value="Microsoft Word">
                        <option value="Microsoft Excel">
                        <option value="Microsoft PowerPoint">
                        <option value="Microsoft Outlook">
                        <option value="Microsoft Teams">
                        <option value="Microsoft Visio">
                        <option value="Microsoft Project">
                        <option value="Adobe Acrobat Reader">
                        <option value="Adobe Acrobat Pro">
                        <option value="Adobe Photoshop">
                        <option value="Adobe Illustrator">
                        <option value="Adobe Premiere Pro">
                        <option value="Google Chrome">
                        <option value="Mozilla Firefox">
                        <option value="Microsoft Edge">
                        <option value="7-Zip">
                        <option value="WinRAR">
                        <option value="VLC Media Player">
                        <option value="Zoom">
                        <option value="Slack">
                        <option value="Skype">
                        <option value="TeamViewer">
                        <option value="AnyDesk">
                        <option value="Visual Studio Code">
                        <option value="Notepad++">
                        <option value="Git">
                        <option value="Python">
                        <option value="Node.js">
                        <option value="Java JDK">
                        <option value="XAMPP">
                        <option value="WampServer">
                        <option value="Kaspersky Antivirus">
                        <option value="Norton Antivirus">
                        <option value="Malwarebytes">
                        <option value="AutoCAD">
                        <option value="QuickBooks">
                        <option value="SAP">
                    </datalist>

                    <!-- Dynamic software entries -->
                    <div class="sm:col-span-2" id="software-entries-list">
                        <!-- entries injected by JS -->
                    </div>

                    <!-- Existing software rows seed for JS -->
                    <script>
                    window._existingSoftwareList = <?= json_encode($swListData) ?>;
                    </script>

                </div>
            </div>
            </div><!-- /#software-os-section -->

            <div class="flex flex-col sm:flex-row sm:justify-between gap-3">
                <a href="<?= site_url($routePrefix . "/assets/show/{$a['asset_id']}") ?>" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="button" onclick="goStep(2)" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Next <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: Financial & Dates -->
        <div class="step-panel hidden" id="panel-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-peso-sign text-green-500"></i>
                    <h3 class="font-semibold text-gray-800">Financial &amp; Dates</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 2 of 4</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date Acquired</label>
                        <input type="date" name="date_acquired" value="<?= set_value('date_acquired', $a['date_acquired']) ?>"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Warranty End</label>
                        <input type="date" name="warranty_end" value="<?= set_value('warranty_end', $a['warranty_end']) ?>"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Acquisition Cost (&#8369;)</label>
                        <input type="number" name="acquisition_cost" step="0.01" min="0"
                            value="<?= set_value('acquisition_cost', $a['acquisition_cost']) ?>" placeholder="0.00"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Depreciation Cost (&#8369;)</label>
                        <input type="number" name="depreciation_cost" step="0.01" min="0"
                            value="<?= set_value('depreciation_cost', $a['depreciation_cost'] ?? '') ?>" placeholder="0.00"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <!-- Procurement Information -->
                    <div class="sm:col-span-2 border-t border-gray-100 pt-4 mt-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-file-invoice text-violet-400"></i> Procurement Information
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Supplier / Vendor</label>
                                <input type="text" name="supplier" value="<?= set_value('supplier', $a['supplier'] ?? '') ?>" placeholder="e.g. ABC Trading Co."
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">PO Number</label>
                                <input type="text" name="po_number" value="<?= set_value('po_number', $a['po_number'] ?? '') ?>" placeholder="Purchase Order No."
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Invoice Number</label>
                                <input type="text" name="invoice_number" value="<?= set_value('invoice_number', $a['invoice_number'] ?? '') ?>" placeholder="Invoice / DR No."
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mode of Procurement</label>
                                <select name="procurement_mode"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Select Mode --</option>
                                    <?php foreach (['Public Bidding','Shopping','Small Value Procurement','Direct Contracting','Negotiated Procurement','Donation','Transfer / Turnover','Other'] as $pm): ?>
                                        <option value="<?= $pm ?>" <?= set_select('procurement_mode', $pm, ($a['procurement_mode'] ?? '') === $pm) ?>><?= $pm ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Fund Source</label>
                                <input type="text" name="fund_source" value="<?= set_value('fund_source', $a['fund_source'] ?? '') ?>" placeholder="e.g. GAA, Trust Fund"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Lifecycle / Notes</label>
                        <textarea name="lifecycle" rows="3"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?= set_value('lifecycle', $a['lifecycle']) ?></textarea>
                        <div id="kw-tip" class="hidden"></div>
                    </div>
                    <!-- Asset Image -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Asset Image <span class="text-gray-400 font-normal">(leave blank to keep existing)</span></label>
                        <?php if (!empty($a['asset_image'])): ?>
                        <div class="mb-3 flex items-center gap-3">
                            <img src="<?= base_url('uploads/assets/' . esc($a['asset_image'])) ?>" alt="Current image"
                                 class="h-20 w-20 rounded-xl object-cover border border-gray-200 shadow-sm">
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Current image</p>
                                <p class="text-xs text-gray-400"><?= esc($a['asset_image']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div id="image-drop-zone"
                             class="relative border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition group"
                             onclick="document.getElementById('asset_image_input').click()"
                             ondragover="event.preventDefault();this.classList.add('border-blue-400','bg-blue-50')"
                             ondragleave="this.classList.remove('border-blue-400','bg-blue-50')"
                             ondrop="handleImageDrop(event)">
                            <img id="image-preview" src="" alt="" class="hidden mx-auto mb-3 max-h-40 rounded-lg object-contain shadow">
                            <div id="image-placeholder">
                                <i class="fa-solid fa-image text-3xl text-gray-300 mb-2 block"></i>
                                <p class="text-sm text-gray-400">Click or drag &amp; drop a new image</p>
                                <p class="text-xs text-gray-300 mt-1">JPG, PNG, WEBP — max 2MB</p>
                            </div>
                            <p id="image-filename" class="hidden text-xs text-gray-500 mt-2 truncate"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-between gap-3">
                <button type="button" onclick="goStep(1)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="button" onclick="goStep(3)" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Next <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: Status & Assignment -->
        <div class="step-panel hidden" id="panel-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-user-tag text-indigo-500"></i>
                    <h3 class="font-semibold text-gray-800">Status &amp; Assignment</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 3 of 4</span>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Status</label>
                        <div class="grid grid-cols-2 gap-2">
                            <?php foreach (['Active', 'Inactive', 'Under Repair', 'Disposed'] as $s):
                                $colors = [
                                    'Active'       => 'has-[:checked]:bg-green-50 has-[:checked]:border-green-400 text-green-700',
                                    'Inactive'     => 'has-[:checked]:bg-gray-50 has-[:checked]:border-gray-400 text-gray-600',
                                    'Under Repair' => 'has-[:checked]:bg-yellow-50 has-[:checked]:border-yellow-400 text-yellow-700',
                                    'Disposed'     => 'has-[:checked]:bg-red-50 has-[:checked]:border-red-400 text-red-600',
                                ];
                                $icons = ['Active'=>'fa-circle-check','Inactive'=>'fa-circle-xmark','Under Repair'=>'fa-wrench','Disposed'=>'fa-trash'];
                            ?>
                            <label class="flex items-center gap-2.5 border-2 border-gray-200 rounded-xl px-4 py-3 cursor-pointer transition <?= $colors[$s] ?>">
                                <input type="radio" name="status" value="<?= $s ?>" <?= set_radio('status', $s, ($a['status'] ?? '') === $s) ?> class="accent-blue-600">
                                <i class="fa-solid <?= $icons[$s] ?> text-sm"></i>
                                <span class="text-sm font-medium"><?= $s ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Building -->
                    <?php
                    $currentUnitId   = $a['assigned_unit_id'] ?? null;
                    foreach ($units as $u) {
                        if ($u['unit_id'] == $currentUnitId) {
                            break;
                        }
                    }

                    $groupUnitMap = [];
                    foreach (($groups ?? []) as $g) {
                        $gid = (string) ($g['group_id'] ?? '');
                        $uid = (string) ($g['assigned_unit_id'] ?? '');
                        if ($gid === '' || $uid === '') {
                            $groupUnitMap[$gid] = ['unit' => '', 'building' => '', 'unit_name' => '', 'building_name' => ''];
                            continue;
                        }

                        $bid = '';
                        $unitName = '';
                        foreach ($units as $u) {
                            if ((string) $u['unit_id'] === $uid) {
                                $bid = (string) $u['building_id'];
                                $unitName = (string) ($u['name'] ?? '');
                                break;
                            }
                        }

                        $buildingName = '';
                        foreach ($buildings as $b) {
                            if ((string) $b['building_id'] === $bid) {
                                $buildingName = (string) ($b['name'] ?? '');
                                break;
                            }
                        }

                        $groupUnitMap[$gid] = ['unit' => $uid, 'building' => $bid, 'unit_name' => $unitName, 'building_name' => $buildingName];
                    }
                    ?>
                    <input type="hidden" name="assigned_unit_id" id="assigned_unit_id" value="<?= esc((string) ($a['assigned_unit_id'] ?? '')) ?>">
                    <!-- Group -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Asset Group</label>
                        <select name="group_id" id="group_select" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Group (optional) --</option>
                            <?php foreach (($groups ?? []) as $g): ?>
                                <?php
                                    $gid = (string) $g['group_id'];
                                    $map = $groupUnitMap[$gid] ?? ['unit' => '', 'building' => '', 'unit_name' => '', 'building_name' => ''];
                                ?>
                                <option value="<?= $g['group_id'] ?>"
                                    data-unit="<?= esc($map['unit']) ?>"
                                    data-building="<?= esc($map['building']) ?>"
                                    data-unit-name="<?= esc($map['unit_name']) ?>"
                                    data-building-name="<?= esc($map['building_name']) ?>"
                                    <?= set_select('group_id', (string)$g['group_id'], ($a['group_id'] ?? '') == $g['group_id']) ?>>
                                    <?= esc($g['group_name']) ?> (<?= esc($g['group_code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Assigned To</label>
                            <?php $selectedAssignedTo = (string) set_value('assigned_to', $a['assigned_to'] ?? ''); ?>
                            <input type="hidden" name="assigned_to" id="assigned_to_id" value="<?= esc($selectedAssignedTo) ?>">
                            <div class="relative">
                                <input type="text" id="assigned_to_search" name="assigned_to_search"
                                    placeholder="Search user..."
                                    autocomplete="off"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="<?php
                                        if ($selectedAssignedTo) {
                                            foreach (($users ?? []) as $u) {
                                                if ((string)$u['user_id'] === $selectedAssignedTo) {
                                                    echo esc($u['name']);
                                                    break;
                                                }
                                            }
                                        }
                                    ?>">
                                <ul id="user_dropdown"
                                    class="fixed z-[999] bg-white border border-gray-200 rounded-lg shadow-xl max-h-48 overflow-y-auto hidden text-sm">
                                    <?php foreach (($users ?? []) as $u): ?>
                                        <li class="user-option px-3 py-2 cursor-pointer hover:bg-blue-50"
                                            data-id="<?= $u['user_id'] ?>"
                                            data-name="<?= esc($u['name']) ?>"
                                            data-email="<?= esc($u['email']) ?>">
                                            <span class="font-medium text-gray-800"><?= esc($u['name']) ?></span>
                                            <span class="text-xs text-gray-400 ml-1"><?= esc($u['email']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <p id="assigned_to_validation" class="mt-1 text-xs text-gray-500">Type a name or email to validate the assignee.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Section</label>
                            <?php $currentSectionId = set_value('section_id', (string)($a['section_id'] ?? '')); ?>
                            <select name="section_id"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Select Section --</option>
                                <?php foreach ($sections as $s): ?>
                                    <option value="<?= $s['section_id'] ?>" <?= $currentSectionId === (string)$s['section_id'] ? 'selected' : '' ?>>
                                        <?= esc($s['acronym']) ?> – <?= esc($s['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-between gap-3">
                <button type="button" onclick="goStep(2)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="button" onclick="goReview()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    Review <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- STEP 4: Review & Confirm -->
        <div class="step-panel hidden" id="panel-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-purple-500"></i>
                    <h3 class="font-semibold text-gray-800">Review &amp; Confirm</h3>
                    <span class="ml-auto text-xs text-gray-400">Step 4 of 4</span>
                </div>
                <div class="p-6 space-y-5">
                    <p class="text-sm text-gray-500">Please review the details below before saving.</p>

                    <!-- Identification -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-tag text-blue-400"></i> Identification
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Asset Tag</span><p class="text-sm font-medium text-gray-800" id="rv-asset_tag"></p></div>
                            <div><span class="text-xs text-gray-400">Property No.</span><p class="text-sm font-medium text-gray-800" id="rv-property_no"></p></div>
                            <div><span class="text-xs text-gray-400">Brand / Model</span><p class="text-sm font-medium text-gray-800" id="rv-brand_model"></p></div>
                            <div><span class="text-xs text-gray-400">Serial Number</span><p class="text-sm font-medium text-gray-800 font-mono" id="rv-serial_number"></p></div>
                            <div class="col-span-2"><span class="text-xs text-gray-400">Category</span><p class="text-sm font-medium text-gray-800" id="rv-category"></p></div>
                            <!-- Image preview in review -->
                            <div class="col-span-2" id="rv-image-wrap" style="display:none">
                                <span class="text-xs text-gray-400">Asset Image</span>
                                <img id="rv-image" src="" alt="Asset Image" class="mt-1.5 max-h-32 rounded-lg object-contain border border-gray-100 shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Financial -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-peso-sign text-green-400"></i> Financial &amp; Dates
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Date Acquired</span><p class="text-sm font-medium text-gray-800" id="rv-date_acquired"></p></div>
                            <div><span class="text-xs text-gray-400">Warranty End</span><p class="text-sm font-medium text-gray-800" id="rv-warranty_end"></p></div>
                            <div><span class="text-xs text-gray-400">Acquisition Cost</span><p class="text-sm font-medium text-gray-800" id="rv-acquisition_cost"></p></div>
                            <div><span class="text-xs text-gray-400">Depreciation Cost</span><p class="text-sm font-medium text-gray-800" id="rv-depreciation_cost"></p></div>
                            <div class="col-span-2"><span class="text-xs text-gray-400">Lifecycle / Notes</span><p class="text-sm font-medium text-gray-800 whitespace-pre-line" id="rv-lifecycle"></p></div>
                        </div>
                    </div>

                    <!-- Procurement -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-file-invoice text-violet-400"></i> Procurement Information
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div class="col-span-2"><span class="text-xs text-gray-400">Supplier / Vendor</span><p class="text-sm font-medium text-gray-800" id="rv-supplier"></p></div>
                            <div><span class="text-xs text-gray-400">PO Number</span><p class="text-sm font-medium text-gray-800" id="rv-po_number"></p></div>
                            <div><span class="text-xs text-gray-400">Invoice Number</span><p class="text-sm font-medium text-gray-800" id="rv-invoice_number"></p></div>
                            <div><span class="text-xs text-gray-400">Mode of Procurement</span><p class="text-sm font-medium text-gray-800" id="rv-procurement_mode"></p></div>
                            <div><span class="text-xs text-gray-400">Fund Source</span><p class="text-sm font-medium text-gray-800" id="rv-fund_source"></p></div>
                        </div>
                    </div>

                    <!-- Assignment -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-tag text-indigo-400"></i> Status &amp; Assignment
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 bg-gray-50 rounded-xl p-4">
                            <div><span class="text-xs text-gray-400">Status</span><p class="text-sm font-medium text-gray-800" id="rv-status"></p></div>
                            <div><span class="text-xs text-gray-400">Asset Group</span><p class="text-sm font-medium text-gray-800" id="rv-group_id"></p></div>
                            <div><span class="text-xs text-gray-400">Building</span><p class="text-sm font-medium text-gray-800" id="rv-building"></p></div>
                            <div><span class="text-xs text-gray-400">Organizational Unit</span><p class="text-sm font-medium text-gray-800" id="rv-assigned_unit_id"></p></div>
                            <div><span class="text-xs text-gray-400">Assigned To</span><p class="text-sm font-medium text-gray-800" id="rv-assigned_to"></p></div>
                        </div>
                    </div>

                    <!-- Software & OS -->
                    <div id="rv-software-section" style="display:none">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-laptop-code text-violet-400"></i> Software &amp; Operating System
                        </h4>
                        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                            <div id="rv-os-block" style="display:none" class="grid grid-cols-2 gap-x-6 gap-y-2">
                                <div><span class="text-xs text-gray-400">Operating System</span><p class="text-sm font-medium text-gray-800" id="rv-operating_system"></p></div>
                                <div><span class="text-xs text-gray-400">OS License Key</span><p class="text-sm font-medium font-mono text-gray-800" id="rv-os_license_key"></p></div>
                                <div><span class="text-xs text-gray-400">OS License Type</span><p class="text-sm font-medium text-gray-800" id="rv-os_license_type"></p></div>
                                <div><span class="text-xs text-gray-400">OS License Expiry</span><p class="text-sm font-medium text-gray-800" id="rv-os_license_expiry"></p></div>
                                <div><span class="text-xs text-gray-400">OS Last Updated</span><p class="text-sm font-medium text-gray-800" id="rv-os_last_updated"></p></div>
                                <div><span class="text-xs text-gray-400">OS Up-to-date</span><p class="text-sm font-medium text-gray-800" id="rv-os_is_updated"></p></div>
                            </div>
                            <div id="rv-sw-list">
                                <!-- software rows injected by goReview() -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-between gap-3">
                <button type="button" onclick="goStep(3)" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Back
                </button>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Update Asset
                </button>
            </div>
        </div>

    </form>
</div>

<script>
let currentStep = 1;
const totalSteps = 4;

function goStep(n) {
    if (n > 1 && currentStep === 1) {
        const tag = document.querySelector('[name="asset_tag"]');
        if (!tag.value.trim()) {
            tag.focus();
            tag.classList.add('border-red-400', 'ring-2', 'ring-red-300');
            setTimeout(() => tag.classList.remove('border-red-400', 'ring-2', 'ring-red-300'), 2000);
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
    const tag = document.querySelector('[name="asset_tag"]');
    if (!tag.value.trim()) {
        goStep(1);
        tag.focus();
        tag.classList.add('border-red-400', 'ring-2', 'ring-red-300');
        setTimeout(() => tag.classList.remove('border-red-400', 'ring-2', 'ring-red-300'), 2000);
        return;
    }

    function fv(name) {
        const el = document.querySelector('[name="' + name + '"]');
        if (!el) return '';
        if (el.tagName === 'SELECT') return el.options[el.selectedIndex]?.text || '';
        return el.value.trim() || '';
    }

    ['asset_tag','property_no','brand_model','serial_number','category',
     'date_acquired','warranty_end','lifecycle',
     'supplier','po_number','invoice_number','procurement_mode','fund_source'].forEach(f => {
        const el = document.getElementById('rv-' + f);
        if (el) el.textContent = fv(f);
    });

    const assignedName = document.getElementById('assigned_to_search')?.value?.trim();
    const assignedId = document.getElementById('assigned_to_id')?.value?.trim();
    if (assignedName && !assignedId) {
        const hint = document.getElementById('assigned_to_validation');
        if (hint) {
            hint.textContent = 'Please choose a valid assignee. No matching user was validated.';
            hint.className = 'mt-1 text-xs text-red-600';
        }
        goStep(3);
        document.getElementById('assigned_to_search')?.focus();
        return;
    }
    document.getElementById('rv-assigned_to').textContent = assignedName || '—';

    const statusEl = document.querySelector('[name="status"]:checked');
    document.getElementById('rv-status').textContent = statusEl ? statusEl.value : '';

    const groupSel = document.querySelector('[name="group_id"]');
    document.getElementById('rv-group_id').textContent =
        groupSel && groupSel.value ? groupSel.options[groupSel.selectedIndex].text : '';

    // Image preview in review
    const rvWrap = document.getElementById('rv-image-wrap');
    const rvImg  = document.getElementById('rv-image');
    if (window._assetImageDataUrl) {
        rvImg.src = window._assetImageDataUrl;
        rvWrap.style.display = '';
    } else {
        rvWrap.style.display = 'none';
    }

    const groupForUnit = document.getElementById('group_select');
    const selectedGroupOpt = groupForUnit ? groupForUnit.options[groupForUnit.selectedIndex] : null;
    const selectedBuildingName = selectedGroupOpt?.dataset?.buildingName || '';
    const selectedUnitName = selectedGroupOpt?.dataset?.unitName || '';
    document.getElementById('rv-building').textContent = selectedBuildingName || '';
    document.getElementById('rv-assigned_unit_id').textContent = selectedUnitName || '';

    ['acquisition_cost','depreciation_cost'].forEach(f => {
        const el  = document.getElementById('rv-' + f);
        const raw = document.querySelector('[name="' + f + '"]')?.value;
        el.textContent = raw ? '₱ ' + parseFloat(raw).toLocaleString('en-PH', {minimumFractionDigits:2}) : '';
    });

    // ── Software & OS Review ──────────────────────────────
    const osVal     = document.querySelector('[name="operating_system"]')?.value?.trim() || '';
    const swEntries = document.querySelectorAll('.software-entry');
    const hasSwOs   = osVal || swEntries.length > 0;
    const rvSwSec   = document.getElementById('rv-software-section');
    rvSwSec.style.display = hasSwOs ? '' : 'none';

    if (hasSwOs) {
        const rvOsBlock = document.getElementById('rv-os-block');
        rvOsBlock.style.display = osVal ? '' : 'none';
        if (osVal) {
            document.getElementById('rv-operating_system').textContent  = osVal || '—';
            document.getElementById('rv-os_license_key').textContent    = fv('os_license_key');
            document.getElementById('rv-os_license_type').textContent   = fv('os_license_type');
            document.getElementById('rv-os_license_expiry').textContent = fv('os_license_expiry');
            document.getElementById('rv-os_last_updated').textContent   = fv('os_last_updated');
            const isUpd = document.querySelector('[name="os_is_updated"][type="checkbox"]');
            document.getElementById('rv-os_is_updated').textContent     = isUpd?.checked ? '✔ Yes' : 'No';
        }

        const rvSwList = document.getElementById('rv-sw-list');
        rvSwList.innerHTML = '';
        swEntries.forEach((entry, idx) => {
            const detailRow = entry.nextElementSibling && entry.nextElementSibling.classList.contains('sw-detail')
                ? entry.nextElementSibling
                : null;
            const readField = (selector) =>
                entry.querySelector(selector) || detailRow?.querySelector(selector);

            const name    = readField('[name$="[name]"]')?.value?.trim();
            if (!name) return;
            const licType = readField('[name$="[license_type]"]');
            const expiry  = readField('[name$="[license_expiry]"]')?.value?.trim();
            const updated = readField('[name$="[last_updated]"]')?.value?.trim();
            const isUpd   = readField('[name$="[is_updated]"][type="checkbox"]')?.checked;
            const notes   = readField('[name$="[notes]"]')?.value?.trim();
            const licLabel = licType ? (licType.options[licType.selectedIndex]?.text || '—') : '—';
            const row = document.createElement('div');
            row.className = (idx > 0 ? 'border-t border-gray-200 pt-3 mt-1 ' : '') + 'grid grid-cols-2 gap-x-6 gap-y-1';
            row.innerHTML = `
                <div class="col-span-2"><span class="text-xs text-gray-400">Software</span><p class="text-sm font-semibold text-gray-800">${name}</p></div>
                <div><span class="text-xs text-gray-400">License Type</span><p class="text-sm font-medium text-gray-800">${licLabel}</p></div>
                <div><span class="text-xs text-gray-400">Expiry</span><p class="text-sm font-medium text-gray-800">${expiry || '—'}</p></div>
                <div><span class="text-xs text-gray-400">Last Updated</span><p class="text-sm font-medium text-gray-800">${updated || '—'}</p></div>
                <div><span class="text-xs text-gray-400">Up-to-date</span><p class="text-sm font-medium text-gray-800">${isUpd ? '✔ Yes' : 'No'}</p></div>
                ${notes ? `<div class="col-span-2"><span class="text-xs text-gray-400">Notes</span><p class="text-sm text-gray-700 whitespace-pre-line">${notes}</p></div>` : ''}
            `;
            rvSwList.appendChild(row);
        });
    }

    goStep(4);
}

function updateIndicators(n) {
    const icons = ['fa-tag','fa-peso-sign','fa-user-tag','fa-eye'];
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

// ── Dynamic Software Entries ──────────────────────────────
let swIndex = 0;

const LICENSE_OPTIONS = [
    ['', '-- Select Type --'],
    ['Subscription',  'Subscription'],
    ['Perpetual',     'Perpetual'],
    ['Open Source',   'Open Source'],
    ['Freeware',      'Freeware'],
    ['Trial',         'Trial / Evaluation'],
    ['Volume',        'Volume License'],
    ['OEM',           'OEM'],
];

function buildLicenseSelect(name) {
    const opts = LICENSE_OPTIONS.map(([v, t]) =>
        `<option value="${v}">${t}</option>`
    ).join('');
    return `<select name="${name}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
        ${opts}
    </select>`;
}

function addSoftwareEntry(data = {}) {
    const i = swIndex++;
    const wrap = document.createElement('div');
    wrap.className = 'software-entry bg-violet-50 border border-violet-100 rounded-xl p-4 mb-3 relative';
    wrap.dataset.index = i;
    wrap.innerHTML = `
        <button type="button" onclick="removeSoftwareEntry(this)"
            class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition text-xs">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Software Name</label>
                <input type="text" name="software_list[${i}][name]" value="${data.name||''}"
                    placeholder="e.g. Microsoft Office 365"
                    list="software-name-list"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">License Type</label>
                ${buildLicenseSelect(`software_list[${i}][license_type]`)}
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">License Expiration</label>
                <input type="date" name="software_list[${i}][license_expiry]" value="${data.license_expiry||''}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Last Updated</label>
                <input type="date" name="software_list[${i}][last_updated]" value="${data.last_updated||''}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div class="flex items-center">
                <label class="flex items-center gap-2.5 cursor-pointer select-none mt-1">
                    <input type="hidden" name="software_list[${i}][is_updated]" value="0">
                    <input type="checkbox" name="software_list[${i}][is_updated]" value="1"
                        ${data.is_updated == 1 ? 'checked' : ''}
                        class="w-4 h-4 accent-violet-600 rounded">
                    <span class="text-sm font-medium text-gray-700">Currently up-to-date</span>
                </label>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1">License Key / Notes</label>
                <textarea name="software_list[${i}][notes]" rows="2"
                    placeholder="License key, subscription ID, or any notes"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 resize-none">${data.notes||''}</textarea>
            </div>
        </div>`;
    if (data.license_type) {
        const sel = wrap.querySelector(`[name="software_list[${i}][license_type]"]`);
        if (sel) sel.value = data.license_type;
    }
    document.getElementById('software-entries-list').appendChild(wrap);
}

function removeSoftwareEntry(btn) {
    btn.closest('.software-entry').remove();
}

function promptAddSoftware() {
    const existing = document.getElementById('sw-count-modal');
    if (existing) { existing.remove(); }
    const modal = document.createElement('div');
    modal.id = 'sw-count-modal';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-xl p-6 w-80 mx-4">
            <h3 class="text-sm font-bold text-gray-800 mb-1 flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-violet-500"></i> Add Software Entries
            </h3>
            <p class="text-xs text-gray-400 mb-4">How many software entries do you want to add?</p>
            <div class="flex items-center gap-3 mb-5">
                <button type="button" onclick="adjustSwCount(-1)"
                    class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition font-bold text-lg">−</button>
                <input type="number" id="sw-add-count" value="1" min="1" max="20"
                    class="flex-1 text-center border border-gray-200 rounded-lg px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-violet-500">
                <button type="button" onclick="adjustSwCount(1)"
                    class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition font-bold text-lg">+</button>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('sw-count-modal').remove()"
                    class="flex-1 py-2 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="button" onclick="confirmAddSoftware()"
                    class="flex-1 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold transition">Add</button>
            </div>
        </div>`;
    document.body.appendChild(modal);
    modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
    document.getElementById('sw-add-count').focus();
    document.getElementById('sw-add-count').select();
}

function adjustSwCount(delta) {
    const inp = document.getElementById('sw-add-count');
    inp.value = Math.min(20, Math.max(1, (parseInt(inp.value) || 1) + delta));
}

function confirmAddSoftware() {
    const count = Math.min(20, Math.max(1, parseInt(document.getElementById('sw-add-count').value) || 1));
    for (let i = 0; i < count; i++) addSoftwareEntry();
    document.getElementById('sw-count-modal').remove();
}

// ── Software & OS: show only for hardware categories (or when data already exists) ──────
const HARDWARE_KEYWORDS = ['computer','laptop','desktop','server','workstation','tablet','electronics','hardware','printer','scanner','monitor','ups','network','switch','router','cpu','pc'];
function isHardwareCategory(val) {
    const lower = val.trim().toLowerCase();
    return HARDWARE_KEYWORDS.some(k => lower.includes(k));
}
function toggleSoftwareSection(val) {
    const sec = document.getElementById('software-os-section');
    if (!sec) return;
    const hasExistingData = ['operating_system','os_license_key','os_license_type','os_last_updated','software_license']
        .some(n => (document.querySelector('[name="' + n + '"]')?.value?.trim() || '') !== '')
        || document.querySelectorAll('.software-entry').length > 0;
    sec.classList.toggle('hidden', !isHardwareCategory(val) && !hasExistingData);
}
document.addEventListener('DOMContentLoaded', () => {
    // Seed existing software entries from PHP
    const existing = window._existingSoftwareList || [];
    if (existing.length > 0) {
        existing.forEach(sw => addSoftwareEntry(sw));
    } else {
        addSoftwareEntry(); // blank row if nothing saved
    }
    const cat = document.querySelector('[name="category"]');
    if (cat) toggleSoftwareSection(cat.value);
});

// Group-driven location auto-fill (no manual Building/Unit selection)
(function () {
    const groupSel    = document.getElementById('group_select');
    const assignedUnitInput = document.getElementById('assigned_unit_id');
    if (!groupSel || !assignedUnitInput) return;

    function applyGroupLocation() {
        const selected = groupSel.options[groupSel.selectedIndex];
        if (!selected) return;

        assignedUnitInput.value = selected.dataset.unit || '';
    }

    groupSel.addEventListener('change', applyGroupLocation);

    if (groupSel.value) {
        applyGroupLocation();
    }
})();

// User live-search picker
(function () {
    const searchInput = document.getElementById('assigned_to_search');
    const hiddenInput = document.getElementById('assigned_to_id');
    const dropdown    = document.getElementById('user_dropdown');
    const hint        = document.getElementById('assigned_to_validation');
    const endpoint    = '<?= site_url($routePrefix . '/assets/check-user-api') ?>';
    if (!searchInput || !hiddenInput || !dropdown || !hint) return;

    const options = Array.from(dropdown.querySelectorAll('.user-option'));
    let validateTimer = null;
    let requestToken = 0;

    function setHint(kind, text) {
        if (kind === 'ok') {
            hint.className = 'mt-1 text-xs text-green-600';
        } else if (kind === 'warn') {
            hint.className = 'mt-1 text-xs text-amber-600';
        } else if (kind === 'error') {
            hint.className = 'mt-1 text-xs text-red-600';
        } else {
            hint.className = 'mt-1 text-xs text-gray-500';
        }
        hint.textContent = text;
    }

    function positionDropdown() {
        const rect = searchInput.getBoundingClientRect();
        const dropH = dropdown.offsetHeight || 192;
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;

        if (spaceBelow < dropH && spaceAbove > spaceBelow) {
            dropdown.style.top = (rect.top + window.scrollY - dropH - 4) + 'px';
        } else {
            dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
        }

        dropdown.style.left = rect.left + 'px';
        dropdown.style.width = rect.width + 'px';
    }

    function showDropdown(filter) {
        const q = String(filter || '').toLowerCase();
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

    async function validateTypedUser(force = false) {
        const q = searchInput.value.trim();
        if (!q) {
            hiddenInput.value = '';
            setHint('muted', 'Type a name or email to validate the assignee.');
            return false;
        }

        if (hiddenInput.value && !force) {
            setHint('ok', 'Valid assignee selected.');
            return true;
        }

        if (!force && q.length < 3) {
            setHint('warn', 'Keep typing to validate this assignee.');
            return false;
        }

        const token = ++requestToken;
        setHint('muted', 'Validating assignee...');

        try {
            const res = await fetch(endpoint + '?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (token !== requestToken) return false;

            if (data.valid && data.user_id) {
                hiddenInput.value = String(data.user_id);
                if (data.name) searchInput.value = data.name;
                setHint('ok', data.reason || 'Valid assignee found.');
                return true;
            }

            hiddenInput.value = '';
            setHint('error', data.reason || 'No valid assignee found for this input.');
            return false;
        } catch (_) {
            if (token !== requestToken) return false;
            hiddenInput.value = '';
            setHint('error', 'Unable to validate right now. Please try again.');
            return false;
        }
    }

    searchInput.addEventListener('input', function () {
        hiddenInput.value = '';
        showDropdown(this.value);
        clearTimeout(validateTimer);
        validateTimer = setTimeout(() => { validateTypedUser(false); }, 350);
    });

    searchInput.addEventListener('focus', function () {
        showDropdown(this.value);
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            validateTypedUser(true);
        }
    });

    searchInput.addEventListener('blur', function () {
        clearTimeout(validateTimer);
        validateTimer = setTimeout(() => { validateTypedUser(true); }, 150);
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
            setHint('ok', 'Valid assignee selected.');
        });
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
            if (!hiddenInput.value) searchInput.value = '';
        }
    });

    document.getElementById('asset-form')?.addEventListener('submit', async function (e) {
        const hasTypedValue = !!searchInput.value.trim();
        if (!hasTypedValue) return;
        if (hiddenInput.value) return;

        e.preventDefault();
        const ok = await validateTypedUser(true);
        if (!ok) {
            searchInput.focus();
            return;
        }
        this.submit();
    });

    if (hiddenInput.value) {
        setHint('ok', 'Valid assignee selected.');
    }
})();

// ── Image upload helpers ──────────────────────────────────
window._assetImageDataUrl = null;
function previewImage(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        window._assetImageDataUrl = e.target.result;
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
    event.currentTarget.classList.remove('border-blue-400','bg-blue-50');
    const file = event.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const dt = new DataTransfer();
    dt.items.add(file);
    const input = document.getElementById('asset_image_input');
    input.files = dt.files;
    previewImage(input);
}

// ── OS License Type: hide date fields for Perpetual ─────────
(function () {
    const sel = document.querySelector('[name="os_license_type"]');
    const expiryWrap  = document.getElementById('os-expiry-wrap');
    const updatedWrap = document.getElementById('os-lastupdated-wrap');
    if (!sel || !expiryWrap || !updatedWrap) return;
    function toggle() {
        const hide = sel.value === 'Perpetual';
        expiryWrap.style.display  = hide ? 'none' : '';
        updatedWrap.style.display = hide ? 'none' : '';
        if (hide) {
            const exp = document.querySelector('[name="os_license_expiry"]');
            if (exp) exp.value = '';
        }
    }
    sel.addEventListener('change', toggle);
    toggle();
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
    const _ta = document.querySelector('[name="lifecycle"]');
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
