<?php

namespace App\Exception;

class ModelValidationException extends \Exception
{
    private $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Validation error(s). ' . json_encode($this->errors));
    }

    public function toArray(): array
    {
        return [
            'message' => 'Validation error(s)',
            'errors'  => $this->errors,
        ];
    }
}
