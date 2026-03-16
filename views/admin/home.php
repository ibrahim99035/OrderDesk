<?php
$latestOrder = $latestOrder ?? null;
$products    = $products    ?? [];
$rooms       = $rooms       ?? [];
$users       = $users       ?? [];
?>
<?php include "layout/header.php"; ?>

<div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
<div class="max-w-6xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-10">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-1">Admin / Dashboard</p>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">🏠 Home</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Welcome back, <?= htmlspecialchars(\App\core\Session::get('name') ?? '') ?>
            </p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <p class="text-xs uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-2">Total Users</p>
            <p class="text-3xl font-extrabold text-blue-600 dark:text-blue-400"><?= count($users) ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <p class="text-xs uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-2">Available Products</p>
            <p class="text-3xl font-extrabold text-blue-600 dark:text-blue-400"><?= count($products) ?></p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <p class="text-xs uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-2">Total Rooms</p>
            <p class="text-3xl font-extrabold text-blue-600 dark:text-blue-400"><?= count($rooms) ?></p>
        </div>
    </div>

    <!-- Users Dropdown + Latest Order -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-4">Users</p>
            <select class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm px-4 py-3 rounded-xl outline-none focus:border-blue-500 transition-colors">
                <option value="">— Select a User —</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>">
                        <?= htmlspecialchars($user['name']) ?>
                        (<?= $user['role'] ?>)
                        <?= !empty($user['room_number']) ? '— Room ' . htmlspecialchars($user['room_number']) : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-4">Latest Order</p>
            <?php if ($latestOrder): ?>
                <?php
                    $status = $latestOrder['status'];
                    $badge = match($status) {
                        'processing'       => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800',
                        'out_for_delivery' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                        'done'             => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                        'cancelled'        => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
                        default            => 'bg-gray-100 text-gray-600 border-gray-200',
                    };
                ?>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-400 dark:text-gray-500">Order</span>
                        <span class="text-gray-900 dark:text-white font-medium">#<?= $latestOrder['id'] ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-400 dark:text-gray-500">Room</span>
                        <span class="text-gray-900 dark:text-white"><?= htmlspecialchars($latestOrder['room_number'] ?? '—') ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-400 dark:text-gray-500">Total</span>
                        <span class="text-blue-600 dark:text-blue-400 font-extrabold">$<?= number_format($latestOrder['total'], 2) ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-400 dark:text-gray-500">Status</span>
                        <span class="border text-xs px-3 py-1 rounded-full font-semibold uppercase tracking-wide <?= $badge ?>">
                            <?= ucfirst(str_replace('_', ' ', $status)) ?>
                        </span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400 dark:text-gray-500">Placed</span>
                        <span class="text-gray-400 dark:text-gray-600 text-xs"><?= date('d M Y, H:i', strtotime($latestOrder['created_at'])) ?></span>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-400 dark:text-gray-600 text-sm">No orders yet.</p>
            <?php endif; ?>
        </div>

    </div>

    <!-- Products -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 dark:text-blue-400">Available Products</p>
            <span class="text-xs text-gray-400 dark:text-gray-500"><?= count($products) ?> items</span>
        </div>
        <?php if (empty($products)): ?>
            <p class="px-6 py-10 text-center text-gray-400 dark:text-gray-600 text-sm">No products available.</p>
        <?php else: ?>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                <?php foreach ($products as $product): ?>
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <?php if (!empty($product['image'])): ?>
                        <img src="/public/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                             class="w-12 h-12 object-cover rounded-xl border border-gray-100 dark:border-gray-700" alt="">
                    <?php else: ?>
                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 rounded-xl flex items-center justify-center text-xl">
                            🥤
                        </div>
                    <?php endif; ?>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($product['name']) ?></p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= htmlspecialchars($product['description']) ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-extrabold text-blue-600 dark:text-blue-400">$<?= number_format($product['price'], 2) ?></span>
                        <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 text-xs px-3 py-1 rounded-full font-semibold uppercase tracking-wide">
                            Available
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Rooms -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-4">Rooms</p>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($rooms as $room): ?>
                <span class="bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 text-blue-600 dark:text-blue-400 text-xs px-4 py-2 rounded-full font-semibold uppercase tracking-wide">
                    Room <?= htmlspecialchars($room['room_number']) ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>

</div>
</div>

<?php include "layout/footer.php"; ?>
