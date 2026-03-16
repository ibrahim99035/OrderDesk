<?php include __DIR__ . "/../layout/header.php"; ?>
<div class="max-w-2xl mx-auto px-4 py-8">

    <a href="/orders/my" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-6 transition">
        ← My Orders
    </a>

    <?php
    $statusMap = [
        'processing'       => ['bg-yellow-100 text-yellow-800', '⏳ Processing'],
        'out_for_delivery' => ['bg-blue-100 text-blue-800',     '🚚 Out for Delivery'],
        'done'             => ['bg-green-100 text-green-800',   '✅ Delivered'],
        'cancelled'        => ['bg-red-100 text-red-800',       '❌ Cancelled'],
    ];
    [$cls, $label] = $statusMap[$order['status']] ?? ['bg-gray-100 text-gray-800', $order['status']];
    ?>

    <!-- Order Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-5">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Order #<?= $order['id'] ?></h2>
                <p class="text-gray-500 text-sm mt-1">🕐 <?= $order['created_at'] ?></p>
            </div>
            <span class="px-3 py-1.5 rounded-full text-sm font-bold <?= $cls ?>">
                <?= $label ?>
            </span>
        </div>
    </div>

    <!-- Delivery Info -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-5">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">🚪 Delivery Info</h3>
        <div class="text-sm space-y-2 text-gray-600 dark:text-gray-400">
            <div class="flex justify-between">
                <span>Room</span>
                <span class="font-semibold text-gray-900 dark:text-white">
                    <?= htmlspecialchars($order['room']['room_number'] ?? "Room #{$order['room_id']}") ?>
                </span>
            </div>
            <?php if (!empty($order['notes'])): ?>
            <div class="flex justify-between">
                <span>Notes</span>
                <span class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($order['notes']) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Items -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-5">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">🛒 Items</h3>
        <?php if (!empty($order['items'])): ?>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php foreach ($order['items'] as $item): ?>
            <div class="flex justify-between items-center py-3">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">Product #<?= $item['name'] ?></p>
                    <p class="text-sm text-gray-500">× <?= $item['quantity'] ?> @ <?= number_format($item['unit_price'], 2) ?> EGP</p>
                </div>
                <span class="font-bold text-gray-900 dark:text-white">
                    <?= number_format($item['unit_price'] * $item['quantity'], 2) ?> EGP
                </span>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700 mt-2">
            <span class="text-xl font-bold text-gray-900 dark:text-white">Total</span>
            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                <?= number_format($order['total'], 2) ?> EGP
            </span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Cancel Action -->
    <?php if (in_array($order['status'], ['processing', 'out_for_delivery'])): ?>
    <form method="POST" action="/orders/cancel"
          onsubmit="return confirm('Are you sure you want to cancel this order?')">
        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
        <button type="submit"
                class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition">
            ❌ Cancel Order
        </button>
    </form>
    <?php endif; ?>

</div>

<?php include "views/admin/layout/footer.php"; ?>