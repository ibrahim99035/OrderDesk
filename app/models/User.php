<?php
namespace App\models ;
use App\core\Model ;
    class User extends  Model {

        public function __construct() {
            parent::__construct("users");
        }
    }
?>