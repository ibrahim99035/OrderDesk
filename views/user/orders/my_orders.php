<?php include __DIR__ . "/../layout/header.php"; ?>
<div class="max-w-3xl mx-auto px-4 py-8">

    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">📦 My Orders</h2>

    <?php if (empty($orders)): ?>
        <div class="text-center py-16 text-gray-500">
            <p class="text-5xl mb-4">🛒</p>
            <p class="text-xl font-semibold">No orders yet</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
        <?php foreach ($orders as $order):
            $statusMap = [
                'processing'       => ['bg-yellow-100 text-yellow-800', '⏳ Processing'],
                'out_for_delivery' => ['bg-blue-100 text-blue-800',     '🚚 Out for Delivery'],
                'done'             => ['bg-green-100 text-green-800',   '✅ Delivered'],
                'cancelled'        => ['bg-red-100 text-red-800',       '❌ Cancelled'],
            ];
            [$cls, $label] = $statusMap[$order['status']] ?? ['bg-gray-100 text-gray-800', $order['status']];
        ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5">

            <!-- Header -->
            <div class="flex justify-between items-start mb-3">
                <div>
                    <a href="/orders/my/<?= $order['id'] ?>"
                       class="text-lg font-bold text-gray-900 dark:text-white hover:text-blue-600 transition">
                        #<?= $order['id'] ?>
                    </a>
                    <p class="text-gray-500 text-sm">🕐 <?= $order['created_at'] ?></p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold <?= $cls ?>">
                    <?= $label ?>
                </span>
            </div>

            <!-- Items -->
            <?php if (!empty($order['items'])): ?>
            <div class="mb-3 space-y-1">
                <?php foreach ($order['items'] as $item): ?>
                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-3">
                        <img src="/<?= $item['image'] ?>"
                            class="w-10 h-10 rounded object-cover">

                        <span>
                            × <?= $item['quantity'] ?> <?= $item['name'] ?>
                        </span>
                    </div>
                    <span><?= number_format($item['unit_price'] * $item['quantity'], 2) ?> EGP</span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Total -->
            <div class="flex justify-between font-bold text-gray-900 dark:text-white border-t border-gray-100 dark:border-gray-700 pt-2 mb-3">
                <span>Total</span>
                <span><?= number_format($order['total'], 2) ?> EGP</span>
            </div>

            <!-- Notes -->
            <?php if (!empty($order['notes'])): ?>
                <p class="text-sm text-gray-500 mb-3">📝 <?= htmlspecialchars($order['notes']) ?></p>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex gap-3">
                <a href="/orders/my/<?= $order['id'] ?>"
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold transition">
                    👁️ View Details
                </a>

                <?php if (in_array($order['status'], ['processing', 'out_for_delivery'])): ?>
                <button type="button"
                        onclick="openCancelModal(<?= $order['id'] ?>)"
                        class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-semibold transition">
                    ❌ Cancel
                </button>
                <?php endif; ?>
            </div>

        </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


<!-- Cancel Modal -->
<div id="cancelModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-80">

        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">
            Cancel Order ?
        </h3>

        <form method="POST" action="/orders/cancel">

            <input type="hidden" name="order_id" id="cancelOrderId">

            <div class="flex justify-end gap-3">

                <button type="button"
                        onclick="closeCancelModal()"
                        class="px-3 py-2 bg-gray-200 rounded">
                    No
                </button>

                <button type="submit"
                        class="px-3 py-2 bg-red-600 text-white rounded">
                    Yes Cancel
                </button>

            </div>

        </form>

    </div>

</div>

<script>
function openCancelModal(id) {
    document.getElementById("cancelModal").classList.remove("hidden");
    document.getElementById("cancelModal").classList.add("flex");
    document.getElementById("cancelOrderId").value = id;
}

function closeCancelModal() {
    document.getElementById("cancelModal").classList.add("hidden");
}
</script>


<?php include __DIR__ . "/../layout/footer.php"; ?>