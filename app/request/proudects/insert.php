<?php
namespace App\request\proudects ;
use App\core\Request ;
class insert extends Request {
       public function rules()
        {
            return [
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|float',
                "is_available" => "required|int" ,
                "category_id" => "required|int" ,

            ];
        }
}