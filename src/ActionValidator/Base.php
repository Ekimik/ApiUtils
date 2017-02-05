<?php

namespace Ekimik\ApiUtils\ActionValidator;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package ClubManager\AuthApi
 */
class Base implements IActionValidator {

    const REQUIRED_FIELDS_ALL = 'all';

    protected $errors = [];
    protected $requiredFields = [self::REQUIRED_FIELDS_ALL];
    protected $allowedFields = [];

    /** @var ValidatorFactory */
    protected $valueValidatorFactory;

    protected function init() {

    }

    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * Check if all fields in input data is not empty, override to do some magic
     * @param array $inputData
     */
    public function validate(array &$inputData) {
        $allowedFields = $this->getAllowedInputFields();
        $this->completeInputData($inputData);

        foreach ($inputData as $field => $inputValue) {
            if (!in_array($field, $allowedFields)) {
                $this->addError($field, "Unknown input field '{$field}'");
                continue;
            }

            if ($this->isInputDataFieldReuqired($field) && (is_null($inputValue) || $inputValue === '')) {
                $this->addError($field, "Field '{$field}' is required, but is missing or has empty value");
            }
        }
    }

    public function isValid(): bool {
        return empty($this->getErrors());
    }

    public function clearErrors() {
        $this->errors = [];
    }

    public function addError(string $key, string $message) {
        $this->errors[$key] = $message;
    }

    public function isInputDataFieldReuqired(string $field): bool {
        $requiredFields = $this->getRequiredInputFields();
        return in_array($field, $requiredFields) || $requiredFields[0] === self::REQUIRED_FIELDS_ALL;
    }

    public function getAllowedInputFields(): array {
        return $this->allowedFields;
    }

    public function setAllowedInputFields(array $fields) {
        $this->allowedFields = $fields;
    }

    public function getRequiredInputFields(): array {
        return $this->requiredFields;
    }

    public function setRequiredInputFields(array $requiredFields) {
        $this->requiredFields = $requiredFields;
    }

    protected function completeInputData(array &$input) {
        $allowedFields = $this->getAllowedInputFields();
        foreach ($allowedFields as $field) {
            if (!key_exists($field, $input)) {
                $input[$field] = NULL;
            }
        }
    }

}
