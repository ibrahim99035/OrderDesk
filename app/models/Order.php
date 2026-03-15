<?php
namespace App\models ;

use App\core\Model;

    class Order extends Model {


        public array $items = [];

        public function __construct() {
            parent::__construct("orders") ;
        }
    }
?>