<!-- User profile -->
      <div class="px-5 py-4 mx-4 mt-4 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl text-white shadow-lg">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg font-bold"><?= substr($user['name'], 0, 1) ?></div>
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-sm truncate"><?= $user['name'] ?></p>
            <p class="text-xs text-blue-200 truncate"><?= $user['email'] ?></p>
            <?php if (!empty($user['role_label'])): ?>
            <div class="flex items-center gap-1.5 mt-1">
              <span class="inline-block px-1.5 py-0.5 text-[10px] font-medium bg-white/20 rounded"><?= esc($user['role_label']) ?></span>
              <?php if (!empty($user['section_acronym'])): ?>
              <span class="inline-block px-1.5 py-0.5 text-[10px] font-medium bg-white/15 rounded"><?= esc($user['section_acronym']) ?></span>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
          <div class="relative">
            <span class="pulse-dot relative inline-block w-2.5 h-2.5 bg-green-400 rounded-full text-green-400"></span>
          </div>
        </div>
      </div>