<?php require __DIR__ . '/../partials/nav_admin.php'; ?>

<?php
$users = [
  ['id' => 1, 'name' => 'Abdulrahman Hamdy', 'room' => '2010', 'extension' => '5605', 'initials' => 'AH'],
  ['id' => 2, 'name' => 'Islam Askar',        'room' => '2010', 'extension' => '5605', 'initials' => 'IA'],
  ['id' => 3, 'name' => 'Sayed Fathy',        'room' => '2010', 'extension' => '5605', 'initials' => 'SF'],
];
?>

<div class="max-w-4xl mx-auto px-6 py-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">All Users</h1>
    <a href="<?= BASE_URL ?>/admin/users/add"
      class="text-sm text-blue-600 hover:underline font-medium">+ Add user</a>
  </div>

  <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-100 text-gray-600 text-left">
        <tr>
          <th class="px-5 py-3">Name</th>
          <th class="px-5 py-3">Room</th>
          <th class="px-5 py-3 text-center">Image</th>
          <th class="px-5 py-3 text-center">Ext.</th>
          <th class="px-5 py-3 text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $i => $u): ?>
          <tr class="border-t border-gray-100 <?= $i % 2 !== 0 ? 'bg-gray-50' : '' ?>"
            id="user-row-<?= $u['id'] ?>">
            <td class="px-5 py-3 font-medium text-gray-700"><?= htmlspecialchars($u['name']) ?></td>
            <td class="px-5 py-3 text-gray-600"><?= $u['room'] ?></td>
            <td class="px-5 py-3 text-center">
              <div class="w-8 h-8 rounded-full bg-blue-100 mx-auto flex items-center justify-center text-blue-600 text-xs font-bold">
                <?= $u['initials'] ?>
              </div>
            </td>
            <td class="px-5 py-3 text-center text-gray-600"><?= $u['extension'] ?></td>
            <td class="px-5 py-3 text-center space-x-3">
              <a href="<?= BASE_URL ?>/admin/users/edit/<?= $u['id'] ?>"
                class="text-xs font-semibold text-blue-500 hover:underline">edit</a>
              <form method="POST" action="<?= BASE_URL ?>/admin/users/delete/<?= $u['id'] ?>"
                style="display:inline">
                <button type="submit" onclick="return confirmDeleteUser(event, this, <?= $u['id'] ?>)"
                  class="text-xs font-semibold text-red-500 hover:text-red-700">delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div class="flex justify-center items-center gap-2 mt-6 text-sm">
    <button class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50">«</button>
    <button class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50">‹</button>
    <button class="px-3 py-1.5 rounded-lg bg-blue-600 text-white font-semibold">1</button>
    <button class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50">2</button>
    <button class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50">›</button>
    <button class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50">»</button>
  </div>
</div>

<script>
function confirmDeleteUser(e, btn, id) {
  if (!confirm('Delete this user?')) {
    e.preventDefault();
    return false;
  }
  e.preventDefault();
  const row = document.getElementById('user-row-' + id);
  row.style.transition = 'opacity 0.3s';
  row.style.opacity    = '0';
  setTimeout(() => row.remove(), 300);
  return false;
}
</script>