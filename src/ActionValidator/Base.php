<?php

namespace Ekimik\ApiUtils\ActionValidator;

use \Ekimik\ApiDesc\Param\Request as RequestParam;
use \Ekimik\ApiUtils\Resource\Request;
use \Ekimik\Validators\ValidatorFactory;
use \Ekimik\Validators\DataType;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package ClubManager\AuthApi
 */
class Base implements IActionValidator {

    protected $errors = [];

    /** @var ValidatorFactory */
    protected $vf;

    /** @var Request */
    private $request;

    public function __construct(ValidatorFactory $vf) {
        $this->vf = $vf;

        $this->init();
    }

    protected function init() {

    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function validate(Request $r) {
        $this->request = $r;
        $this->clearErrors();
        $inputData = $r->getInputData();

        foreach ($inputData as $field => $value) {
            $this->validateField($field, $value);
        }
    }

    protected function validateField(string $field, $value, $fieldPath = null) {
        $path = ltrim($fieldPath . RequestParam::NAME_PATH_SEPARATOR . $field, RequestParam::NAME_PATH_SEPARATOR);
        $param = $this->request->getAction()->getParam($path);

        if (empty($param)) {
            $this->addError($field, sprintf("Unknown input field '%s'", $field));
            return;
        }

        if ($param->isRequired() && $this->isValueEmpty($value)) {
            $this->addError($field, sprintf("Field '%s' is required, but is missing or has empty value", $field));
            return;
        }

        // validate type of param by definition in action
        $paramType = $param->getDataType();
        $dtOptions = [
            DataType::OPTION_DATA_TYPE => $paramType,
            DataType::OPTION_REQUIRED => false,
        ];
        if (
            !$this->isValueEmpty($value)
            && !$this->vf->create(ValidatorFactory::VALIDATOR_DATA_TYPE, $value, $dtOptions)->validate()
        ) {
            $this->addError($field, sprintf("Field '%s' should be of type '%s', but '%s' given", $field, $paramType, gettype($value)));
            return;
        }

        if (is_array($value) && $this->validateFieldRecursively($field)) {
            foreach ($value as $key => $v) {
                $this->validateField($key, $v, $path);
            }
        }
    }

    public function isValid(): bool {
        return empty($this->getErrors());
    }

    public function clearErrors() {
        $this->errors = [];
    }

    protected function addError(string $key, string $message) {
        $this->errors[$key] = $message;
    }

    protected function isValueEmpty($value): bool {
        return is_null($value) || $value === '' || $value === [];
    }

    protected function validateFieldRecursively(string $field): bool {
        return true;
    }

}
