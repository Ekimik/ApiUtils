<?php

namespace Ekimik\ApiUtils\Tests\Resource;

use Ekimik\ApiUtils\Resource\Action,
    \Ekimik\ApiUtils\Resource\Param;

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

	$action->setResponse('integer', 'number of something');
	$actionDef = [
	    'name' => 'Foo',
	    'method' => 'GET',
	    'description' => 'Foo desc',
	    'params' => [],
	    'response' => [
		'type' => 'integer',
		'description' => 'number of something'
	    ]
	];
	$this->assertEquals($actionDef, $action->getActionDefinition());
    }

    /**
     * @covers Action::addParam
     * @covers Action::getParam
     */
    public function testAddParam() {
	$action = new Action('Foobar', 'GET', 'Foobar desc');
	$action->addParam('param_1', 'string', TRUE);
	$action->addParam('param_1', 'array', FALSE);
	$action->addParam('param_2', 'integer', FALSE);

	$actionDef = [
	    'name' => 'Foobar',
	    'method' => 'GET',
	    'description' => 'Foobar desc',
	    'params' => [
		'param_1' => $action->getParam('param_1'),
		'param_2' => $action->getParam('param_2'),
	    ],
	    'response' => []
	];

	$this->assertEquals($actionDef, $action->getActionDefinition());
    }

    /**
     * @covers Action::getParam
     * @covers Action::addParam
     */
    public function testGetParam() {
	$action = new Action('/barbar', 'POST', 'Some desc');
	$action->addParam('param_1', 'string', TRUE);
	$action->addParam('param_2', 'integer', FALSE);

	$param = $action->getParam('param_1');
	$this->assertInstanceOf(Param::class, $param);
	$this->assertEquals('param_1', $param->getName());

	$param = $action->getParam('param_2');
	$this->assertInstanceOf(Param::class, $param);
	$this->assertEquals('param_2', $param->getName());
    }

    /**
     * @covers Action::getParam
     * @covers Action::setParams
     */
    public function testGetParamNested() {
	// setup
	$action = new Action('/baz', 'DELETE', 'Some desc');

	$param1 = new Param('foobar', 'integer');
	$param2 = new Param('baz', 'string');
	$param3 = new Param('bar', 'array');
	$param3->setSubParams([$param1, $param2]);

	$param4 = new Param('foo', 'array');
	$param4->setSubParams([$param3]);
	$action->addParam('foo', 'array', TRUE, [$param3]);

	// assertions
	$param = $action->getParam('foo');
	$this->assertInstanceOf(Param::class, $param);
	$this->assertEquals('foo', $param->getName());

	$param = $action->getParam('foo.bar');
	$this->assertInstanceOf(Param::class, $param);
	$this->assertEquals('bar', $param->getName());

	$param = $action->getParam('foo.bar.baz');
	$this->assertInstanceOf(Param::class, $param);
	$this->assertEquals('baz', $param->getName());

	$param = $action->getParam('foo.bar.unknown');
	$this->assertNull($param);

	$param = $action->getParam('foo.');
	$this->assertNull($param);
    }

}
