<?php
namespace App\controllers ;
use App\core\View ;
use App\models\Product ;
use App\request\proudects\insert ;
use App\core\FileManager ;
use App\models\Category;

class ProductController{


   public function index(){

    
    $pro = new Product() ;
    $catgory = new Category ;
     $proudects = $pro->all() ;
     $catgroies =  $catgory->all() ;
     View::make("admin.products" , ["proudects" => $proudects,"catgroies" => $catgroies , "current" => 'proudects' ]);


   }

   public function store(){


      $request = new insert ;
      $pro = new Product() ;

      if(!$request->validate()){

         $_SESSION["errorsCreate"] = $request->errors();
         header("Location: /products");

      }else{

            $image = $_FILES["image"] ;
            var_dump($image) ;
            $fileManger = new FileManager() ;
            $fileManger->types([
               "image/jpg",
               "image/jpeg",
               "image/png",
               "image/webp",
            ]);
            $path = $fileManger->upload($image) ;
            if(!$path){
               $_SESSION["errorsCreate"] = ["image" =>["image faild not valid only allwo jpg jpeg png webp "]];
               header("Location: /products");
            }
            $data = $request->data() ;

            $result = $pro->insert(["name" , "description", "price" , "category_id"  ,"image" ,"is_available"] , 
                           [$data["name"] ,$data["description"], $data["price"] , $data["category_id"], $path , $data["is_available"]]) ;
            if($result){
               $_SESSION["success"] = "Data added sucsuful";
               header("Location: /products");
            }else{
               $_SESSION["serverEror"] = ["image" =>["image faild not valid only allwo jpg jpeg png webp "]];
               header("Location: /products");
            }
      }
   }

   public function delete($id){


       $pro = new Product() ;
      $proud = $pro->find($id) ;
      if($proud){

      }else{

      }
   }
}