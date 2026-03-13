<?php include "layout/header.php" ; ?>

<div class="min-h-screen bg-neutral-950 text-neutral-100 font-mono">
<div class="max-w-6xl mx-auto px-6 py-12">

    <!-- Page Header -->
    <div class="flex items-end justify-between mb-10 flex-wrap gap-4">
        <div>
            <p class="text-lime-300 text-xs tracking-widest uppercase mb-2">Admin / Users</p>
            <h1 class="font-syne font-extrabold text-4xl">
                Users <span class="text-neutral-600">(<?= count($users) ?>)</span>
            </h1>
        </div>
        <button
            onclick="openModal('createModal')"
            class="bg-lime-300 text-neutral-950 font-syne font-bold text-xs tracking-widest uppercase px-5 py-3 hover:bg-lime-200 active:scale-[.99] transition-all"
        >
            + New User
        </button>
    </div>

    <!-- Table -->
    <div class="border border-neutral-800 overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-neutral-900 border-b border-neutral-800">
                <tr>
                    <th class="px-4 py-4 text-left text-xs text-neutral-500 tracking-widest uppercase">User</th>
                    <th class="px-4 py-4 text-left text-xs text-neutral-500 tracking-widest uppercase">Email</th>
                    <th class="px-4 py-4 text-left text-xs text-neutral-500 tracking-widest uppercase">Room</th>
                    <th class="px-4 py-4 text-left text-xs text-neutral-500 tracking-widest uppercase">Ext.</th>
                    <th class="px-4 py-4 text-left text-xs text-neutral-500 tracking-widest uppercase">Role</th>
                    <th class="px-4 py-4 text-left text-xs text-neutral-500 tracking-widest uppercase">Status</th>
                    <th class="px-4 py-4 text-left text-xs text-neutral-500 tracking-widest uppercase">Joined</th>
                    <th class="px-4 py-4 text-right text-xs text-neutral-500 tracking-widest uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-800/60">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-neutral-600 text-xs tracking-wide">
                            No users found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-neutral-900/50 transition-colors">

                        <!-- Avatar + Name -->
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($user['image'])): ?>
                                    <img src="/public/uploads/users/<?= htmlspecialchars($user['image']) ?>"
                                         class="w-8 h-8 rounded-full object-cover border border-neutral-700" alt="">
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center text-xs text-neutral-500 font-syne font-bold">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="font-medium text-neutral-100"><?= htmlspecialchars($user['name']) ?></span>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-neutral-400"><?= htmlspecialchars($user['email']) ?></td>

                        <td class="px-4 py-3 text-neutral-400">
                            <?= !empty($user['room_number']) ? htmlspecialchars($user['room_number']) : '<span class="text-neutral-700">—</span>' ?>
                        </td>

                        <td class="px-4 py-3 text-neutral-400">
                            <?= !empty($user['extension']) ? htmlspecialchars($user['extension']) : '<span class="text-neutral-700">—</span>' ?>
                        </td>

                        <td class="px-4 py-3">
                            <?php if ($user['role'] === 'admin'): ?>
                                <span class="bg-lime-300/10 text-lime-300 border border-lime-300/20 text-xs px-2 py-1 tracking-widest uppercase">Admin</span>
                            <?php else: ?>
                                <span class="bg-neutral-800 text-neutral-400 text-xs px-2 py-1 tracking-widest uppercase">User</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-4 py-3">
                            <?php if ($user['is_active']): ?>
                                <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs px-2 py-1 tracking-widest uppercase">Active</span>
                            <?php else: ?>
                                <span class="bg-red-500/10 text-red-400 border border-red-500/20 text-xs px-2 py-1 tracking-widest uppercase">Inactive</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-4 py-3 text-neutral-500 text-xs">
                            <?= date('d M Y', strtotime($user['created_at'])) ?>
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button
                                    onclick='openEditModal(<?= json_encode($user) ?>)'
                                    class="border border-neutral-700 text-neutral-300 font-syne font-bold text-xs tracking-widest uppercase px-3 py-2 hover:border-neutral-400 hover:text-white transition-all"
                                >Edit</button>

                                <?php if ((int)$user['id'] !== (int)\App\core\Session::get('user_id')): ?>
                                <button
                                    onclick='openDeleteModal(<?= $user["id"] ?>, <?= json_encode($user["name"]) ?>)'
                                    class="border border-red-500/30 text-red-400 font-syne font-bold text-xs tracking-widest uppercase px-3 py-2 hover:bg-red-500/10 transition-all"
                                >Delete</button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</div>


<!-- ═══════════════════════════════════════════════════════
     SHARED FIELD MACRO (PHP helper to avoid duplication)
════════════════════════════════════════════════════════ -->
<?php
// Builds a reusable rooms <select> to avoid repeating the PHP loop twice
function roomOptions(array $rooms, $selectedId = null): string {
    $html = '<option value="">— No Room —</option>';
    foreach ($rooms as $r) {
        $sel   = (int)$r['id'] === (int)$selectedId ? 'selected' : '';
        $html .= "<option value=\"{$r['id']}\" $sel>" . htmlspecialchars($r['room_number']) . "</option>";
    }
    return $html;
}

$inputCls  = "bg-neutral-950 border border-neutral-800 text-neutral-100 font-mono text-sm px-4 py-3 outline-none focus:border-lime-300 transition-colors placeholder:text-neutral-700 w-full";
$labelCls  = "text-neutral-500 text-xs tracking-widest uppercase mb-2 block";
$fieldCls  = "flex flex-col";
?>


<!-- ═══════════════════════════════════════════
     CREATE MODAL
════════════════════════════════════════════ -->
<div id="createModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeModal('createModal')"></div>

    <div class="absolute right-0 top-0 bottom-0 w-full max-w-lg bg-neutral-900 border-l border-neutral-800 flex flex-col translate-x-full transition-transform duration-300" id="createPanel">
        <div class="h-0.5 bg-lime-300"></div>

        <div class="flex items-center justify-between px-8 py-5 border-b border-neutral-800">
            <div>
                <p class="text-lime-300 text-xs tracking-widest uppercase mb-1">Users</p>
                <h2 class="font-syne font-bold text-xl">New User</h2>
            </div>
            <button onclick="closeModal('createModal')" class="text-neutral-500 hover:text-white text-xl transition-colors">✕</button>
        </div>

        <form method="POST" action="/admin/users/store" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-y-auto">
            <div class="px-8 py-6 space-y-5 flex-1">

                <?php if (!empty($createErrors)): ?>
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs px-4 py-3 tracking-wide space-y-1">
                        <?php foreach ($createErrors as $e): ?>
                            <div>— <?= htmlspecialchars($e) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Name -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" placeholder="Full name"
                           value="<?= htmlspecialchars($createOld['name'] ?? '') ?>"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Email -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" placeholder="user@example.com"
                           value="<?= htmlspecialchars($createOld['email'] ?? '') ?>"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Password -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Password <span class="text-red-400">*</span></label>
                    <input type="password" name="password" placeholder="Min. 8 characters"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Role + Room (2 cols) -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="<?= $fieldCls ?>">
                        <label class="<?= $labelCls ?>">Role</label>
                        <select name="role" class="<?= $inputCls ?>">
                            <option value="user"  <?= ($createOld['role'] ?? '') === 'user'  ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= ($createOld['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="<?= $fieldCls ?>">
                        <label class="<?= $labelCls ?>">Room</label>
                        <select name="room_id" class="<?= $inputCls ?>">
                            <?= roomOptions($rooms, $createOld['room_id'] ?? null) ?>
                        </select>
                    </div>
                </div>

                <!-- Extension -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Extension</label>
                    <input type="text" name="extension" placeholder="e.g. 101"
                           value="<?= htmlspecialchars($createOld['extension'] ?? '') ?>"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Image -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Profile Image</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                           class="text-sm text-neutral-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-neutral-800 file:text-neutral-300 file:text-xs file:font-syne file:uppercase file:tracking-widest hover:file:bg-neutral-700 cursor-pointer">
                    <p class="text-neutral-600 text-xs mt-1">JPEG, PNG or WebP — max 2 MB</p>
                </div>

                <!-- is_active -->
                <div class="flex items-center gap-3 pt-1">
                    <input type="checkbox" name="is_active" id="create_is_active" value="1"
                           <?= !isset($createOld) || !empty($createOld['is_active']) ? 'checked' : '' ?>
                           class="w-4 h-4 accent-lime-300 cursor-pointer">
                    <label for="create_is_active" class="text-neutral-400 text-sm cursor-pointer">Active account</label>
                </div>

            </div>

            <div class="px-8 py-5 border-t border-neutral-800 flex gap-3">
                <button type="submit"
                    class="flex-1 bg-lime-300 text-neutral-950 font-syne font-bold text-xs tracking-widest uppercase py-3 hover:bg-lime-200 active:scale-[.99] transition-all">
                    Create User
                </button>
                <button type="button" onclick="closeModal('createModal')"
                    class="px-5 border border-neutral-700 text-neutral-400 font-syne font-bold text-xs tracking-widest uppercase hover:border-neutral-500 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════════════════
     EDIT MODAL
════════════════════════════════════════════ -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeModal('editModal')"></div>

    <div class="absolute right-0 top-0 bottom-0 w-full max-w-lg bg-neutral-900 border-l border-neutral-800 flex flex-col translate-x-full transition-transform duration-300" id="editPanel">
        <div class="h-0.5 bg-lime-300"></div>

        <div class="flex items-center justify-between px-8 py-5 border-b border-neutral-800">
            <div>
                <p class="text-lime-300 text-xs tracking-widest uppercase mb-1">Users</p>
                <h2 class="font-syne font-bold text-xl">Edit User</h2>
            </div>
            <button onclick="closeModal('editModal')" class="text-neutral-500 hover:text-white text-xl transition-colors">✕</button>
        </div>

        <form method="POST" action="/admin/users/update" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-y-auto">
            <div class="px-8 py-6 space-y-5 flex-1">

                <?php if (!empty($editErrors)): ?>
                    <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs px-4 py-3 tracking-wide space-y-1">
                        <?php foreach ($editErrors as $e): ?>
                            <div>— <?= htmlspecialchars($e) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <input type="hidden" name="id" id="edit_id">

                <!-- Current avatar preview -->
                <div id="edit_avatar_wrap" class="hidden items-center gap-3">
                    <img id="edit_avatar" src="" class="w-10 h-10 rounded-full object-cover border border-neutral-700" alt="">
                    <span class="text-neutral-500 text-xs">Current photo</span>
                </div>

                <!-- Name -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Name <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="edit_name"
                           value="<?= htmlspecialchars($editOld['name'] ?? '') ?>"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Email -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" id="edit_email"
                           value="<?= htmlspecialchars($editOld['email'] ?? '') ?>"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Password -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">New Password</label>
                    <input type="password" name="password"
                           placeholder="Leave blank to keep current"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Role + Room -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="<?= $fieldCls ?>">
                        <label class="<?= $labelCls ?>">Role</label>
                        <select name="role" id="edit_role" class="<?= $inputCls ?>">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="<?= $fieldCls ?>">
                        <label class="<?= $labelCls ?>">Room</label>
                        <select name="room_id" id="edit_room_id" class="<?= $inputCls ?>">
                            <?= roomOptions($rooms, $editOld['room_id'] ?? null) ?>
                        </select>
                    </div>
                </div>

                <!-- Extension -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Extension</label>
                    <input type="text" name="extension" id="edit_extension"
                           value="<?= htmlspecialchars($editOld['extension'] ?? '') ?>"
                           placeholder="e.g. 101"
                           class="<?= $inputCls ?>">
                </div>

                <!-- Image -->
                <div class="<?= $fieldCls ?>">
                    <label class="<?= $labelCls ?>">Replace Profile Image</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                           class="text-sm text-neutral-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-neutral-800 file:text-neutral-300 file:text-xs file:font-syne file:uppercase file:tracking-widest hover:file:bg-neutral-700 cursor-pointer">
                    <p class="text-neutral-600 text-xs mt-1">Leave empty to keep current image</p>
                </div>

                <!-- is_active -->
                <div class="flex items-center gap-3 pt-1">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                           class="w-4 h-4 accent-lime-300 cursor-pointer">
                    <label for="edit_is_active" class="text-neutral-400 text-sm cursor-pointer">Active account</label>
                </div>

            </div>

            <div class="px-8 py-5 border-t border-neutral-800 flex gap-3">
                <button type="submit"
                    class="flex-1 bg-lime-300 text-neutral-950 font-syne font-bold text-xs tracking-widest uppercase py-3 hover:bg-lime-200 active:scale-[.99] transition-all">
                    Save Changes
                </button>
                <button type="button" onclick="closeModal('editModal')"
                    class="px-5 border border-neutral-700 text-neutral-400 font-syne font-bold text-xs tracking-widest uppercase hover:border-neutral-500 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════════════════
     DELETE MODAL
════════════════════════════════════════════ -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeModal('deleteModal')"></div>

    <div class="relative z-10 bg-neutral-900 border border-neutral-800 w-full max-w-sm mx-4 p-8 scale-95 opacity-0 transition-all duration-200" id="deletePanel">
        <div class="absolute top-0 left-0 right-0 h-0.5 bg-red-500"></div>

        <p class="text-red-400 text-xs tracking-widest uppercase mb-2">Danger Zone</p>
        <h2 class="font-syne font-bold text-xl mb-3">Delete User</h2>
        <p class="text-neutral-400 text-sm mb-6">
            You are about to delete <span id="delete_name" class="text-white font-medium"></span>.
            This action <span class="text-red-400">cannot be undone</span>.
        </p>

        <form method="POST" action="/admin/users/delete" class="flex gap-3">
            <input type="hidden" name="id" id="delete_id">
            <button type="submit"
                class="flex-1 bg-red-500 text-white font-syne font-bold text-xs tracking-widest uppercase py-3 hover:bg-red-400 active:scale-[.99] transition-all">
                Yes, Delete
            </button>
            <button type="button" onclick="closeModal('deleteModal')"
                class="px-5 border border-neutral-700 text-neutral-400 font-syne font-bold text-xs tracking-widest uppercase hover:border-neutral-500 transition-all">
                Cancel
            </button>
        </form>
    </div>
</div>


<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const panel = document.getElementById(id.replace('Modal', 'Panel'));
        if (panel?.classList.contains('translate-x-full')) {
            requestAnimationFrame(() => panel.classList.remove('translate-x-full'));
        }
        if (id === 'deleteModal') {
            requestAnimationFrame(() => {
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            });
        }
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const panel = document.getElementById(id.replace('Modal', 'Panel'));

        if (panel?.classList.contains('transition-transform')) {
            panel.classList.add('translate-x-full');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        } else if (id === 'deleteModal') {
            panel.classList.add('scale-95', 'opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
        }
        document.body.style.overflow = '';
    }

    function openEditModal(user) {
        document.getElementById('edit_id').value        = user.id;
        document.getElementById('edit_name').value      = user.name;
        document.getElementById('edit_email').value     = user.email;
        document.getElementById('edit_extension').value = user.extension ?? '';
        document.getElementById('edit_is_active').checked = user.is_active == 1;

        const roleSelect = document.getElementById('edit_role');
        roleSelect.value = user.role ?? 'user';

        const roomSelect = document.getElementById('edit_room_id');
        roomSelect.value = user.room_id ?? '';

        // Show avatar preview if user has an image
        const avatarWrap = document.getElementById('edit_avatar_wrap');
        const avatarImg  = document.getElementById('edit_avatar');
        if (user.image) {
            avatarImg.src = '/public/uploads/users/' + user.image;
            avatarWrap.classList.remove('hidden');
            avatarWrap.classList.add('flex');
        } else {
            avatarWrap.classList.add('hidden');
            avatarWrap.classList.remove('flex');
        }

        openModal('editModal');
    }

    function openDeleteModal(id, name) {
        document.getElementById('delete_id').value = id;
        document.getElementById('delete_name').textContent = name;
        openModal('deleteModal');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            ['createModal', 'editModal', 'deleteModal'].forEach(id => {
                if (!document.getElementById(id).classList.contains('hidden')) closeModal(id);
            });
        }
    });

    <?php if (!empty($createErrors)): ?>
        openModal('createModal');
    <?php endif; ?>

    <?php if (!empty($editErrors) && !empty($editOld)): ?>
        openEditModal(<?= json_encode($editOld) ?>);
    <?php endif; ?>
</script>

<?php
include "layout/footer.php" ;
?>
