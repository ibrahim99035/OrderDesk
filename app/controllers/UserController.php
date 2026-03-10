<?php
namespace App\controllers ;
use App\core\View ;
use App\models\User ;
class UserController{
    public function index($id){    
        $userModel = new User ;    
        $users = $userModel->all();

        View::make("user.index",[
            "users" => $users
        ]);
    }


    public function store(){
        
    }
}