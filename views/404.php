<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .float {
            animation: float 3s ease-in-out infinite;
        }
    </style>

</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 text-white flex items-center justify-center">

    <div class="text-center">

        <!-- 404 -->
        <h1 class="text-9xl font-extrabold tracking-widest float">
            404
        </h1>

        <!-- line -->
        <div class="w-32 h-1 bg-blue-500 mx-auto my-4 rounded"></div>

        <!-- text -->
        <h2 class="text-2xl font-semibold mb-2">
            Page Not Found
        </h2>

        <p class="text-gray-400 mb-6">
            Sorry, the page you are looking for does not exist.
        </p>

        <!-- button -->
        <a href="/"
           class="inline-block px-6 py-3 rounded-xl
           bg-blue-600 hover:bg-blue-700
           transition duration-300
           shadow-lg hover:shadow-blue-500/40">

            Go Home

        </a>

        <!-- animation circle -->
        <div class="absolute top-10 left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>

    </div>

</body>
</html>