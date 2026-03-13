<?php
namespace App\controllers ;

use App\core\View;

class Index{
    public  function  home(){
       View::make("home") ;
    }
}