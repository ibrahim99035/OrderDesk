
<?php
/** @var array $users */
/** @var array $rooms */
/** @var array $products */
/** @var array $orders */
include "views/admin/layout/header.php";
?>
<div class="max-w-5xl mx-auto px-6 py-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">📋 New Orders</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Orders waiting for confirmation</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/orders/delivery_queue"
               class="px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 rounded-xl font-semibold text-sm transition">
                🚚 Delivery Queue
            </a>
            <a href="/orders/manual"
               class="px-4 py-2 bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-200 rounded-xl font-semibold text-sm transition">
                📝 Manual Order
            </a>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full font-bold">
                <?= count($orders) ?> new
            </span>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-20 text-gray-400 dark:text-gray-500">
            <p class="text-6xl mb-4">🎉</p>
            <p class="text-2xl font-semibold">No new orders!</p>
            <p class="text-gray-400 mt-2">New orders will appear here automatically</p>
        </div>
    <?php else: ?>
        <div class="grid gap-5">
        <?php foreach ($orders as $order): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 border-l-4 border-yellow-400">
            <div class="flex justify-between items-start">

                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-xl font-bold text-gray-900 dark:text-white">#<?= $order['id'] ?></span>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full text-xs font-bold">
                            ⏳ Processing
                        </span>
                        <span class="text-sm text-gray-400">🕐 <?= $order['created_at'] ?></span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                        <span class="text-gray-500">👤 User: <strong class="text-gray-900 dark:text-white">#<?= $order['user_id'] ?></strong></span>
                        <span class="text-gray-500">🚪 Room: <strong class="text-gray-900 dark:text-white">#<?= $order['room_id'] ?></strong></span>
                        <span class="text-gray-500">💰 Total: <strong class="text-gray-900 dark:text-white"><?= number_format($order['total'], 2) ?> EGP</strong></span>
                    </div>

                    <?php if (!empty($order['items'])): ?>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-3 mb-3 space-y-1">
                        <?php foreach ($order['items'] as $item): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700 dark:text-gray-300">× <?= $item['quantity'] ?> — Product #<?= $item['product_id'] ?></span>
                            <span class="text-gray-500"><?= number_format($item['unit_price'] * $item['quantity'], 2) ?> EGP</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($order['notes'])): ?>
                        <p class="text-sm text-orange-600 dark:text-orange-400">📝 <?= htmlspecialchars($order['notes']) ?></p>
                    <?php endif; ?>
                </div>

                <form method="POST" action="/orders/confirm/<?= $order['id'] ?>" class="ml-6"
                      onsubmit="return confirm('Confirm order #<?= $order['id'] ?> and send for delivery?')">
                    <button type="submit"
                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition hover:scale-105 shadow">
                        ✅ Confirm
                    </button>
                </form>

            </div>
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