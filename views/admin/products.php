<?php

include "layout/header.php" ;
include "components/proudects/main.php" ;
include "components/proudects/addModel.php" ;



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

    function editProduct(id) {
        document.getElementById('productModalTitle').textContent = 'تعديل المنتج';
        document.getElementById('productModal').classList.remove('hidden');
    }

    function deleteProduct(id) {
        if(confirm('هل تأكد من حذف هذا المنتج؟')) {
            alert('تم حذف المنتج بنجاح');
        }
    }
</script>
<?php
include "layout/footer.php" ;
?>
