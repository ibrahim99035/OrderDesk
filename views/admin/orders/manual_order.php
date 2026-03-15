<?php include "views/admin/layout/header.php"; ?>
<?php
/** @var array $users */
/** @var array $rooms */
/** @var array $products */
include "views/admin/layout/header.php";
?>
<div class="max-w-2xl mx-auto px-6 py-8">

    <a href="/orders" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 mb-6 transition">
        ← Back to Orders
    </a>

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">📝 Manual Order</h2>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Create an order on behalf of a user</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
        <form method="POST" action="/orders/manual">

            <!-- User -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">👤 User</label>
                <select name="user_id" required
                        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Select user --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>">
                            <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Room -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">🚪 Room</label>
                <select name="room_id" required
                        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Select room --</option>
                    <?php foreach ($rooms as $r): ?>
                        <option value="<?= $r['id'] ?>">Room <?= htmlspecialchars($r['room_number']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Products -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">🛒 Products</label>
                <div id="itemsContainer" class="space-y-3">
                    <div class="item-row flex gap-3 items-center">
                        <select name="items[0][product_id]" required onchange="calcTotal()"
                                class="flex-1 px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">-- Select product --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
                                    <?= htmlspecialchars($p['name']) ?> — <?= number_format($p['price'], 2) ?> EGP
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="items[0][quantity]" value="1" min="1" oninput="calcTotal()"
                               class="w-20 px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-center focus:ring-2 focus:ring-blue-500 outline-none">
                        <button type="button" onclick="removeItem(this)"
                                class="w-9 h-9 flex items-center justify-center text-red-500 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg transition">✕</button>
                    </div>
                </div>
                <button type="button" onclick="addItem()"
                        class="mt-3 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold transition">
                    + Add Product
                </button>
            </div>

            <!-- Total -->
            <div class="mb-5 px-4 py-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                <p class="text-blue-800 dark:text-blue-300 font-bold">
                    💰 Estimated Total: <span id="totalDisplay">0.00</span> EGP
                </p>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">📝 Notes (optional)</label>
                <textarea name="notes" rows="2"
                          class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"
                          placeholder="Any special notes..."></textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-lg transition hover:scale-[1.01] shadow">
                ✅ Create Order
            </button>
        </form>
    </div>
</div>

<script>
let itemIndex = 1;

const productOptionsHTML = `
    <option value="">-- Select product --</option>
    <?php foreach ($products as $p): ?>
    <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
        <?= addslashes(htmlspecialchars($p['name'])) ?> — <?= number_format($p['price'], 2) ?> EGP
    </option>
    <?php endforeach; ?>
`;

function addItem() {
    const container = document.getElementById('itemsContainer');
    const div = document.createElement('div');
    div.className = 'item-row flex gap-3 items-center';
    div.innerHTML = `
        <select name="items[${itemIndex}][product_id]" required onchange="calcTotal()"
                class="flex-1 px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            ${productOptionsHTML}
        </select>
        <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" oninput="calcTotal()"
               class="w-20 px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-center">
        <button type="button" onclick="removeItem(this)"
                class="w-9 h-9 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-lg transition">✕</button>
    `;
    container.appendChild(div);
    itemIndex++;
    calcTotal();
}

function removeItem(btn) {
    if (document.querySelectorAll('.item-row').length > 1) {
        btn.closest('.item-row').remove();
        calcTotal();
    }
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const price = parseFloat(row.querySelector('select').selectedOptions[0]?.dataset.price) || 0;
        const qty   = parseInt(row.querySelector('input[type="number"]')?.value) || 1;
        total += price * qty;
    });
    document.getElementById('totalDisplay').textContent = total.toFixed(2);
}

document.querySelector('.item-row select').addEventListener('change', calcTotal);
document.querySelector('.item-row input[type="number"]').addEventListener('input', calcTotal);
</script>

<?php include "views/admin/layout/footer.php"; ?>