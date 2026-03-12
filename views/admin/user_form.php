<?php require __DIR__ . '/../partials/nav_admin.php'; ?>

<?php
$isEdit = isset($user);
$title  = $isEdit ? 'Edit User' : 'Add User';
$user   = $user ?? ['name' => '', 'email' => '', 'room' => '', 'extension' => '', 'image' => null];
$rooms  = [
  ['id' => 1, 'room_number' => '2006'],
  ['id' => 2, 'room_number' => '2010'],
  ['id' => 3, 'room_number' => '1505'],
];
?>

<div class="max-w-lg mx-auto px-6 py-8">
  <h1 class="text-2xl font-bold text-gray-800 mb-8"><?= $title ?></h1>

  <div class="bg-white rounded-2xl shadow-sm p-6">
    <form method="POST"
      action="<?= $isEdit ? BASE_URL . '/admin/users/edit/' . $user['id'] : BASE_URL . '/admin/users/add' ?>"
      enctype="multipart/form-data" id="user-form" class="space-y-5">

      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Name</label>
        <input id="uname" name="name" type="text"
          value="<?= htmlspecialchars($user['name']) ?>"
          placeholder="Full name"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <p id="name-error" class="hidden text-red-500 text-xs mt-1">Name is required.</p>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
        <input id="uemail" name="email" type="email"
          value="<?= htmlspecialchars($user['email']) ?>"
          placeholder="user@company.com"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <p id="email-error" class="hidden text-red-500 text-xs mt-1">Valid email is required.</p>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">
          Password <?= $isEdit ? '<span class="text-gray-400 font-normal">(leave blank to keep current)</span>' : '' ?>
        </label>
        <input id="upass" name="password" type="password"
          placeholder="••••••••"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <p id="pass-error" class="hidden text-red-500 text-xs mt-1">Password must be at least 6 characters.</p>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Confirm Password</label>
        <input id="uconfirm" name="confirm_password" type="password"
          placeholder="••••••••"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        <p id="confirm-error" class="hidden text-red-500 text-xs mt-1">Passwords do not match.</p>
      </div>

      <!-- Room -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Room No.</label>
        <select name="room_id"
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select room</option>
          <?php foreach ($rooms as $r): ?>
            <option value="<?= $r['id'] ?>"
              <?= ($user['room_id'] ?? '') == $r['id'] ? 'selected' : '' ?>>
              <?= $r['room_number'] ?>
            </option>
          <?php endforeach; ?>
        </select>
        <p id="room-error" class="hidden text-red-500 text-xs mt-1">Room is required.</p>
      </div>

      <!-- Extension -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Ext.</label>
        <input name="extension" type="text"
          value="<?= htmlspecialchars($user['extension']) ?>"
          placeholder="e.g. 5605"
          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
      </div>

      <!-- Profile Picture -->
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Profile Picture</label>
        <?php if ($isEdit && $user['image']): ?>
          <img src="<?= USER_UPLOAD_URL . htmlspecialchars($user['image']) ?>"
            class="w-12 h-12 rounded-full object-cover border mb-2 mx-auto block"/>
        <?php endif; ?>
        <div class="border-2 border-dashed border-gray-300 rounded-xl px-4 py-5 text-center cursor-pointer hover:border-blue-400 transition"
          onclick="document.getElementById('profile-input').click()">
          <p class="text-sm text-gray-400">Click to browse</p>
          <p id="profile-name" class="text-xs text-blue-500 mt-1 hidden"></p>
        </div>
        <input id="profile-input" name="image" type="file"
          accept="image/*" class="hidden" onchange="previewProfile(this)"/>
        <img id="profile-preview"
          class="hidden mt-3 rounded-full w-14 h-14 object-cover border mx-auto"/>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-2">
        <button type="submit" onclick="return validateUser()"
          class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition">
          Save
        </button>
        <button type="reset" onclick="resetUserForm()"
          class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-lg transition">
          Reset
        </button>
      </div>

    </form>
  </div>
</div>

<script>
const isEdit = <?= $isEdit ? 'true' : 'false' ?>;

function previewProfile(input) {
  const file    = input.files[0];
  const preview = document.getElementById('profile-preview');
  const name    = document.getElementById('profile-name');
  if (!file) return;
  name.textContent = file.name;
  name.classList.remove('hidden');
  const reader  = new FileReader();
  reader.onload = e => {
    preview.src = e.target.result;
    preview.classList.remove('hidden');
  };
  reader.readAsDataURL(file);
}

function validateUser() {
  const name    = document.getElementById('uname');
  const email   = document.getElementById('uemail');
  const pass    = document.getElementById('upass');
  const confirm = document.getElementById('uconfirm');

  const errors = {
    'name-error':    () => !name.value.trim(),
    'email-error':   () => !/\S+@\S+\.\S+/.test(email.value),
    'pass-error':    () => !isEdit && pass.value.length < 6,
    'confirm-error': () => pass.value && confirm.value !== pass.value,
    'room-error':    () => !document.querySelector('select[name=room_id]').value,
  };

  const fields = { 'name-error': name, 'email-error': email, 'pass-error': pass, 'confirm-error': confirm };
  let valid = true;

  for (const [errId, check] of Object.entries(errors)) {
    const errEl = document.getElementById(errId);
    const field = fields[errId];
    errEl.classList.add('hidden');
    if (field) field.classList.remove('border-red-500');
    if (check()) {
      errEl.classList.remove('hidden');
      if (field) field.classList.add('border-red-500');
      valid = false;
    }
  }

  return valid;
}

function resetUserForm() {
  document.getElementById('profile-input').value = '';
  document.getElementById('profile-name').classList.add('hidden');
  document.getElementById('profile-preview').classList.add('hidden');
}
</script>