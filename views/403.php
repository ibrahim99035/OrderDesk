<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }

        .float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.4s infinite;
        }
    </style>

</head>

<body class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-red-900 text-white flex items-center justify-center relative overflow-hidden">


    <!-- glow -->
    <div class="absolute top-10 left-10 w-40 h-40 bg-red-500/30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-40 h-40 bg-orange-500/30 rounded-full blur-3xl animate-pulse"></div>


    <div class="text-center z-10">

        <!-- code -->
        <h1 class="text-9xl font-extrabold tracking-widest text-red-500 float">
            403
        </h1>

        <!-- line -->
        <div class="w-32 h-1 bg-red-600 mx-auto my-4 rounded"></div>

        <!-- icon -->
        <div class="text-5xl mb-4 shake">
            ⚠️
        </div>

        <!-- title -->
        <h2 class="text-2xl font-semibold text-red-400 mb-2">
            Forbidden Access
        </h2>

        <!-- text -->
        <p class="text-gray-300 mb-6">
            You don't have permission to access this page.
        </p>

        <!-- button -->
        <a href="/"
           class="inline-block px-6 py-3 rounded-xl
           bg-red-600 hover:bg-red-700
           transition duration-300
           shadow-lg hover:shadow-red-500/40">

            Go Back Home

        </a>

    </div>

</body>
</html>