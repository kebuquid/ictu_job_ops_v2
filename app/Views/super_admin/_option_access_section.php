<?php
/**
 * Partial: renders one section's option-access table.
 *
 * Expected variables:
 *  $sectionLabel  – e.g. "ICTRAM — ICT Repair and Maintenance"
 *  $iconColor     – gradient classes
 *  $acronym       – e.g. "ICTRAM"
 *  $items         – array of option rows
 *  $optionType    – e.g. 'request_type'
 *  $pkField       – PK column name in the source table
 *  $nameField     – display-name column
 *  $roles         – [ roleId => label ]
 *  $matrix        – [ roleId => [ optionId => is_enabled ] ]
 *  (optional) $parentLabel, $parentMap, $parentFk, $parentName – for platforms
 */
?>

<div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-indigo-100/50 shadow-lg overflow-hidden mb-6">
  <!-- Section header -->
  <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex items-center gap-3">
    <div class="w-8 h-8 rounded-lg bg-gradient-to-br <?= $iconColor ?> text-white flex items-center justify-center shadow-sm shrink-0">
      <span class="text-[10px] font-bold"><?= esc($acronym) ?></span>
    </div>
    <p class="text-sm font-bold text-gray-800"><?= esc($sectionLabel) ?></p>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-gray-200">
          <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Option</th>
          <?php if (! empty($parentLabel)): ?>
            <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider"><?= esc($parentLabel) ?></th>
          <?php endif; ?>
          <?php foreach ($roles as $roleId => $roleLabel): ?>
            <th class="text-center px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
              <?php
                $badgeColor = match($roleId) {
                  5 => 'bg-gray-100 text-gray-700',
                  6 => 'bg-purple-100 text-purple-700',
                  default => 'bg-blue-100 text-blue-700',
                };
              ?>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $badgeColor ?>">
                <?= esc($roleLabel) ?>
              </span>
            </th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php foreach ($items as $item): ?>
          <?php $optionId = (int) $item[$pkField]; ?>
          <tr class="hover:bg-indigo-50/40 transition-colors">
            <td class="px-6 py-3">
              <p class="text-sm font-semibold text-gray-900"><?= esc($item[$nameField]) ?></p>
              <?php if (! empty($item['description'])): ?>
                <p class="text-xs text-gray-400 truncate max-w-xs"><?= esc($item['description']) ?></p>
              <?php endif; ?>
            </td>
            <?php if (! empty($parentLabel) && ! empty($parentMap) && ! empty($parentFk)): ?>
              <td class="px-4 py-3 text-xs text-gray-500">
                <?php
                  $pId = (int) ($item[$parentFk] ?? 0);
                  echo esc($parentMap[$pId][$parentName] ?? '—');
                ?>
              </td>
            <?php endif; ?>
            <?php foreach ($roles as $roleId => $roleLabel): ?>
              <?php
                $isEnabled = ($matrix[$roleId][$optionId] ?? 1) === 1;
                $inputName = 'access[' . $optionType . '_' . $roleId . '_' . $optionId . ']';
              ?>
              <td class="px-6 py-3 text-center">
                <label class="relative inline-flex items-center cursor-pointer group">
                  <input type="checkbox"
                         name="<?= $inputName ?>"
                         value="1"
                         <?= $isEnabled ? 'checked' : '' ?>
                         class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-200 rounded-full
                              peer-checked:after:translate-x-full peer-checked:after:border-white
                              after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white
                              after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5
                              after:transition-all peer-checked:bg-indigo-600 transition-colors"></div>
                  <span class="ml-2 text-xs font-medium peer-checked:text-indigo-600 text-gray-400 transition-colors">
                    <?= $isEnabled ? 'On' : 'Off' ?>
                  </span>
                </label>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
