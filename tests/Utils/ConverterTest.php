<?php

namespace Ekimik\ApiUtils\Tests\Utils;

use Ekimik\ApiUtils\Utils\Converter;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class ConverterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Converter::convertPresenterNameToResource
     */
    public function testConvertPresenterNameToResource() {
        $this->assertEquals('Foo', Converter::convertPresenterNameToResource('FooPresenter'));
        $this->assertEquals('', Converter::convertPresenterNameToResource(''));
        $this->assertEquals('FooBar', Converter::convertPresenterNameToResource('FooBarBazBarBar'));
    }

}
