<?php
namespace App\controllers ;

use App\core\View;
use App\models\Category;
use App\request\catgories\catgoryRequest;

class categoryController{
    public function index(){
        $catgory = new Category ;
        $catgroies =  $catgory->all() ;
        View::make("admin.categories" , ["catgroies" => $catgroies , "current" => 'categories' , "obj" => $catgory ]);
    }

    public function store(){
        $request = new catgoryRequest ;
        if(!$request->validate()){
            $_SESSION["error"] = $request->errors();
            header("Location: /admin/categories");
        }else{
            $catgory = new Category ;
            $result = $catgory->insert(["name"] , [$request->data()["name"]]) ;
            if($result){
               $_SESSION["success"] = "Data added sucsuful";
               header("Location: /admin/categories/");
            }else{
               $_SESSION["error"] = "filed to add data";
               header("Location: /admin/categories/");
            }

        }
    }

    public function delete($id){
        $catgory = new Category ;
        if($catgory->find($id) && $catgory->proudectCount($id) <= 0){
            $ok = $catgory->where("id=$id")->delete() ;
            if($ok){
                $_SESSION["success"] = "Data added sucsuful";
               header("Location: /admin/categories/");
               exit ;
            }
        }
        $_SESSION["error"] = "filed to add data";
        header("Location: /admin/categories/");
    }


       public function update($id){
        $request = new catgoryRequest ;
        if(!$request->validate()){
            $_SESSION["error"] = $request->errors();
            header("Location: /admin/categories");
        }else{
            $catgory = new Category ;
            $result = $catgory->where("id=$id")->update(["name"] , [$request->data()["name"]]) ;
            if($result){
               $_SESSION["success"] = "Data added sucsuful";
               header("Location: /admin/categories/");
            }else{
               $_SESSION["error"] = "filed to add data";
               header("Location: /admin/categories/");
            }

        }
    }
}