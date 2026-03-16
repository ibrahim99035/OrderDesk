<!DOCTYPE html>
<html lang="en" dir="ltr" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeteria | Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn .35s ease both',
                        'slide-up': 'slideUp .4s ease both',
                        'pop':      'pop .25s ease both',
                        'bounce-in':'bounceIn .4s cubic-bezier(.36,.07,.19,.97) both',
                    },
                    keyframes: {
                        fadeIn:   { from:{ opacity:0 }, to:{ opacity:1 } },
                        slideUp:  { from:{ opacity:0, transform:'translateY(18px)' }, to:{ opacity:1, transform:'translateY(0)' } },
                        pop:      { '0%':{ transform:'scale(.92)' }, '60%':{ transform:'scale(1.06)' }, '100%':{ transform:'scale(1)' } },
                        bounceIn: { '0%':{ transform:'scale(.3)', opacity:0 }, '60%':{ transform:'scale(1.05)' }, '100%':{ transform:'scale(1)', opacity:1 } },
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 99px; }

        /* Glass card */
        .glass {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        /* Product card hover lift */
        .product-card { transition: transform .25s ease, box-shadow .25s ease; }
        .product-card:hover { transform: translateY(-5px); }

        /* Cart drawer transition */
        .cart-drawer { transition: transform .35s cubic-bezier(.4,0,.2,1); }
        .cart-drawer.closed { transform: translateX(110%); }
        
        /* Category pill active */
        .cat-pill { transition: all .2s ease; }
        .cat-pill.active,
        .cat-pill:hover { background: #f97316; color: #fff; box-shadow: 0 4px 15px rgba(249,115,22,.4); }

        /* Quantity button */
        .qty-btn { transition: background .15s; }
        .qty-btn:hover { background: #f97316; color: #fff; }

        /* Noise texture overlay */
        .noise::after {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 999;
            opacity: .025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size: 180px 180px;
        }

        /* Staggered product cards */
        .product-card:nth-child(1)  { animation-delay: .05s }
        .product-card:nth-child(2)  { animation-delay: .10s }
        .product-card:nth-child(3)  { animation-delay: .15s }
        .product-card:nth-child(4)  { animation-delay: .20s }
        .product-card:nth-child(5)  { animation-delay: .25s }
        .product-card:nth-child(6)  { animation-delay: .30s }
        .product-card:nth-child(7)  { animation-delay: .35s }
        .product-card:nth-child(8)  { animation-delay: .40s }

        /* Gradient mesh bg */
        .mesh-bg {
            background:
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(249,115,22,.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 110%, rgba(234,88,12,.10) 0%, transparent 55%);
        }
        .dark .mesh-bg {
            background:
                radial-gradient(ellipse 80% 50% at 20% -10%, rgba(249,115,22,.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 110%, rgba(234,88,12,.07) 0%, transparent 55%);
        }
    </style>
</head>

<body
    class="noise mesh-bg min-h-screen bg-orange-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased"
    x-data="cafeteriaApp()"
    x-init="init()"
>
<?php if(isset($_SESSION["success"])): ?>

<div id="toastSuccess"
class="fixed top-6 right-6 z-50
bg-green-500/90 backdrop-blur
text-white px-6 py-4 rounded-xl
shadow-2xl
translate-x-40 opacity-0
transition duration-500">

✅ Order added successfully

</div>

<script>

localStorage.removeItem("cafeteria_cart");

const toast = document.getElementById("toastSuccess");

setTimeout(()=>{
    toast.classList.remove("translate-x-40","opacity-0");
},100);

setTimeout(()=>{
    toast.classList.add("translate-x-40","opacity-0");
},3000);

</script>

<?php unset($_SESSION["success"]); ?>
<?php endif; ?>



<?php if(isset($_SESSION["error"])): ?>

<div id="toastError"
class="fixed top-6 right-6 z-50
bg-red-500/90 backdrop-blur
text-white px-6 py-4 rounded-xl
shadow-2xl
translate-x-40 opacity-0
transition duration-500">

❌ Failed to add order

</div>

<script>

const toast2 = document.getElementById("toastError");

setTimeout(()=>{
    toast2.classList.remove("translate-x-40","opacity-0");
},100);

setTimeout(()=>{
    toast2.classList.add("translate-x-40","opacity-0");
},3000);

</script>

<?php unset($_SESSION["error"]); ?>
<?php endif; ?>
<!-- ══════════════════════ NAVBAR ══════════════════════ -->
<header class="sticky top-0 z-40 glass border-b border-orange-100/60 dark:border-white/5 bg-white/70 dark:bg-gray-900/70 shadow-sm shadow-orange-100/30 dark:shadow-black/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 shrink-0">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-md shadow-orange-400/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="text-lg font-black tracking-tight text-brand-600 dark:text-brand-400">Cafeteria</span>
        </a>

        <!-- Actions -->
        <div class="flex items-center gap-3">

            <!-- Dark mode toggle -->
            <button
                @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                class="w-9 h-9 rounded-xl flex items-center justify-center bg-orange-100 dark:bg-white/10 hover:bg-orange-200 dark:hover:bg-white/20 transition-colors"
            >
                <svg x-show="!darkMode" class="w-4.5 h-4.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="darkMode" class="w-4.5 h-4.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- Cart button -->
            <button
                @click="cartOpen = true"
                class="relative flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-sm transition-all shadow-md shadow-brand-500/30 hover:shadow-brand-500/50 active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Cart</span>
                <span
                    x-show="totalItems > 0"
                    x-text="totalItems"
                    class="animate-bounce-in absolute -top-2 -right-2 min-w-[20px] h-5 px-1 rounded-full bg-white text-brand-600 text-xs font-black flex items-center justify-center shadow"
                ></span>
            </button>

        <a
        href="/logout"
        class="px-4 py-2
        rounded-lg
        bg-red-600
        hover:bg-red-700
        text-white
        font-semibold
        shadow
        transition
        hover:scale-105">

            Logout

        </a>
        </div>
    </div>
</header>

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

<!-- ══════════════════════ CART DRAWER ══════════════════════ -->
<!-- Overlay -->
<div
    x-show="cartOpen"
    x-transition:enter="transition-opacity duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="cartOpen = false"
    class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm"
    style="display:none"
></div>

<!-- Drawer -->
<div
    class="cart-drawer fixed top-0 right-0 h-full w-full max-w-sm z-50 flex flex-col bg-white dark:bg-gray-900 shadow-2xl"
    :class="cartOpen ? '' : 'closed'"
>
    <!-- Header -->
    <div class="flex items-center justify-between p-5 border-b border-orange-100 dark:border-white/10">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-brand-500 flex items-center justify-center shadow-md shadow-brand-500/30">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="font-black text-lg text-gray-900 dark:text-white">Your Cart</h2>
            <span
                x-show="totalItems > 0"
                x-text="totalItems + ' items'"
                class="text-xs font-bold px-2 py-0.5 rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-600 dark:text-brand-400"
            ></span>
        </div>
        <button @click="cartOpen = false"
            class="w-8 h-8 rounded-xl flex items-center justify-center bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 transition-colors text-gray-500 dark:text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-5 space-y-3">

        <!-- Empty -->
        <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full gap-4 text-center py-16">
            <div class="text-5xl">🛒</div>
            <p class="font-bold text-gray-400 dark:text-gray-500">Your cart is empty</p>
            <p class="text-sm text-gray-400 dark:text-gray-600">Add your favourite items to get started</p>
        </div>

        <!-- Items -->
        <template x-for="(item, idx) in cart" :key="item.id">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 animate-slide-up">

                <!-- Image / Emoji -->
                <div class="w-14 h-14 rounded-xl overflow-hidden bg-orange-100 dark:bg-gray-800 shrink-0 flex items-center justify-center text-2xl">
                    <template x-if="item.image">
                        <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!item.image">
                        <span>🍽️</span>
                    </template>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-gray-900 dark:text-white line-clamp-1" x-text="item.name"></p>
                    <p class="text-brand-500 text-xs font-black mt-0.5" x-text="'$' + (item.price * item.qty).toFixed(2)"></p>

                    <!-- Qty controls -->
                    <div class="flex items-center gap-2 mt-1.5">
                        <button @click="decreaseQty(item.id)"
                            class="qty-btn w-6 h-6 rounded-lg bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 flex items-center justify-center font-black text-sm">−</button>
                        <span class="text-sm font-black text-gray-800 dark:text-white min-w-[18px] text-center" x-text="item.qty"></span>
                        <button @click="increaseQty(item.id)"
                            class="qty-btn w-6 h-6 rounded-lg bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 flex items-center justify-center font-black text-sm">+</button>
                    </div>
                </div>

                <!-- Remove -->
                <button @click="removeItem(item.id)"
                    class="w-7 h-7 rounded-xl flex items-center justify-center bg-red-50 dark:bg-red-900/20 text-red-400 hover:bg-red-500 hover:text-white transition-all shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>

        <!-- Room Select -->

<div class="space-y-1">

    <label class="text-xs font-bold text-gray-500 dark:text-gray-400">
        Choose room
    </label>

    <select
        x-model="selectedRoom"
        class="w-full rounded-xl border border-gray-200 dark:border-white/10
               bg-white dark:bg-gray-800
               px-3 py-2 text-sm font-bold
               focus:ring-2 focus:ring-brand-500"
    >

        <option value="">Select room</option>

        <?php foreach($rooms as $r): ?>

            <option value="<?= $r['id'] ?>">

                <?= $r['room_number'] ?>

            </option>

        <?php endforeach ?>

    </select>

</div>
    </div>

    <!-- Footer -->
    <div class="p-5 border-t border-orange-100 dark:border-white/10 space-y-4 bg-white dark:bg-gray-900">

        <!-- Subtotal -->
        <div class="flex items-center justify-between">
            <span class="text-gray-500 dark:text-gray-400 font-medium">Total</span>
            <span class="text-xl font-black text-gray-900 dark:text-white" x-text="'$' + totalPrice.toFixed(2)"></span>
        </div>

        <!-- Checkout -->
        <button
            x-show="cart.length > 0"
            @click="checkout()"
            class="w-full py-3.5 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white font-black text-sm shadow-lg shadow-brand-500/30 hover:shadow-brand-500/50 transition-all active:scale-95 flex items-center justify-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7"/>
            </svg>
            إconferim
        </button>

        <button
            x-show="cart.length > 0"
            @click="clearCart()"
            class="w-full py-2.5 rounded-2xl border border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 font-bold text-sm transition-colors"
        > empty</button>
    </div>
</div>

<!-- ══════════════════════ TOAST ══════════════════════ -->
<div
    x-show="toast.show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-4 py-3 rounded-2xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 shadow-2xl font-bold text-sm"
    style="display:none"
>
    <span class="text-lg" x-text="toast.emoji"></span>
    <span x-text="toast.msg"></span>
</div>

<!-- ══════════════════════ ALPINE APP ══════════════════════ -->
<script>
function cafeteriaApp() {
    return {
        // ─── State ───────────────────────────────────────────
        cartOpen: false,
        cart: [],
        toast: { show: false, msg: '', emoji: '✅', timer: null },
        selectedRoom: "",

        // ─── Init ────────────────────────────────────────────
        init() {
            this.loadCart();
            // Sync darkMode from localStorage on init
            const saved = localStorage.getItem('darkMode');
            if (saved !== null) this.darkMode = saved === 'true';
        },

        // ─── Cart helpers ────────────────────────────────────
        loadCart() {

            try {

                const raw = localStorage.getItem("cafeteria_cart");

                if (!raw) return;

                const data = JSON.parse(raw);

                this.cart = data.items || [];

                

            } catch {

                this.cart = [];

            }

        },

        saveCart() {
                localStorage.setItem(
                    "cafeteria_cart",
                    JSON.stringify({
                        items: this.cart,
                      
                    })
                );
        },

        addToCart(product) {
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
                existing.qty++;
            } else {
                this.cart.push({ ...product, qty: 1 });
            }
            this.saveCart();
            this.showToast(`تمت إضافة ${product.name}`, '🛒');
        },

        increaseQty(id) {
            const item = this.cart.find(i => i.id === id);
            if (item) { item.qty++; this.saveCart(); }
        },

        decreaseQty(id) {
            const item = this.cart.find(i => i.id === id);
            if (!item) return;
            if (item.qty <= 1) {
                this.removeItem(id);
            } else {
                item.qty--;
                this.saveCart();
            }
        },

        removeItem(id) {
            this.cart = this.cart.filter(i => i.id !== id);
            this.saveCart();
            this.showToast(' deleted', '🗑️');
        },

        clearCart() {
            this.cart = [];
            this.saveCart();
            this.showToast('  deleted', '🧹');
        },

        // ─── Totals ──────────────────────────────────────────
        get totalItems() {
            return this.cart.reduce((s, i) => s + i.qty, 0);
        },

        get totalPrice() {
            return this.cart.reduce((s, i) => s + i.price * i.qty, 0);
        },

        // ─── Checkout ────────────────────────────────────────
        checkout() {

                if (this.cart.length === 0) return;

                if (!this.selectedRoom) {
                    this.showToast("choose room", "⚠️");
                    return;
                }

                const form = document.createElement("form");

                form.method = "POST";

                form.action = "/checkout";

                const cartInput = document.createElement("input");

                cartInput.type = "hidden";

                cartInput.name = "cart";

                cartInput.value = JSON.stringify(this.cart);

                form.appendChild(cartInput);



                const roomInput = document.createElement("input");

                roomInput.type = "hidden";

                roomInput.name = "room_id";

                roomInput.value = this.selectedRoom;

                form.appendChild(roomInput);



                document.body.appendChild(form);

                form.submit();

        },
        // ─── Toast ───────────────────────────────────────────
        showToast(msg, emoji = '✅') {
            clearTimeout(this.toast.timer);
            this.toast = { show: true, msg, emoji, timer: null };
            this.toast.timer = setTimeout(() => { this.toast.show = false; }, 2500);
        }
    };
}
</script>

</body>
</html>