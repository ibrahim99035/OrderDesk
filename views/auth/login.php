<?php
// No nav, no header/footer — controller calls renderPartial for login
// header.php and footer.php are still included via render()
?>

<div class="min-h-screen bg-gray-100 flex items-center justify-center">
  <div class="bg-white rounded-2xl shadow-md p-10 w-full max-w-md">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Cafeteria</h1>

    <div id="error-banner"
      class="<?= isset($error) ? '' : 'hidden' ?> bg-red-100 text-red-700 text-sm rounded-lg px-4 py-3 mb-6">
      <?= htmlspecialchars($error ?? '') ?>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/login" id="login-form" class="space-y-5">

      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
        <input
          id="email" name="email" type="email"
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
          placeholder="you@company.com"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <p id="email-error" class="hidden text-red-500 text-xs mt-1">Please enter a valid email.</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
        <input
          id="password" name="password" type="password"
          placeholder="••••••••"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <p id="password-error" class="hidden text-red-500 text-xs mt-1">Password is required.</p>
      </div>

      <button type="submit" id="login-btn" onclick="return handleLogin()"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition">
        Login
      </button>

      <p class="text-center text-sm text-blue-500 hover:underline cursor-pointer">
        Forgot Password?
      </p>

    </form>
  </div>
</div>

<script>
function handleLogin() {
  const email    = document.getElementById('email');
  const password = document.getElementById('password');
  const emailErr = document.getElementById('email-error');
  const passErr  = document.getElementById('password-error');
  const btn      = document.getElementById('login-btn');

  emailErr.classList.add('hidden');
  passErr.classList.add('hidden');
  email.classList.remove('border-red-500');
  password.classList.remove('border-red-500');

  let valid = true;

  if (!email.value || !/\S+@\S+\.\S+/.test(email.value)) {
    emailErr.classList.remove('hidden');
    email.classList.add('border-red-500');
    valid = false;
  }

  if (!password.value) {
    passErr.classList.remove('hidden');
    password.classList.add('border-red-500');
    valid = false;
  }

  if (!valid) return false;

  btn.disabled = true;
  btn.textContent = 'Logging in...';
  btn.classList.add('opacity-70', 'cursor-not-allowed');
  return true;
}
</script>