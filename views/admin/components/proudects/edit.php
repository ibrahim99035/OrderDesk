<?php
$errors = $_SESSION["errorsupdate"] ?? [];
unset($_SESSION["errorsupdate"]);
$old = $_SESSION["old"] ?? [];
unset($_SESSION["old"]);
?>

<div id="edit"
class="<?=  count($errors) > 0 ? '' : 'hidden' ?> fixed inset-0 z-50 flex items-center justify-center
bg-black/60 backdrop-blur-sm p-4">

    <div
    class="w-full max-w-2xl rounded-2xl
    bg-white dark:bg-gray-900
    border border-gray-200 dark:border-gray-700
    shadow-2xl">

        <!-- header -->
        <div
        class="flex justify-between items-center
        px-6 py-4 border-b
        border-gray-200 dark:border-gray-700">

            <h3 id="editTitle"
            class="text-lg font-bold text-gray-800 dark:text-white">
                🛒 edit Product
            </h3>

            <button
            type="button"
            onclick="closeedit()"
            class="text-gray-500 hover:text-red-500
            text-xl font-bold">
                ✕
            </button>

        </div>



        <!-- form -->
        <form
        id="editForm"
        method="POST"
        action="/products/update/"
        enctype="multipart/form-data"
        class="p-6 space-y-4">



            <!-- name + category -->
            <div class="grid grid-cols-2 gap-4">

                <div>

                    <!-- name -->
                    <div>
                        <label
                        class="block text-sm font-medium
                        text-gray-700 dark:text-gray-300 mb-1">
                            Product Name
                        </label>

                        <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?= isset($old["name"]) ? $old["name"] : "" ?>"
                        required
                        class="w-full px-3 py-2 rounded-lg
                        border border-gray-300
                        dark:border-gray-600
                        bg-white dark:bg-gray-800
                        text-gray-900 dark:text-white
                        focus:ring-2 focus:ring-blue-500
                        outline-none">

                        <div class="text-red-400 <?= isset($errors["name"]) ? "" : "hidden" ?>">
                            <?= isset($errors["name"]) ? $errors["name"][0] : "" ?>
                        </div>
                    </div>

                </div>



                <div>

                    <!-- category -->
                    <div>

                        <label
                        class="block text-sm font-medium
                        text-gray-700 dark:text-gray-300 mb-1">
                            Category
                        </label>

                        <select
                        name="category_id"
                        id="catgory"
                        class="w-full px-3 py-2 rounded-lg
                        border border-gray-300
                        dark:border-gray-600
                        bg-white dark:bg-gray-800
                        text-gray-900 dark:text-white
                        focus:ring-2 focus:ring-blue-500
                        outline-none">

                            <option value="">Select Category</option>

                            <?php foreach ($catgroies as $cat): ?>

                                <option   <?= isset($old["category_id"]) && $old["category_id"] == $cat["id"]  ? "selected" : "" ?> value="<?= $cat["id"] ?>">
                                    <?= htmlspecialchars($cat["name"]) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                        <div class="text-red-400 <?= isset($errors["category_id"]) ? "" : "hidden" ?>">
                            <?= $errors["category_id"][0] ?? "" ?>
                        </div>

                    </div>

                </div>

            </div>



            <!-- desc -->
            <div>
                <label
                class="block text-sm font-medium
                text-gray-700 dark:text-gray-300 mb-1">
                    Description
                </label>

                <textarea
                id="disc"
                name="description"
                rows="3"
                required
                value="<?= isset($old["description"]) ? $old["description"] : "" ?>"
                class="w-full px-3 py-2 rounded-lg
                border border-gray-300
                dark:border-gray-600
                bg-white dark:bg-gray-800
                text-gray-900 dark:text-white
                focus:ring-2 focus:ring-blue-500
                outline-none"></textarea>

                <div class="text-red-400 <?= isset($errors["description"]) ? "" : "hidden" ?>">
                    <?= isset($errors["description"]) ? $errors["description"][0] : "" ?>
                </div>

            </div>



            <!-- price + image -->
            <div class="grid grid-cols-2 gap-4">

                <div>

                    <!-- price -->
                    <div>

                        <label
                        class="block text-sm font-medium
                        text-gray-700 dark:text-gray-300 mb-1">
                            Price
                        </label>

                        <input
                        type="number"
                        id="price"
                        name="price"
                        value="<?= isset($old["price"]) ? $old["price"] : "" ?>"
                        required              
                        class="w-full px-3 py-2 rounded-lg border
                        border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-800
                        text-gray-900 dark:text-white">

                        <div class="text-red-400 <?= isset($errors["price"]) ? "" : "hidden" ?>">
                            <?= isset($errors["price"]) ? $errors["price"][0] : "" ?>
                        </div>

                    </div>

                </div>



                <div>

                    <!-- image -->
                    <div>

                        <label
                        class="block text-sm font-medium
                        text-gray-700 dark:text-gray-300 mb-1">
                            Product Image
                        </label>

                        <input
                        type="file"
                        id="img"
                        name="image"
                        accept="image/*"
                        class="w-full text-sm
                        text-gray-700 dark:text-gray-300
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-lg
                        file:border-0
                        file:text-sm
                        file:font-semibold
                        file:bg-blue-600
                        file:text-white
                        hover:file:bg-blue-700">

                        <div class="text-red-400 <?= isset($errors["image"]) ? "" : "hidden" ?>">
                            <?= isset($errors["image"]) ? $errors["image"][0] : "" ?>
                        </div>

                    </div>

                </div>

            </div>



            <!-- is_available -->
            <div>

                <label
                class="block text-sm font-medium
                text-gray-700 dark:text-gray-300 mb-1">
                    Available
                </label>

                <select
                name="is_available"
                class="w-full px-3 py-2 rounded-lg
                border border-gray-300
                dark:border-gray-600
                bg-white dark:bg-gray-800
                text-gray-900 dark:text-white
                focus:ring-2 focus:ring-blue-500
                outline-none" id="valible">

                    <option <?= isset($old["is_available"]) && $old["is_available"] == 0  ? "selected" : "" ?>  value="1">Available</option>
                    <option <?= isset($old["is_available"]) && $old["is_available"] == 1 ? "selected" : "" ?>  value="0">Not Available</option>

                </select>

                <div class="text-red-400 <?= isset($errors["is_available"]) ? "" : "hidden" ?>">
                    <?= isset($errors["is_available"]) ? $errors["is_available"][0] : "" ?>
                </div>

            </div>



            <!-- buttons -->
            <div class="flex gap-3 pt-2">

                <button
                type="submit"
                class="flex-1 bg-blue-600
                hover:bg-blue-700
                text-white font-semibold
                py-2 rounded-lg shadow">
                    💾 Save
                </button>

                <button
                type="button"
                onclick="closeedit()"
                class="flex-1 bg-gray-300
                dark:bg-gray-700
                text-gray-900 dark:text-white
                font-semibold py-2 rounded-lg">
                    Cancel
                </button>

            </div>

        </form>

    </div>

</div>