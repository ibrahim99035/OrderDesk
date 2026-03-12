<?php

namespace App\core;
use App\core\Validator ;
class Request
{
    protected array $errors = [];
    protected array $data = [];

    public function rules()
    {
        return [];
    }

    public function validate()
    {

        $validator = new Validator(

            $_POST,
            $this->rules()

        );

        if (!$validator->validate()) {

            $this->errors = $validator->errors();
            return false;
        }

        $this->data = $validator->data();

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function data()
    {
        return $this->data;
    }
}