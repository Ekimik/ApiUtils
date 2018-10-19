<?php

namespace Ekimik\ApiUtils\Exception;

class ApiException extends \Exception {

    protected $errors = [];

    public function getErrors(): array {
        return $this->errors;
    }

    public function setErrors(array $errors) {
        $this->errors = $errors;
    }

}
