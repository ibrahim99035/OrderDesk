<?php
namespace App\models ;

use App\core\Model;

class Product extends Model {


        public function __construct() {
            parent::__construct("products") ;
        }
    }
?>