<?php

namespace Ekimik\ApiUtils\Tests\Resource;

use Ekimik\ApiUtils\Resource\Action;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class ActionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Action::getActionDefinition
     * @covers Action::setResponse
     */
    public function testAction() {
        $action = new Action('Foo', 'GET', 'Foo desc');

        $actionDef = [
            'name' => 'Foo',
            'method' => 'GET',
            'description' => 'Foo desc',
            'params' => [],
            'response' => []
        ];
        $this->assertEquals($actionDef, $action->getActionDefinition());

        $action->setResponse(['integer' => 'number of something']);
        $actionDef = [
            'name' => 'Foo',
            'method' => 'GET',
            'description' => 'Foo desc',
            'params' => [],
            'response' => ['integer' => 'number of something']
        ];
        $this->assertEquals($actionDef, $action->getActionDefinition());
    }

    /**
     * @covers Action::setParams
     * @covers Action::addParam
     */
    public function testParams() {
        $action = new Action('Foobar', 'GET', 'Foobar desc');
        $params = [
            [
                'name' => 'param_1',
                'type' => 'string',
            ],
            [
                'name' => 'param_1',
                'type' => 'array',
            ],
            [
                'name' => 'param_2',
                'type' => 'integer',
                'additionalInfo' => ['foobar barbar']
            ]
        ];
        $action->setParams($params);

        $actionDef = [
            'name' => 'Foobar',
            'method' => 'GET',
            'description' => 'Foobar desc',
            'params' => [
                'param_1' => [
                    'type' => 'string',
                    'additionalInfo' => []
                ],
                'param_2' => [
                    'type' => 'integer',
                    'additionalInfo' => ['foobar barbar']
                ]
            ],
            'response' => []
        ];

        $this->assertEquals($actionDef, $action->getActionDefinition());
    }

}
