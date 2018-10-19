<?php

namespace Ekimik\ApiUtils\Exception;

class ApiValidationException extends ApiException {

    public function __construct(string $rootMessage, array $errors) {
        parent::__construct($rootMessage, 422);
        $this->setErrors($errors);
    }

}
