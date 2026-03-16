<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="max-w-5xl mx-auto px-6 py-10">

        <!-- Page Header -->
        <div class="mb-8">
            <p class="text-sm text-blue-600 dark:text-blue-400 font-semibold">Admin / Checks</p>
            <h1 class="text-3xl font-bold mt-2">Employee Checks</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                See how much each employee has spent in the cafeteria.
            </p>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="<?= BASE_URL ?>/admin/checks" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-sm mb-8">
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="user_id" class="block text-sm font-medium mb-2">Employee</label>
                    <select name="user_id" id="user_id" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3">
                        <option value="0">All employees</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= (int) $user['id'] ?>" <?= $userId === (int) $user['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="min-w-[160px]">
                    <label for="date_from" class="block text-sm font-medium mb-2">From</label>
                    <input type="date" name="date_from" id="date_from"
                           value="<?= htmlspecialchars($dateFrom) ?>"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3">
                </div>

                <div class="min-w-[160px]">
                    <label for="date_to" class="block text-sm font-medium mb-2">To</label>
                    <input type="date" name="date_to" id="date_to"
                           value="<?= htmlspecialchars($dateTo) ?>"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 py-3">
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 font-semibold transition-colors">
                        Filter
                    </button>
                    <a href="<?= BASE_URL ?>/admin/checks" class="rounded-xl border border-gray-300 dark:border-gray-600 px-6 py-3 font-semibold hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Results Table -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">

            <?php if (empty($results)): ?>
                <div class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                    No orders found.
                </div>
            <?php else: ?>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-900/60">
                        <tr class="text-left">
                            <th class="px-6 py-4 font-semibold">Employee</th>
                            <th class="px-6 py-4 font-semibold">Orders</th>
                            <th class="px-6 py-4 font-semibold text-right">Total Spent</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($results as $user): ?>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td colspan="3" class="p-0">

                                    <details class="group">
                                        <summary class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors list-none">
                                            <span class="font-medium">
                                                <?= htmlspecialchars($user['user_name']) ?>
                                            </span>

                                            <span class="flex items-center gap-6">
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    <?= count($user['orders']) ?> order<?= count($user['orders']) !== 1 ? 's' : '' ?>
                                                </span>
                                                <span class="font-bold text-blue-600 dark:text-blue-400">
                                                    <?= number_format($user['user_total'], 2) ?> EGP
                                                </span>
                                                <svg class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </span>
                                        </summary>

                                        <div class="bg-gray-50 dark:bg-gray-900/40 px-6 pb-4">
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="text-left text-gray-500 dark:text-gray-400">
                                                        <th class="py-2 pr-4">Order #</th>
                                                        <th class="py-2 pr-4">Date</th>
                                                        <th class="py-2 text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($user['orders'] as $order): ?>
                                                        <tr class="border-t border-gray-200 dark:border-gray-700/50">
                                                            <td class="py-2 pr-4">#<?= (int) $order['id'] ?></td>
                                                            <td class="py-2 pr-4"><?= htmlspecialchars($order['created_at']) ?></td>
                                                            <td class="py-2 text-right font-medium">
                                                                <?= number_format((float) $order['total'], 2) ?> EGP
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </details>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>

    </div>
</div>
