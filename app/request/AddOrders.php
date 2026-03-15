<?php
namespace App\request ;

use App\core\Request;

class AddOrders extends Request{
    
    public function rules()
    {
        return [

            "cart" => "required|string",

            "room_id" => "required|int",

        ];
    }
}