<?php

namespace Ekimik\ApiUtils\Exception;

use Throwable;

class ApiException extends \Exception {

    protected $errors = [];

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);

        if (!empty($previous)) {
            $e = ['message' => $previous->getMessage()];
            $this->setErrors([$e]);
        }
    }

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
