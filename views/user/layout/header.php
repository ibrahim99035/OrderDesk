<!DOCTYPE html>
<html lang="en" dir="ltr" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeteria </title>
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
         <div class="flex gap-4 items-center">
             <a href="/" class="flex items-center gap-2 shrink-0">
                 <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-md shadow-orange-400/30">
                     <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                     </svg>
                 </div>
                 <span class="text-lg font-black tracking-tight text-brand-600 dark:text-brand-400">Cafeteria</span>
             </a>
     
          <a href="/product"
            class="px-3 py-2 rounded-lg text-sm font-medium
                    text-gray-700 dark:text-gray-200
                    bg-gray-100 dark:bg-gray-800
                    hover:bg-blue-500 hover:text-white
                    dark:hover:bg-blue-600
                    transition duration-200">
                All Products
            </a>

                 <a href="/orders/my"
            class="px-3 py-2 rounded-lg text-sm font-medium
                    text-gray-700 dark:text-gray-200
                    bg-gray-100 dark:bg-gray-800
                    hover:bg-blue-500 hover:text-white
                    dark:hover:bg-blue-600
                    transition duration-200">
                my Orders
            </a>


     
         </div>

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
