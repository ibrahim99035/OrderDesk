<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<style>
body{
font-family: Inter, sans-serif;
}
</style>

</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">


<div class="w-full max-w-md px-6">

<div class="bg-white rounded-3xl shadow-2xl p-10">


<h2 class="text-3xl font-extrabold text-gray-800 mb-2">
Welcome Back
</h2>

<p class="text-gray-500 mb-8">
Sign in to continue 
</p>


<?php if (!empty($error)): ?>
<div class="bg-red-100 text-red-600 px-4 py-3 rounded-lg mb-5 text-sm">
<?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>


<form method="POST" action="<?= BASE_URL ?>/login" class="space-y-5">


<div>

<label class="text-sm text-gray-600">
Email
</label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
required
class="w-full mt-1 px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-400 outline-none"
/>

</div>



<div>

<label class="text-sm text-gray-600">
Password
</label>

<input
type="password"
name="password"
required
class="w-full mt-1 px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-400 outline-none"
/>

</div>



<button
class="w-full py-3 rounded-xl text-white font-semibold
bg-gradient-to-r from-indigo-500 to-purple-600
hover:from-indigo-600 hover:to-purple-700
transition shadow-lg"
>
Sign In
</button>


</form>


<div class="text-center mt-6 text-sm text-gray-500">
Secure Admin Login
</div>


</div>

</div>

</body>
</html>