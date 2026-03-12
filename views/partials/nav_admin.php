<nav class="bg-white shadow-sm px-6 py-3 flex items-center justify-between">
  <div class="flex gap-6 text-sm font-medium">
    <a href="<?= BASE_URL ?>/admin"
      class="<?= ($activeNav ?? '') === 'home' ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600' ?>">
      Home
    </a>
    <a href="<?= BASE_URL ?>/admin/products"
      class="<?= ($activeNav ?? '') === 'products' ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600' ?>">
      Products
    </a>
    <a href="<?= BASE_URL ?>/admin/users"
      class="<?= ($activeNav ?? '') === 'users' ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600' ?>">
      Users
    </a>
    <a href="<?= BASE_URL ?>/admin/manual-order"
      class="<?= ($activeNav ?? '') === 'manual' ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600' ?>">
      Manual Order
    </a>
    <a href="<?= BASE_URL ?>/admin/checks"
      class="<?= ($activeNav ?? '') === 'checks' ? 'text-blue-600 font-semibold' : 'text-gray-500 hover:text-blue-600' ?>">
      Checks
    </a>
  </div>
  <div class="flex items-center gap-2 text-sm text-gray-700 font-medium">
    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
      AD
    </div>
    <?= htmlspecialchars($currentUser['name'] ?? 'Admin') ?>
    <a href="<?= BASE_URL ?>/logout"
      class="ml-3 text-xs text-gray-400 hover:text-red-500 transition">
      Logout
    </a>
  </div>
</nav>