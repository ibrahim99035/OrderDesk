<div class="grid grid-cols-4 gap-4 p-6">

<?php foreach($proudects as $pro): ?>

<div class="glass p-3 rounded">

<img src="<?= $pro['image'] ?>" class="w-full h-40 object-cover">

<h3><?= $pro["name"] ?></h3>

<p><?= $pro["price"] ?> $</p>

<button onclick="addItem(<?= $pro['id'] ?>)">
Add
</button>

</div>

<?php endforeach ?>

</div>