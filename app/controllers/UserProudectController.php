<?php 
namespace App\controllers ;

use App\Core\View;
use App\models\Category;
use App\models\Product;

class UserProudectController{
    public function index(){
         
    $pro = new Product() ;
    $catgory = new Category ;
     $proudects = $pro->all() ;
     $catgroies =  $catgory->all() ;
     View::make("user.product" , ["proudects" => $proudects,"catgroies" => $catgroies , "current" => 'proudects' ]);
    }
}