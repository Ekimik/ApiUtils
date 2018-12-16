<?php

namespace Ekimik\ApiUtils\Exception;

class ApiException extends \Exception {

    protected $errors = [];

    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * @param array $errors array of arrays with following minimal structure
     * [
     *      ["message" => "Some message"],
     *      ...
     * ]
     */
    public function setErrors(array $errors) {
        $this->errors = $errors;
    }

}
