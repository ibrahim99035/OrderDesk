<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-10">

        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-1">
                Dashboard
            </p>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                🍔 Products Manager
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Manage your inventory with ease
            </p>
        </div>

        <button
            onclick="openAddProductModal()"
            class="inline-flex items-center gap-2
            px-6 py-3
            rounded-xl
            bg-blue-600
            hover:bg-blue-700
            active:scale-95
            text-white
            font-semibold
            text-sm
            shadow-lg shadow-blue-200 dark:shadow-blue-900/40
            transition-all duration-200
            self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Product
        </button>

    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <?php foreach($proudects as $proudect) : ?>

        <div class="group flex flex-col
            bg-white dark:bg-gray-800
            rounded-2xl
            shadow-sm hover:shadow-xl
            border border-gray-100 dark:border-gray-700
            hover:-translate-y-1.5
            transition-all duration-300
            overflow-hidden">

            <!-- Image -->
            <div class="relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
                <img
                    src="/<?= $proudect["image"] ?>"
                    alt="<?= $proudect["name"] ?>"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <!-- Subtle overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>

            <!-- Body -->
            <div class="flex flex-col flex-1 p-5">

                <!-- Name & Badge -->
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug">
                        <?= $proudect["name"] ?>
                    </h3>
                    <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                        Active
                    </span>
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-4 flex-1 line-clamp-2">
                    <?= $proudect["description"] ?>
                </p>

                <!-- Price + Divider -->
                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700 mb-4">
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wide font-medium">Price</p>
                        <p class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 leading-tight">
                            $<?= $proudect["price"] ?>
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2.5">
                    <button
                        onclick="editProduct(<?= htmlspecialchars(json_encode($proudect), ENT_QUOTES, 'UTF-8') ?>)"
                        class="flex-1 h-12 flex items-center justify-center gap-1.5
                        py-2.5 rounded-xl
                        bg-blue-50 dark:bg-blue-900/30
                        hover:bg-blue-600
                        text-blue-600 dark:text-blue-400
                        hover:text-white
                        text-sm font-semibold
                        border border-blue-200 dark:border-blue-800
                        hover:border-blue-600
                        transition-all duration-200
                        active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>

                    <form class="flex-1 inline-flex" action="products/delete/<?= $proudect["id"] ?>" method="POST">
                        
                        <button
                            onclick="deleteProduct(1)"
                            class="flex w-full h-12 items-center justify-center gap-1.5
                            py-2.5 rounded-xl
                            bg-red-50 dark:bg-red-900/20
                            hover:bg-red-600
                            text-red-500 dark:text-red-400
                            hover:text-white
                            text-sm font-semibold
                            border border-red-200 dark:border-red-800
                            hover:border-red-600
                            transition-all duration-200
                            active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <?php endforeach ?>

    </div>

</div>