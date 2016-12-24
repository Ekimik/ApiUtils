<?php

namespace Ekimik\ApiUtils\Resource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
interface IDescription {

    public function setResourceDescription(string $desc);
    public function addResourceAction(IAction $action);
    public function getInfoAboutResource() : array;

}
