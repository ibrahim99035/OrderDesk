<nav class="bg-white shadow-sm px-6 py-3 flex items-center justify-between">
  <div class="flex gap-6 text-sm font-medium">
    <a href="<?= BASE_URL ?>/"
      class="<?= ($activeNav ?? '') === 'home' ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600' ?>">
      Home
    </a>
    <a href="<?= BASE_URL ?>/orders"
      class="<?= ($activeNav ?? '') === 'orders' ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600' ?>">
      My Orders
    </a>
  </div>
  <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
      <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 2)) ?>
    </div>
    <?= htmlspecialchars($currentUser['name'] ?? '') ?>
    <a href="<?= BASE_URL ?>/logout"
      class="ml-3 text-xs text-gray-400 hover:text-red-500 transition">
      Logout
    </a>
  </div>
</nav>