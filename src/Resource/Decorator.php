<?php

namespace Ekimik\ApiUtils\Resource;

use \Nette\Object,
    \Drahak\Restful\IResource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Decorator extends Object {

    /** @var IResource */
    protected $resource;

    public function __construct(IResource $resource, array $dataForResource = []) {
        $this->resource = $resource;
        $this->init();
        $this->fillResource($dataForResource);
    }

    public function addError(array $error) {
        $this->resource->errors[] = $error;
    }

    public function addErrors(array $errors) {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    public function setErrors(array $errors) {
        $this->resource->errors = $errors;
    }

    public function getErrors() : array {
        return $this->resource->errors;
    }

    public function setData(array $data) {
        $this->resource->responseData = $data;
    }

    public function getData() : array {
        return $this->resource->responseData;
    }

    public function getResource() : IResource {
        return $this->resource;
    }

    protected function init() {
        $this->resource->errors = [];
        $this->resource->responseData = [];
    }

    protected function fillResource(array $data, $index = NULL) {
        foreach ($data as $key => $value) {
            if ($key === 'errors') {
                $this->addErrors($value);
                continue;
            }

            if (is_int($key)) {
                $this->fillResource($value, $key);
            } else {
                if (!is_null($index)) {
                    $this->resource->responseData[$index][$key] = $value;
                } else {
                    $this->resource->responseData[$key] = $value;
                }
            }
        }
    }

}
