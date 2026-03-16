<?php include "layout/header.php" ?>
<!-- ══════════════════════ HERO STRIP ══════════════════════ -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 pt-10 pb-6 animate-fade-in">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-brand-500 dark:text-brand-400 text-sm font-bold tracking-widest uppercase mb-1">Our Menu</p>
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white leading-tight">
                Pick your <span class="text-brand-500">favourite</span> meal 🍔
            </h1>
        </div>
        <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">
            <?= count($proudects ?? [])?> items available
        </p>
    </div>
</div>

<!-- ══════════════════════ CATEGORY FILTER ══════════════════════ -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 mb-8 animate-slide-up" style="animation-delay:.1s">
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">

        <!-- All -->
        <a href="/product"
           class="cat-pill shrink-0 px-5 py-2 rounded-xl font-bold text-sm border border-orange-200 dark:border-white/10 bg-white dark:bg-white/5 text-gray-700 dark:text-gray-200 <?= !isset($_GET['id']) ? 'active' : '' ?>"
        >All</a>

        <?php foreach($catgroies as $cat): ?>
        <a href="/product?id=<?= $cat["id"] ?>"
           class="cat-pill shrink-0 px-5 py-2 rounded-xl font-bold text-sm border border-orange-200 dark:border-white/10 bg-white dark:bg-white/5 text-gray-700 dark:text-gray-200 <?= (isset($_GET['id']) && $_GET['id'] == $cat["id"]) ? 'active' : '' ?>"
        >
            <?= htmlspecialchars($cat["name"]) ?>
        </a>
        <?php endforeach; ?>

    </div>
</div>

<!-- ══════════════════════ PRODUCT GRID ══════════════════════ -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 pb-24">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">

        <?php foreach($proudects as $product): ?>
        <div
            class="product-card animate-slide-up bg-white dark:bg-gray-900 border border-orange-100 dark:border-white/5 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:shadow-orange-200/30 dark:hover:shadow-black/40 cursor-pointer group"
            x-data="{}"
            @click="addToCart(<?= htmlspecialchars(json_encode([
                'id'    => $product["id"],
                'name'  => $product["name"],
                'price' => $product["price"],
                'image' => $product["image"] ?? null,
            ])) ?>)"
        >
            <!-- Image -->
            <div class="relative aspect-square bg-orange-50 dark:bg-gray-800 overflow-hidden">
                <?php if(!empty($product["image"])): ?>
                    <img
                        src="/<?= htmlspecialchars($product["image"]) ?>"
                        alt="<?= htmlspecialchars($product["name"]) ?>"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        loading="lazy"
                    >
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-5xl">🍽️</div>
                <?php endif; ?>

                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="bg-brand-500 text-white text-xs font-bold px-2 py-1 rounded-lg shadow">+ Add</span>
                </div>
            </div>

            <!-- Info -->
            <div class="p-3">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white leading-snug line-clamp-2 mb-1">
                    <?= htmlspecialchars($product["name"]) ?>
                </h3>

                <?php if(!empty($product["description"])): ?>
                    <p class="text-xs text-gray-400 dark:text-gray-500 line-clamp-1 mb-2">
                        <?= htmlspecialchars($product["description"]) ?>
                    </p>
                <?php endif; ?>

                <div class="flex items-center justify-between mt-2">
                    <span class="font-black text-brand-600 dark:text-brand-400 text-base">
                        $<?= number_format($product["price"], 2) ?>
                    </span>
                    <button
                        class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400 flex items-center justify-center hover:bg-brand-500 hover:text-white transition-all active:scale-90 font-black text-lg"
                        @click.stop="addToCart(<?= htmlspecialchars(json_encode([
                            'id'    => $product["id"],
                            'name'  => $product["name"],
                            'price' => $product["price"],
                            'image' => $product["image"] ?? null,
                        ])) ?>)"
                    >+</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Empty state -->
        <?php if(empty($proudects)): ?>
        <div class="col-span-full py-24 flex flex-col items-center gap-4 text-center animate-fade-in">
            <div class="text-6xl">🍽️</div>
            <p class="text-xl font-bold text-gray-400">No products in this category</p>
            <a href="/products" class="text-brand-500 font-bold hover:underline text-sm">View all products</a>
        </div>
        <?php endif; ?>

    </div>
</main>
<?php include "layout/footer.php" ?>