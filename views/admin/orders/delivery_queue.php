<?php include "views/admin/layout/header.php"; ?>

<div class="max-w-5xl mx-auto px-6 py-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">🚚 Delivery Queue</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Orders ready to be delivered</p>
        </div>
        <span class="px-4 py-2 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full font-bold">
            <?= count($orders) ?> 
        </span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-20 text-gray-400 dark:text-gray-500">
            <p class="text-6xl mb-4">🎉</p>
            <p class="text-2xl font-semibold">All clear! No orders waiting.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-5">
        <?php foreach ($orders as $order): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex justify-between items-start border-l-4 border-blue-500">

            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-xl font-bold text-gray-900 dark:text-white">#<?= $order['id'] ?></span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-xs font-bold">
                        🚚 Out for Delivery
                    </span>
                    <span class="text-sm text-gray-400">🕐 <?= $order['created_at'] ?></span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <span>👤 User: <strong class="text-gray-900 dark:text-white">#<?= $order['user_id'] ?></strong></span>
                    <span>🚪 Room: <strong class="text-gray-900 dark:text-white">#<?= $order['room_id'] ?></strong></span>
                    <span>💰 Total: <strong class="text-gray-900 dark:text-white"><?= number_format($order['total'], 2) ?> EGP</strong></span>
                </div>

                <?php if (!empty($order['items'])): ?>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-3 space-y-1">
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-700 dark:text-gray-300">× <?= $item['quantity'] ?> — Product #<?= $item['product_id'] ?></span>
                        <span class="text-gray-500"><?= number_format($item['unit_price'] * $item['quantity'], 2) ?> EGP</span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($order['notes'])): ?>
                    <p class="text-sm text-orange-600 dark:text-orange-400 mt-2">📝 <?= htmlspecialchars($order['notes']) ?></p>
                <?php endif; ?>
            </div>

            <form method="POST" action="/orders/deliver/<?= $order['id'] ?>" class="ml-6"
                  onsubmit="return confirm('Mark order #<?= $order['id'] ?> as delivered?')">
                <button type="submit"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition hover:scale-105 shadow">
                    ✅ Delivered
                </button>
            </form>
        </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Auto-refresh every 30 seconds
    setTimeout(() => location.reload(), 30000);
</script>

<?php include "views/admin/layout/footer.php"; ?>