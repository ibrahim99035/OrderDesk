<?php
namespace App\request\catgories ;

use App\core\Request;

class catgoryRequest extends Request{
    public function rules()
    {
        return [
            'name' => 'required|string',
        ];
    }
}