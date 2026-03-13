<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
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
<body class="font-mono bg-neutral-950 text-neutral-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-6">

        <div class="border border-neutral-800 bg-neutral-900 p-10 relative">

            <!-- Accent top line -->
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-lime-300"></div>

            <p class="text-lime-300 text-xs tracking-widest uppercase mb-3">Admin Panel</p>
            <h1 class="font-syne font-extrabold text-3xl mb-8">Sign In</h1>

            <?php if (!empty($error)): ?>
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs px-4 py-3 mb-6 tracking-wide">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-5">

                <div class="flex flex-col gap-2">
                    <label for="email" class="text-neutral-500 text-xs tracking-widest uppercase">Email</label>
                    <input
                        type="email" id="email" name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        placeholder="you@example.com"
                        required autofocus
                        class="bg-neutral-950 border border-neutral-800 text-neutral-100 font-mono text-sm px-4 py-3 outline-none focus:border-lime-300 transition-colors placeholder:text-neutral-700"
                    >
                </div>

                <div class="flex flex-col gap-2">
                    <label for="password" class="text-neutral-500 text-xs tracking-widest uppercase">Password</label>
                    <input
                        type="password" id="password" name="password"
                        placeholder="••••••••"
                        required
                        class="bg-neutral-950 border border-neutral-800 text-neutral-100 font-mono text-sm px-4 py-3 outline-none focus:border-lime-300 transition-colors placeholder:text-neutral-700"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-lime-300 text-neutral-950 font-syne font-bold text-xs tracking-widest uppercase py-3.5 mt-2 hover:bg-lime-200 active:scale-[.99] transition-all"
                >
                    Enter →
                </button>

            </form>

            <p class="mt-8 text-xs text-neutral-600 text-center tracking-wide">
                Restricted access — authorised personnel only
            </p>

        </div>
    </div>

</body>
</html>