<?php

namespace App\core;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $errors = [];
    protected array $clean = [];

    protected array $messages = [

        'required' => 'The :field field is required.',
        'string' => 'The :field must be a string.',
        'int' => 'The :field must be an integer.',
        'float' => 'The :field must be a number.',
        'image' => 'The :field must be an image.',

    ];


    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }



    public function validate()
    {

        foreach ($this->rules as $field => $ruleString) {

            $rules = explode('|', $ruleString);

            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {

                if ($rule === 'required') {

                    if ($value === null || $value === '') {
                        $this->addError($field, 'required');
                    }

                }


                if ($rule === 'string') {

                    if ($value && !is_string($value)) {
                        $this->addError($field, 'string');
                    }

                }


                if ($rule === 'int') {

                    if ($value && !filter_var($value, FILTER_VALIDATE_INT)) {
                        $this->addError($field, 'int');
                    }

                }


                if ($rule === 'float') {

                    if ($value && !filter_var($value, FILTER_VALIDATE_FLOAT)) {
                        $this->addError($field, 'float');
                    }

                }


                if ($rule === 'image') {

                    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== 0) {
                        $this->addError($field, 'image');
                    }

                }

            }


            if (!isset($this->errors[$field])) {

                if (is_string($value)) {
                    $value = htmlspecialchars(strip_tags(trim($value)));
                }

                $this->clean[$field] = $value;
            }
        }

        return empty($this->errors);
    }



    protected function addError($field, $rule)
    {
        $message = $this->messages[$rule] ?? 'Invalid';

        $message = str_replace(':field', $field, $message);

        $this->errors[$field][] = $message;
    }



    public function errors()
    {
        return $this->errors;
    }



    public function data()
    {
        return $this->clean;
    }
}