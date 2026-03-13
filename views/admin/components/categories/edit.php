<div id="editModal"
class="hidden fixed inset-0 z-50
flex items-center justify-center
bg-black/50">

<div class="bg-white dark:bg-gray-800
rounded-xl p-6 w-96">

<h3 class="text-lg font-bold mb-4
text-gray-700 dark:text-gray-300">

Update Category

</h3>

<form method="post" id="edit">


<input
id="edit_name"
name="name"
class="w-full p-2 rounded
border border-gray-300
dark:bg-gray-700 dark:text-white dark:border-gray-600
mb-4">

<div class="flex justify-end gap-2">

<button type="button"
onclick="closeEditModal()"
class="px-3 py-1 bg-gray-400 text-white rounded">

Cancel

</button>

<button
class="px-3 py-1 bg-blue-600 text-white rounded">

Update

</button>

</div>

</form>

</div>
</div>