<div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white font-bold text-sm shadow-lg cursor-pointer">
    <?php if($user['avatar']): ?>
    <img src="<?= esc($user['avatar']) ?>" alt="Avatar" class="w-full h-full object-cover rounded-lg">
    <?php else: ?>
    <?= esc($user['initials']) ?>
    <?php endif; ?>
</div>