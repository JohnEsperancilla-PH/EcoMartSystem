<?php
class Validator
{
    public static function validate(array $data, array $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            if (isset($rule['required']) && $rule['required'] && empty($data[$field])) {
                $errors[$field][] = "The {$field} field is required";
            }

            if (isset($rule['email']) && $rule['email'] && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field][] = "Invalid email format";
            }

            if (isset($rule['pattern']) && !preg_match($rule['pattern'], $data[$field])) {
                $errors[$field][] = "The {$field} format is invalid";
            }
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        return true;
    }
}
