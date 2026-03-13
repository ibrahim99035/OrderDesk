<?php
namespace App\models ;

use App\core\Model;

class Category extends Model{
    

        public function __construct() {
            parent::__construct("categories") ;
              
        }

        public function proudectCount($id){
            $pro = new Product ;
            $proudects = $pro->where("category_id=$id")->count() ;
            return   $proudects ;
        }
    }
?>