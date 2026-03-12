<?php
namespace App\models;
use App\core\Model;

class Room extends Model {
    public function __construct() {
        parent::__construct("rooms");
    }
}
?>