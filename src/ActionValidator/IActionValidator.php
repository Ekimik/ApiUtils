<?php

namespace Ekimik\ApiUtils\ActionValidator;

use \Ekimik\ApiUtils\Resource\Request;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
interface IActionValidator {

    public function validate(Request $request);
    public function getErrors(): array;
    public function isValid(): bool;

}
