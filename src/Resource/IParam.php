<?php

namespace Ekimik\ApiUtils\Resource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
interface IParam {

    public function getParamDefinition(): array;
    public function toArray(): array;

}
