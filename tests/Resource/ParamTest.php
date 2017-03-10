<?php

namespace Ekimik\ApiUtils\Tests\Resource;

use \Ekimik\ApiUtils\Resource\Param;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class ParamTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Param::setSubParams
     */
    public function testSetSubParams() {
	$params = [];
	$params[] = new Param('foo', 'string', FALSE);
	$params[] = new Param('bar', 'array');
	$params[] = new Param('baz', 'integer');

	$mainParam = new Param('foobar', 'array');
	$mainParam->setSubParams($params);

	$this->assertEquals('foobar', $mainParam->getName());
	$this->assertEquals('array', $mainParam->getType());
	$this->assertTrue($mainParam->isRequired());

	$subParamsAssert = [
	    'foo' => $params[0],
	    'bar' => $params[1],
	    'baz' => $params[2],
	];
	$this->assertSame($subParamsAssert, $mainParam->getSubParams());
    }

    /**
     * @covers Param::toArray
     */
    public function testToArray() {
	$params = [];
	$params[] = new Param('foo', 'boolean', FALSE);
	$params[] = new Param('bar', 'string');
	$params[] = new Param('baz', 'float');
	$params[2]->setSubParams([new Param('barbar', 'object', FALSE)]);

	$mainParam = new Param('foobar', 'array');
	$mainParam->setSubParams($params);

	$assert = [
	    'name' => 'foobar',
	    'type' => 'array',
	    'required' => TRUE,
	    'additionalInfo' => [],
	    'params' => [
		'foo' => [
		    'name' => 'foo',
		    'type' => 'boolean',
		    'required' => FALSE,
		    'additionalInfo' => [],
		    'params' => []
		],
		'bar' => [
		    'name' => 'bar',
		    'type' => 'string',
		    'required' => TRUE,
		    'additionalInfo' => [],
		    'params' => []
		],
		'baz' => [
		    'name' => 'baz',
		    'type' => 'float',
		    'required' => TRUE,
		    'additionalInfo' => [],
		    'params' => [
			'barbar' => [
			    'name' => 'barbar',
			    'type' => 'object',
			    'required' => FALSE,
			    'additionalInfo' => [],
			    'params' => []
			]
		    ]
		],
	    ],
	];
	$this->assertEquals($assert, $mainParam->toArray());
    }

    /**
     * @covers Param::setSubParams
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Every item have to be instance of Ekimik\ApiUtils\Resource\Param
     */
    public function testSetSubParamsInvalidParams() {
	$param = new Param('foo', 'string');
	$param->setSubParams(['bar', 'baz']);
    }

}
