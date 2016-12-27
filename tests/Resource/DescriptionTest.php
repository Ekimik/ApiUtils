<?php

namespace Ekimik\ApiUtils\Tests\Resource;

use \Ekimik\ApiUtils\Resource\Description,
    \Ekimik\ApiUtils\Resource\Action;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class DescriptionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Description::setResourceDescription
     * @covers Description::addResourceAction
     * @covers Description::getInfoAboutResource
     */
    public function testDescription() {
        $desc = new Description('fooResource');

        $descDef = [
            'name' => 'fooResource',
            'description' => null,
            'actions' => []
        ];
        $this->assertEquals($descDef, $desc->getInfoAboutResource());

        $desc->setResourceDescription('Some resource desc');
        $descDef = [
            'name' => 'fooResource',
            'description' => 'Some resource desc',
            'actions' => []
        ];
        $this->assertEquals($descDef, $desc->getInfoAboutResource());

        $desc->addResourceAction(new Action('Foo action', 'PUT', 'Foo desc'));
        $desc->addResourceAction(new Action('Foobar action', 'DELETE', 'Foobar desc'));
        $descDef = [
            'name' => 'fooResource',
            'description' => 'Some resource desc',
            'actions' => [
                [
                    'name' => 'Foo action',
                    'description' => 'Foo desc',
                    'method' => 'PUT',
                    'params' => [],
                    'response' => []
                ],
                [
                    'name' => 'Foobar action',
                    'description' => 'Foobar desc',
                    'method' => 'DELETE',
                    'params' => [],
                    'response' => []
                ]
            ]
        ];
        $this->assertEquals($descDef, $desc->getInfoAboutResource());
    }

}
