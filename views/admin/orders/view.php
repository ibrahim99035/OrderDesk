<?php
/** @var array $order */
include "views/admin/layout/header.php";
?>
<div class="max-w-4xl mx-auto px-6 py-8">

    <!-- Back -->
    <a href="/orders" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-6 transition">
        ← Back to Orders
    </a>

    <?php
    $statusMap = [
        'processing'       => ['bg-yellow-100 text-yellow-800', '⏳ Processing'],
        'out_for_delivery' => ['bg-blue-100 text-blue-800',     '🚚 Out for Delivery'],
        'done'             => ['bg-green-100 text-green-800',   '✅ Done'],
        'cancelled'        => ['bg-red-100 text-red-800',       '❌ Cancelled'],
    ];
    [$cls, $label] = $statusMap[$order['status']] ?? ['bg-gray-100 text-gray-800', $order['status']];
    ?>

    <!-- Order Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Order #<?= $order['id'] ?></h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1">🕐 Placed: <?= $order['created_at'] ?></p>
                <?php if ($order['updated_at']): ?>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Updated: <?= $order['updated_at'] ?></p>
                <?php endif; ?>
            </div>
            <span class="px-4 py-2 rounded-full font-bold text-sm <?= $cls ?>">
                <?= $label ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <!-- User Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">👤 User Info</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Name</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        <?= htmlspecialchars($order['user']['name'] ?? "User #{$order['user_id']}") ?>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        <?= htmlspecialchars($order['user']['email'] ?? '—') ?>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Placed by</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        <?= $order['placed_by'] == $order['user_id'] ? 'Self' : "Admin #{$order['placed_by']}" ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Room Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">🚪 Delivery Info</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Room</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        <?= htmlspecialchars($order['room']['room_number'] ?? "Room #{$order['room_id']}") ?>
                    </span>
                </div>
                <?php if (!empty($order['notes'])): ?>
                <div class="flex justify-between">
                    <span class="text-gray-500">Notes</span>
                    <span class="font-semibold text-gray-900 dark:text-white">
                        <?= htmlspecialchars($order['notes']) ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Order Items -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-6">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">🛒 Order Items</h3>
        <?php if (!empty($order['items'])): ?>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php foreach ($order['items'] as $item): ?>
            <div class="flex justify-between items-center py-3">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white">Product #<?= $item['product_id'] ?></p>
                    <p class="text-sm text-gray-500">× <?= $item['quantity'] ?> @ <?= number_format($item['unit_price'], 2) ?> EGP each</p>
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
        <?php else: ?>
            <p class="text-gray-400">No items found.</p>
        <?php endif; ?>
    </div>

    <!-- Actions -->
    <div class="flex gap-3 flex-wrap">

        <?php if ($order['status'] === 'processing'): ?>
        <form method="POST" action="/orders/confirm/<?= $order['id'] ?>">
            <button class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition">
                ✅ Confirm Order
            </button>
        </form>
        <?php endif; ?>

        <?php if ($order['status'] === 'out_for_delivery'): ?>
        <form method="POST" action="/orders/deliver/<?= $order['id'] ?>">
            <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                🚚 Mark as Delivered
            </button>
        </form>
        <?php endif; ?>

        <?php if ($order['status'] !== 'done'): ?>
        <form method="POST" action="/orders/complete/<?= $order['id'] ?>"
              onsubmit="return confirm('Force complete this order?')">
            <button class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition">
                ⚡ Force Complete
            </button>
        </form>
        <?php endif; ?>

        <?php if (!in_array($order['status'], ['done', 'cancelled'])): ?>
        <form method="POST" action="/orders/cancel/<?= $order['id'] ?>"
              onsubmit="return confirm('Cancel this order?')">
            <button class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition">
                🚫 Cancel Order
            </button>
        </form>
        <?php endif; ?>

        <?php if ($order['status'] === 'cancelled'): ?>
        <form method="POST" action="/orders/delete/<?= $order['id'] ?>"
              onsubmit="return confirm('Permanently delete this order?')">
            <button class="px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                🗑️ Delete Order
            </button>
        </form>
        <?php endif; ?>

    </div>

</div>

<?php include "views/admin/layout/footer.php"; ?>