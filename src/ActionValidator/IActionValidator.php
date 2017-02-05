<?php

namespace Ekimik\ApiUtils\ActionValidator;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
interface IActionValidator {

    public function validate(array &$inputData);
    public function getErrors(): array;
    public function isValid(): bool;

}
