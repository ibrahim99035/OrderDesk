<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">

        <h2
        class="text-3xl font-bold
        text-gray-900 dark:text-white
        flex items-center gap-2">

            🍔 Products Manager

        </h2>


        <button
        onclick="openAddProductModal()"
        class="px-5 py-2.5
        rounded-lg
        bg-blue-600
        hover:bg-blue-700
        text-white
        font-semibold
        shadow
        transition
        hover:scale-105">

            + New Product

        </button>

    </div>



    <!-- Grid -->
    <div
    class="grid grid-cols-1
    sm:grid-cols-2
    lg:grid-cols-3
    gap-6">



    <?php foreach($proudects as $proudect) : ?>
        <div
        class="group
        bg-white dark:bg-gray-800
        rounded-2xl
        shadow-md
        hover:shadow-2xl
        transition
        overflow-hidden
        border border-gray-200 dark:border-gray-700
        hover:-translate-y-1">

            <div
                class="h-40
                overflow-hidden
                bg-gray-100 dark:bg-gray-700">

                <img
                src="/<?=  $proudect["image"] ?>"
                alt=""
                class="w-full h-full object-cover
                group-hover:scale-110
                transition duration-300">

            </div>

            <!-- body -->
            <div class="p-5">

                <h3
                class="text-lg font-bold
                text-gray-900 dark:text-white
                mb-1">

                    <?= $proudect["name"] ?>
                </h3>


                <p
                class="text-sm
                text-gray-600 dark:text-gray-400
                mb-3">

                   <?= $proudect["description"] ?>

                </p>



                <!-- price -->
                <div
                class="flex justify-between items-center mb-4">

                    <span
                    class="text-2xl font-bold
                    text-blue-600 dark:text-blue-400">

                        <?= $proudect["price"] ?> $

                    </span>



                </div>



                <!-- buttons -->
                <div class="flex gap-2">

                    <button
                    onclick="editProduct(1)"
                    class="flex-1
                    py-2
                    rounded-lg
                    bg-blue-600
                    hover:bg-blue-700
                    text-white
                    text-sm
                    transition
                    hover:scale-105">

                        Edit

                    </button>



                    <button
                    onclick="deleteProduct(1)"
                    class="flex-1
                    py-2
                    rounded-lg
                    bg-red-600
                    hover:bg-red-700
                    text-white
                    text-sm
                    transition
                    hover:scale-105">

                        Delete

                    </button>

                </div>

            </div>

        </div>
        <?php endforeach  ?>

    </div>

</div>