<?php

namespace Core;

use Exception;

class ValidationException extends Exception
{
    private $errors;

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
