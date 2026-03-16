<div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="max-w-6xl mx-auto px-6 py-10">

        <div class="mb-8 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">Admin / Checks</p>
                <h1 class="mt-2 text-3xl font-bold">Employee Checks</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Review employee spending with quick filters and expandable order details.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Employees</p>
                    <p class="mt-2 text-2xl font-bold"><?= count($results) ?></p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white px-4 py-3 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Orders</p>
                    <p class="mt-2 text-2xl font-bold">
                        <?= array_sum(array_map(static fn($userRow) => count($userRow['orders']), $results)) ?>
                    </p>
                </div>
                <div class="col-span-2 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 shadow-sm sm:col-span-1 dark:border-blue-900/40 dark:bg-blue-950/40">
                    <p class="text-xs font-medium uppercase tracking-wide text-blue-700 dark:text-blue-300">Total Spent</p>
                    <p class="mt-2 text-2xl font-bold text-blue-700 dark:text-blue-200">
                        <?= number_format(array_sum(array_map(static fn($userRow) => (float) $userRow['user_total'], $results)), 2) ?> EGP
                    </p>
                </div>
            </div>
        </div>

        <form method="GET" action="<?= BASE_URL ?>/admin/checks" class="mb-8 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Filters</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Choose an employee or a date range to narrow the results.</p>
                </div>
                <a href="<?= BASE_URL ?>/admin/checks" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                    Reset filters
                </a>
            </div>

            <div class="grid gap-4 lg:grid-cols-[minmax(0,1.2fr)_minmax(160px,0.7fr)_minmax(160px,0.7fr)]">
                <div>
                    <label for="user_id" class="mb-2 block text-sm font-medium">Employee</label>
                    <select name="user_id" id="user_id" onchange="this.form.submit()" class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-900">
                        <option value="0">All employees</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= (int) $user['id'] ?>" <?= $userId === (int) $user['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="mb-2 block text-sm font-medium">From</label>
                    <input type="date" name="date_from" id="date_from"
                           onchange="this.form.submit()"
                           value="<?= htmlspecialchars($dateFrom) ?>"
                           class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-900">
                </div>

                <div>
                    <label for="date_to" class="mb-2 block text-sm font-medium">To</label>
                    <input type="date" name="date_to" id="date_to"
                           onchange="this.form.submit()"
                           value="<?= htmlspecialchars($dateTo) ?>"
                           class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-900">
                </div>
            </div>
        </form>

        <div class="space-y-4">
            <?php if (empty($results)): ?>
                <div class="rounded-3xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-lg font-semibold">No orders found</p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Try changing the employee or date filters to see more results.
                    </p>
                </div>
            <?php else: ?>
                <?php foreach ($results as $userRow): ?>
                    <details class="group/user overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <summary class="list-none cursor-pointer px-6 py-5 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-lg font-semibold"><?= htmlspecialchars($userRow['user_name']) ?></p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <?= count($userRow['orders']) ?> order<?= count($userRow['orders']) !== 1 ? 's' : '' ?> in the selected period
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-200">
                                        <?= count($userRow['orders']) ?> orders
                                    </span>
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700 dark:bg-blue-950/50 dark:text-blue-200">
                                        <?= number_format($userRow['user_total'], 2) ?> EGP
                                    </span>
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-400 transition-transform group-open/user:rotate-180 dark:border-gray-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </summary>

                        <div class="border-t border-gray-200 bg-gray-50/80 px-6 py-5 dark:border-gray-700 dark:bg-gray-900/30">
                            <div class="space-y-3">
                                <?php foreach ($userRow['orders'] as $order): ?>
                                    <details class="group/order overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                                        <summary class="list-none cursor-pointer px-4 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <span class="rounded-full bg-gray-900 px-3 py-1 text-xs font-semibold text-white dark:bg-gray-100 dark:text-gray-900">
                                                        Order #<?= (int) $order['id'] ?>
                                                    </span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                                        <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                                                    </span>
                                                </div>

                                                <div class="flex items-center gap-3">
                                                    <span class="text-base font-bold text-gray-900 dark:text-gray-100">
                                                        <?= number_format((float) $order['total'], 2) ?> EGP
                                                    </span>
                                                    <svg class="h-4 w-4 text-gray-400 transition-transform group-open/order:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </summary>

                                        <?php if (!empty($order['items'])): ?>
                                            <div class="border-t border-gray-200 bg-gray-50 px-4 py-4 dark:border-gray-700 dark:bg-gray-900/40">
                                                <div class="grid gap-3">
                                                    <?php foreach ($order['items'] as $item): ?>
                                                        <div class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-3 py-3 dark:border-gray-700 dark:bg-gray-800">
                                                            <?php if (!empty($item['product_image'])): ?>
                                                                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($item['product_image']) ?>" alt="" class="h-10 w-10 rounded-xl object-cover">
                                                            <?php else: ?>
                                                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-200 text-gray-400 dark:bg-gray-700">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                    </svg>
                                                                </div>
                                                            <?php endif; ?>

                                                            <div class="min-w-0 flex-1">
                                                                <p class="truncate text-sm font-semibold"><?= htmlspecialchars($item['product_name']) ?></p>
                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                    Qty: <?= $item['quantity'] ?>
                                                                </p>
                                                            </div>

                                                            <div class="text-right">
                                                                <p class="text-sm font-semibold"><?= number_format($item['unit_price'], 2) ?> EGP</p>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="border-t border-gray-200 px-4 py-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                                No items found for this order.
                                            </div>
                                        <?php endif; ?>
                                    </details>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </details>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>
