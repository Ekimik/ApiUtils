<?php

namespace Ekimik\ApiUtils\Resource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Response {

    protected $response = [];

    public function __construct(array $data = []) {
	$this->init();
	$this->fillResponse($data);
    }

    public function addError(array $error) {
	$this->response['errors'][] = $error;
    }

    public function addErrors(array $errors) {
	foreach ($errors as $error) {
	    $this->addError($error);
	}
    }

    public function setErrors(array $errors) {
	$this->response['errors'] = $errors;
    }

    public function getErrors(): array {
	return $this->response['errors'];
    }

    public function setData(array $data) {
	$this->response['responseData'] = $data;
    }

    public function getData(): array {
	return $this->response['responseData'];
    }

    public function getResponse(): IResource {
	return $this->response;
    }

    protected function init() {
	$this->response['errors'] = [];
	$this->response['responseData'] = [];
    }

    protected function fillResponse(array $data, $index = NULL) {
	foreach ($data as $key => $value) {
	    if ($key === 'errors') {
		$this->addErrors($value);
		continue;
	    }

	    if (is_int($key)) {
		$this->fillResponse($value, $key);
	    } else {
		if (!is_null($index)) {
		    $this->response['responseData'][$index][$key] = $value;
		} else {
		    $this->response['responseData'][$key] = $value;
		}
	    }
	}
    }

}
