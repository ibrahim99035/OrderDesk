<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        syne: ['Syne', 'sans-serif'],
                        mono: ['DM Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
</head>
<body class="font-mono bg-neutral-950 text-neutral-100 min-h-screen">

<?php
// Pull and clear flash session messages
$success = $_SESSION['success'] ?? null;
$error   = $_SESSION['error']   ?? null;
unset($_SESSION['success'], $_SESSION['error']);

// Active nav helper
$current = $current ?? '';
$navLink = fn(string $key, string $href, string $label) =>
    sprintf(
        '<a href="%s" class="text-xs tracking-widest uppercase px-3 py-2 transition-colors %s">%s</a>',
        BASE_URL . $href,
        $key === $current
            ? 'text-lime-300 border-b border-lime-300'
            : 'text-neutral-400 hover:text-neutral-100',
        $label
    );
?>

<?php if ($success): ?>
<div id="toastSuccess"
     class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999] flex items-center gap-3
            bg-neutral-900 border border-emerald-500/40 text-emerald-400
            px-6 py-3 shadow-2xl transition-all duration-300">
    <span>✓</span>
    <span class="text-xs tracking-wide"><?= htmlspecialchars($success) ?></span>
</div>
<script>
    setTimeout(() => {
        const t = document.getElementById('toastSuccess');
        if (t) { t.style.opacity = '0'; t.style.transform = 'translateX(-50%) translateY(-8px)'; setTimeout(() => t.remove(), 300); }
    }, 3000);
</script>
<?php endif; ?>

<?php if ($error): ?>
<div id="toastError"
     class="fixed top-5 left-1/2 -translate-x-1/2 z-[9999] flex items-center gap-3
            bg-neutral-900 border border-red-500/40 text-red-400
            px-6 py-3 shadow-2xl transition-all duration-300">
    <span>✕</span>
    <span class="text-xs tracking-wide"><?= htmlspecialchars($error) ?></span>
</div>
<script>
    setTimeout(() => {
        const t = document.getElementById('toastError');
        if (t) { t.style.opacity = '0'; t.style.transform = 'translateX(-50%) translateY(-8px)'; setTimeout(() => t.remove(), 300); }
    }, 4000);
</script>
<?php endif; ?>

<!-- NAV -->
<nav class="sticky top-0 z-50 bg-neutral-900 border-b border-neutral-800">
    <div class="h-0.5 bg-lime-300"></div>
    <div class="max-w-7xl mx-auto px-6 py-0 flex items-center justify-between">

        <!-- Logo -->
        <a href="<?= BASE_URL ?>/admin/home"
           class="font-syne font-extrabold text-lg text-neutral-100 py-4 mr-10 tracking-tight hover:text-lime-300 transition-colors">
            🍽 <?= APP_NAME ?>
        </a>

        <!-- Links -->
        <div class="flex items-center gap-1 flex-1">
            <?= $navLink('home',       '/admin/home',       'Dashboard') ?>
            <?= $navLink('users',      '/admin/users',      'Users') ?>
            <?= $navLink('proudects',  '/products',         'Products') ?>
            <?= $navLink('categories', '/admin/categories', 'Categories') ?>
            <?= $navLink('orders',     '/admin/orders',     'Orders') ?>
            <?= $navLink('delivery',   '/admin/delivery',   'Delivery') ?>
        </div>

        <!-- User + Logout -->
        <div class="flex items-center gap-4 py-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-lime-300 flex items-center justify-center text-neutral-950 font-syne font-bold text-xs">
                    <?= strtoupper(substr(\App\core\Session::get('name') ?? 'A', 0, 1)) ?>
                </div>
                <span class="text-xs text-neutral-300"><?= htmlspecialchars(\App\core\Session::get('name') ?? 'Admin') ?></span>
            </div>
            <a href="<?= BASE_URL ?>/logout"
               class="text-xs tracking-widest uppercase text-neutral-500 hover:text-red-400 transition-colors">
                Logout
            </a>
        </div>

    </div>
</nav>