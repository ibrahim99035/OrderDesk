<div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">

    <div class="max-w-5xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold
        text-gray-700 dark:text-gray-300">
        Categories | <?= count($catgroies) ?>
        </h2>

        <button
        onclick="openAddModal()"
        class="px-4 py-2 rounded-lg
        bg-blue-600 hover:bg-blue-700
        text-white text-sm">

        Add Category

        </button>

</div>

        


        <!-- table -->
        <div class="overflow-hidden rounded-xl shadow
        border border-gray-200 dark:border-gray-700
        bg-white dark:bg-gray-800">

            <table class="w-full text-sm">

                <!-- head -->
                <thead class="bg-gray-100 dark:bg-gray-700
                text-gray-700 dark:text-gray-300">

                    <tr>

                        <th class="text-left px-4 py-3">ID</th>

                        <th class="text-left px-4 py-3">
                            Category Name
                        </th>

                        <th class="text-left px-4 py-3">
                            Products Count
                        </th>

                        <th class="text-left px-4 py-3">
                            Actions
                        </th>

                    </tr>

                </thead>


                <!-- body -->
                <tbody class="text-gray-700 dark:text-gray-300">

                   <?php foreach($catgroies as $catgry) : ?>
                    <tr class="border-t border-gray-200 dark:border-gray-700">

                        <td class="px-4 py-3">  <?=  $catgry["id"] ?></td>

                        <td class="px-4 py-3">
                            <?=  $catgry["name"] ?>
                        </td>

                        <td class="px-4 py-3">
                            <?php $count =  $obj->proudectCount($catgry["id"]) ; echo $count ?>
                        </td>

                        <td class="px-4 py-3 flex gap-2">

                            <button
                            onclick="openEditModal(
                            <?= $catgry['id'] ?>,
                            '<?= $catgry['name'] ?>'
                            )"
                            class="px-3 py-1 rounded-md
                            bg-blue-600 text-white text-xs">

                            Update

                            </button>

                        <?php
                        if ($count <= 0) {

                        echo '<button
                        onclick="openDeleteModal('.$catgry['id'].')"
                        class="px-3 py-1 rounded-md
                        bg-red-600 text-white text-xs">

                        Delete

                        </button>';

                        }
                        ?>
                         

                        </td>

                    </tr>
                    <?php endforeach ?>




                </tbody>

            </table>

        </div>

    </div>

</div>