<div id="deleteModal"
class="hidden fixed inset-0 z-50
flex items-center justify-center
bg-black/50">

<div class="bg-white dark:bg-gray-800
rounded-xl p-6 w-96">

<h3 class="text-lg font-bold mb-4
text-red-500">

Delete Category ?

</h3>

<form id="deleteform" method="post" action="admin/categories/delete/">



<div class="flex justify-end gap-2">

<button type="button"
onclick="closeDeleteModal()"
class="px-3 py-1 bg-gray-400 text-white rounded">

Cancel

</button>

<button
class="px-3 py-1 bg-red-600 text-white rounded">

Delete

</button>

</div>

</form>

</div>
</div>