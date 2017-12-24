<?php

namespace Ekimik\ApiUtils\Tests\Resource;

use \Ekimik\ApiUtils\Resource\Response;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class ResponseTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Response::addErrors
     * @covers Response::getErrors
     * @covers Response::addError
     * @covers Response::getData
     */
    public function testResponse() {
	$r = new Response();
	$this->assertEmpty($r->getData());
	$this->assertEmpty($r->getErrors());

	$data = [
	    'foo' => 'bar',
	    'baz' => 'barbar',
	    'list' => [1, 2, 3]
	];
	$r = new Response($data);
	$this->assertEquals($data, $r->getData());
	$this->assertEmpty($r->getErrors());

	$r->addError(['message' => 'Error message']);
	$this->assertEquals($data, $r->getData());
	$this->assertEquals([['message' => 'Error message']], $r->getErrors());

	$data = [
	    'title' => 'Some title',
	    'message' => 'Some long message',
	    'errors' => [['message' => 'Some error message']]
	];
	$r = new Response($data);
	$this->assertEquals(['title' => 'Some title', 'message' => 'Some long message'], $r->getData());
	$this->assertEquals([['message' => 'Some error message']], $r->getErrors());

	$data = [
	    [
		'title' => 'Some title',
		'message' => 'Some long message',
		'errors' => [
		    ['message' => 'Some error message']
		]
	    ],
	    [
		'title' => 'Some title 2',
		'message' => 'Some long message 2',
	    ],
	    [
		'title' => 'Some title 3',
		'message' => 'Some long message',
		'errors' => [
		    ['message' => 'Some error message 3']
		]
	    ],
	];
	$r = new Response($data);

	$resData = [
	    [
		'title' => 'Some title',
		'message' => 'Some long message',
	    ],
	    [
		'title' => 'Some title 2',
		'message' => 'Some long message 2',
	    ],
	    [
		'title' => 'Some title 3',
		'message' => 'Some long message',
	    ],
	];
	$this->assertEquals($resData, $r->getData());

	$errors = [
	    ['message' => 'Some error message'],
	    ['message' => 'Some error message 3']
	];
	$this->assertEquals($errors, $r->getErrors());
    }

}
