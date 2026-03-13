<?php
include "layout/header.php" ;
include "components/categories/main.php" ;
include "components/categories/edit.php" ;
include "components/categories/add.php" ;
include "components/categories/delete.php" ;

 

?>




<script>

function openAddModal(){
document.getElementById("addModal").classList.remove("hidden")
}

function closeAddModal(){
document.getElementById("addModal").classList.add("hidden")
}


function openEditModal(id,name){

document.getElementById("editModal").classList.remove("hidden")

document.getElementById("edit").action="/admin/categories/update/" + id ;
document.getElementById("edit_name").value=name ;

}

function closeEditModal(){
document.getElementById("editModal").classList.add("hidden")
}


function openDeleteModal(id){

document.getElementById("deleteModal").classList.remove("hidden")

document.getElementById("deleteform").action="/admin/categories/delete/" + id

}

function closeDeleteModal(){
document.getElementById("deleteModal").classList.add("hidden")
}

</script>

<?php
include "layout/footer.php" ;
?>