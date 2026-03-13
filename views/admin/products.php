<?php

$editData = [] ;
include "layout/header.php" ;
include "components/proudects/main.php" ;
include "components/proudects/addModel.php" ;
include "components/proudects/edit.php" ;


?>





<script>


    function openAddProductModal() {
        document.getElementById('productModalTitle').textContent = "new proudect";
        document.getElementById('productForm').reset();
        document.getElementById('productModal').classList.remove('hidden');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    function editProduct(proudect) {

        document.getElementById('edit').classList.remove('hidden');
        console.log(proudect.name ) ;
        document.getElementById("name").value = proudect.name ?? "ali";
        document.getElementById("price").value = proudect.price ?? "";
        document.getElementById("disc").value = proudect.description ?? "";
        catgory = document.getElementById("catgory") ;
     
      
        catgory.querySelectorAll("option").forEach((e)=>{
            if(e.value == proudect.category_id){
                e.selected = true;
            }
        })
        avilable = document.getElementById("valible")
         avilable.querySelectorAll("option").forEach((e)=>{
            if(e.value == proudect.is_available){
                e.selected = true;
            }
        })

        console.log(proudect.id)
        document.getElementById("editForm").action = "products/update/" + proudect.id ;

    }

    function closeedit() {
        document.getElementById('edit').classList.add('hidden');
    }


    function deleteProduct(id) {
        if(confirm('  are you shore   ')) {
            alert('تم حذف المنتج بنجاح');
        }
    }
</script>
<?php
include "layout/footer.php" ;
?>
