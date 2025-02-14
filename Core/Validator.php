<?php

namespace Core;

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule => $value) {
                if (!isset($data[$field]) && $rule !== 'required') {
                    continue;
                }

                switch ($rule) {
                    case 'required':
                        if ($value && (!isset($data[$field]) || empty($data[$field]))) {
                            $this->addError($field, 'This field is required');
                        }
                        break;

                    case 'email':
                        if ($value && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, 'Invalid email format');
                        }
                        break;

                    case 'min':
                        if (strlen($data[$field]) < $value) {
                            $this->addError($field, "Minimum length is {$value} characters");
                        }
                        break;

                    case 'max':
                        if (strlen($data[$field]) > $value) {
                            $this->addError($field, "Maximum length is {$value} characters");
                        }
                        break;

                    case 'pattern':
                        if (!preg_match($value, $data[$field])) {
                            $this->addError($field, 'Invalid format');
                        }
                        break;

                    case 'in':
                        if (is_array($value) && !in_array($data[$field], $value)) {
                            $this->addError($field, 'Invalid value selected');
                        }
                        break;

                    case 'date':
                        if ($value && !strtotime($data[$field])) {
                            $this->addError($field, 'Invalid date format');
                        }
                        break;
                }
            }
        }

        if (count($this->errors) > 0) {
            throw new ValidationException($this->errors);
        }

        return true;
    }

    private function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

class ValidationException extends \Exception
{
    protected $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Validation failed');
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
