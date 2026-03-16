<?php include "views/admin/layout/header.php"; ?>

<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">📦 Orders</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Manage all cafeteria orders</p>
        </div>
        <a href="/orders/manual"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow transition hover:scale-105">
            + Manual Order
        </a>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex gap-2 mb-6 flex-wrap">
        <?php
        $filters = [
            ['label' => 'All',              'href' => '/orders','active' => !isset($active_filter)],
            ['label' => '⏳ Processing',     'href' => '/orders/processing','active' => ($active_filter ?? '') === 'processing'],
            ['label' => '🚚 Out for Delivery','href' => '/orders/out_for_delivery', 'active' => ($active_filter ?? '') === 'out_for_delivery'],
            ['label' => '✅ Done',           'href' => '/orders/done','active' => ($active_filter ?? '') === 'done'],
            ['label' => '❌ Cancelled',      'href' => '/orders/cancelled','active' => ($active_filter ?? '') === 'cancelled'],
        ];
        foreach ($filters as $f):
        ?>
        <a href="<?= $f['href'] ?>"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition
           <?= $f['active']
               ? 'bg-blue-600 text-white shadow'
               : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' ?>">
            <?= $f['label'] ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
        <?php if (empty($orders)): ?>
            <div class="text-center py-20 text-gray-400 dark:text-gray-500">
                <p class="text-5xl mb-4">📭</p>
                <p class="text-xl font-semibold">No orders found</p>
            </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Room</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($orders as $order):
                        $statusMap = [
                            'processing'       => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'out_for_delivery' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'done'             => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'cancelled'        => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        ];
                        $statusLabels = [
                            'processing'       => '⏳ Processing',
                            'out_for_delivery' => '🚚 Out for Delivery',
                            'done'             => '✅ Done',
                            'cancelled'        => '❌ Cancelled',
                        ];
                        $cls   = $statusMap[$order['status']]   ?? 'bg-gray-100 text-gray-800';
                        $label = $statusLabels[$order['status']] ?? $order['status'];
                    ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">#<?= $order['id'] ?></td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($order['user_id']) ?></td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">Room #<?= $order['room_id'] ?></td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white"><?= number_format($order['total'], 2) ?> EGP</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $cls ?>">
                                <?= $label ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm"><?= $order['created_at'] ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">

                                <!-- View -->
                                <a href="/orders/view/<?= $order['id'] ?>"
                                   class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg transition" title="View">
                                    👁️
                                </a>

                                <!-- Confirm (processing only) -->
                                <?php if ($order['status'] === 'processing'): ?>
                                <form method="POST" action="/orders/confirm/<?= $order['id'] ?>">
                                    <button type="submit"
                                            class="p-1.5 text-green-500 hover:bg-green-50 dark:hover:bg-green-900 rounded-lg transition" title="Confirm">
                                        ✅
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- Deliver (out_for_delivery only) -->
                                <?php if ($order['status'] === 'out_for_delivery'): ?>
                                <form method="POST" action="/orders/deliver/<?= $order['id'] ?>">
                                    <button type="submit"
                                            class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg transition" title="Deliver">
                                        🚚
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- Complete (force close - not done) -->
                                <?php if ($order['status'] !== 'done'): ?>
                                <form method="POST" action="/orders/complete/<?= $order['id'] ?>"
                                      onsubmit="return confirm('Force complete order #<?= $order['id'] ?>?')">
                                    <button type="submit"
                                            class="p-1.5 text-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900 rounded-lg transition" title="Force Complete">
                                        ⚡
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- Cancel (not done/cancelled) -->
                                <?php if (!in_array($order['status'], ['done', 'cancelled'])): ?>
                                <form method="POST" action="/orders/cancel/<?= $order['id'] ?>"
                                      onsubmit="return confirm('Cancel order #<?= $order['id'] ?>?')">
                                    <button type="submit"
                                            class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition" title="Cancel">
                                        🚫
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- Edit -->
                                <button onclick="openEditModal(<?= htmlspecialchars(json_encode($order)) ?>)"
                                        class="p-1.5 text-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-900 rounded-lg transition" title="Edit">
                                    ✏️
                                </button>

                                <!-- Delete (cancelled only) -->
                                <?php if ($order['status'] === 'cancelled'): ?>
                                <form method="POST" action="/orders/delete/<?= $order['id'] ?>"
                                      onsubmit="return confirm('Permanently delete order #<?= $order['id'] ?>?')">
                                    <button type="submit"
                                            class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition" title="Delete">
                                        🗑️
                                    </button>
                                </form>
                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 id="editModalTitle" class="text-xl font-bold text-gray-900 dark:text-white">Edit Order</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
       <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Room ID
            </label>

            <select name="room_id" id="edit_room_id" required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">

                <option value="">Select Room</option>
                                    <?php var_dump($rooms) ?>
                <?php foreach ($rooms as $room): ?>

                    <option value="<?= $room['id'] ?>">
                        <?= $room['room_number'] ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                <textarea name="notes" id="edit_notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" id="edit_status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="processing">⏳ Processing</option>
                    <option value="out_for_delivery">🚚 Out for Delivery</option>
                    <option value="done">✅ Done</option>
                    <option value="cancelled">❌ Cancelled</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
                    💾 Save
                </button>
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-800 dark:text-white font-bold rounded-xl transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(order) {
    document.getElementById('editModalTitle').textContent = 'Edit Order #' + order.id;
    document.getElementById('editForm').action = '/orders/update/' + order.id;
    document.getElementById('edit_room_id').value  = order.room_id;
    document.getElementById('edit_notes').value    = order.notes ?? '';
    document.getElementById('edit_status').value   = order.status;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>

<?php include "views/admin/layout/footer.php"; ?>