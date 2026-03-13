<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CFTRIE</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>

@keyframes float {
0%{transform:translateY(0)}
50%{transform:translateY(-12px)}
100%{transform:translateY(0)}
}

.float{
animation: float 4s ease-in-out infinite;
}

</style>

</head>

<body class="bg-gray-950 text-white min-h-screen overflow-hidden">

<!-- glow bg -->
<div class="absolute top-0 left-0 w-72 h-72 bg-blue-600/30 blur-3xl rounded-full animate-pulse"></div>
<div class="absolute bottom-0 right-0 w-72 h-72 bg-purple-600/30 blur-3xl rounded-full animate-pulse"></div>


<!-- NAVBAR -->
<nav class="flex justify-between items-center px-10 py-5 bg-black/40 backdrop-blur border-b border-gray-800 relative z-10">

<h1 class="text-2xl font-bold text-blue-500">
CFTRIE
</h1>

<a href="/login"
class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition shadow-lg">
Login
</a>

</nav>


<!-- HERO -->
<section class="flex flex-col items-center justify-center text-center h-[80vh] relative z-10">

<h1 class="text-6xl font-extrabold mb-6 float">
CFTRIE SYSTEM
</h1>

<p class="text-gray-400 max-w-xl mb-8">
Fast • Secure • Clean UI  
Static interface for modern web systems
</p>

<a href="/login"
class="px-8 py-3 bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-lg hover:shadow-blue-500/40">

Login to Continue

</a>

</section>


<!-- footer -->
<footer class="text-center text-gray-500 pb-6 relative z-10">

© 2026 CFTRIE

</footer>


</body>
</html>