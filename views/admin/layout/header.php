<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
</head>


<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

<?php

$success = $_SESSION["success"] ?? null;
$error   = $_SESSION["error"] ?? null;



unset($_SESSION["success"]);
unset($_SESSION["error"]);



?>

<?php if ($success): ?>

<div
id="toastSuccess"
class="fixed top-20 left-1/2 -translate-x-1/2
z-[9999]
flex items-center gap-3
bg-emerald-500 text-white
px-6 py-3
rounded-xl
shadow-2xl
border border-emerald-300">

    <span class="text-xl">✅</span>

    <span class="font-semibold">
        <?= $success ?> added successfully
    </span>

</div>

<script>

setTimeout(() => {

    const t = document.getElementById("toastSuccess");

    if (t) {
        t.classList.add("opacity-0","-translate-y-2");
        setTimeout(()=> t.remove(),300);
    }

}, 3000);

</script>

<?php endif; ?>



<?php if ($error): ?>

<div
id="toastError"
class="fixed top-20 left-1/2 -translate-x-1/2
z-[9999]
flex items-center gap-3
bg-red-500 text-white
px-6 py-3
rounded-xl
shadow-2xl
border border-red-300">

    <span class="text-xl">❌</span>

    <span class="font-semibold">
        <?= $error ?>
    </span>

</div>

<script>

setTimeout(() => {

    const t = document.getElementById("toastError");

    if (t) {
        t.classList.add("opacity-0","-translate-y-2");
        setTimeout(()=> t.remove(),300);
    }

}, 4000);

</script>

<?php endif; ?>
<!-- NAVBAR -->
<nav
class="sticky top-0 z-50
bg-white/70 dark:bg-gray-800/70
backdrop-blur-xl
border-b border-gray-200 dark:border-gray-700
shadow-sm">

<div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">


    <!-- LEFT -->
    <div class="flex items-center gap-10">


        <!-- LOGO -->
        <div
        class="text-2xl font-bold
        text-blue-600 dark:text-blue-400
        tracking-wide
        transition
        hover:scale-105">

            🍽️ Cafeteria

        </div>


        <!-- LINKS -->
        <div class="hidden md:flex items-center gap-6 text-sm font-medium">


            <a href="/admin/home"
            class="px-2 py-1 rounded-md
            text-gray-700 dark:text-gray-300
            hover:text-blue-600
            dark:hover:text-blue-400
            hover:bg-gray-100
            dark:hover:bg-gray-700
            transition">

                Dashboard

            </a>

    <a href="/orders"
            class="px-2 py-1 rounded-md
            text-gray-700 dark:text-gray-300
            hover:text-blue-600
            dark:hover:text-blue-400
            hover:bg-gray-100
            dark:hover:bg-gray-700
            transition">

                orders

            </a>
       



            <a href="/products/"
            class="px-3 py-1.5 rounded-md
            hover:text-blue-600
            dark:hover:text-blue-400
            hover:bg-gray-100
            dark:hover:bg-gray-700 text-white
            shadow <?= $current == "proudects" ? 'bg-blue-600' : ' ' ?> transition">

                Products

            </a>
                     <a href="/admin/categories/"
            class="px-3 py-1.5 rounded-md
            hover:text-blue-600
            dark:hover:text-blue-400
            hover:bg-gray-100
            dark:hover:bg-gray-700 text-white
            shadow <?= $current == "categories" ? 'bg-blue-600' : ' ' ?> transition">

                categories

            </a>

                </a>
            <a href="/admin/users/"
            class="px-3 py-1.5 rounded-md
            hover:text-blue-600
            dark:hover:text-blue-400
            hover:bg-gray-100
            dark:hover:bg-gray-700 text-white
            shadow <?= $current == "users" ? 'bg-blue-600' : ' ' ?> transition">

                users

            </a>




        </div>

    </div>



    <!-- RIGHT -->
    <div class="flex items-center gap-3">


        <!-- DARK MODE -->
        <button
        onclick="toggleDarkMode()"
        class="w-10 h-10 flex items-center justify-center
        rounded-lg
        bg-gray-100 dark:bg-gray-700
        hover:bg-gray-200
        dark:hover:bg-gray-600
        transition
        hover:scale-110">

            🌙

        </button>



        <!-- LOGOUT -->
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
</nav>