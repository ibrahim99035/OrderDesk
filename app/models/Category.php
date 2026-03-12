<?php
namespace App\models ;

use App\core\Model;

class Category extends Model{
    

        public function __construct() {
            parent::__construct("categories") ;
              
        }
    }
?>