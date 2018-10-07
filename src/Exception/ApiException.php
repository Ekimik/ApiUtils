<?php

namespace Ekimik\AuthApi\Exceptions;

class ApiException extends \Exception {

    protected $errors = [];

    public function getErrors(): array {
        return $this->errors;
    }

    public function setErrors(array $errors) {
        $this->errors = $errors;
    }

}
