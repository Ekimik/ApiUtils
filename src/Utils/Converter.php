<?php

namespace Ekimik\ApiUtils\Utils;

use \Nette\Object;
use \Nette\Utils\Strings;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Converter extends Object {

    /**
     * Simply cut off last nine chars from string, asumes this last chars is "Presenter"
     * @param string $presenterName
     * @return string
     */
    public static function convertPresenterNameToResource(string $presenterName): string {
	return Strings::substring($presenterName, 0, -9);
    }

}
