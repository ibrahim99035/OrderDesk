<!DOCTYPE html>
<html lang="en" class="dark">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>FOODI — Premium Food Shop</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>

<script>
tailwind.config = {
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        display: ['Syne','sans-serif'],
        body: ['DM Sans','sans-serif']
      }
    }
  }
}
</script>

<style>
body{
 background:#0a0a12;
 color:white;
 font-family:'DM Sans',sans-serif;
}
.glass{
 background:rgba(255,255,255,.05);
 backdrop-filter:blur(20px);
 border:1px solid rgba(255,255,255,.08);
}
</style>

</head>
<body>

<nav class="glass p-4 flex justify-between">

<h1 class="text-xl font-bold">FOODI</h1>

<button onclick="openCart()" class="bg-purple-600 px-3 py-1 rounded">
Cart
<span id="nav-badge">0</span>
</button>

</nav>