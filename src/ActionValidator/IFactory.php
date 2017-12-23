<?php

namespace Ekimik\ApiUtils\ActionValidator;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
interface IFactory {

    public function create(string $validatorCode): IActionValidator;

}
