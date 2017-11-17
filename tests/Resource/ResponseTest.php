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
	$decorator = new Response();
	$this->assertEmpty($decorator->getData());
	$this->assertEmpty($decorator->getErrors());

	$data = [
	    'foo' => 'bar',
	    'baz' => 'barbar',
	    'list' => [1, 2, 3]
	];
	$decorator = new Response($data);
	$this->assertEquals($data, $decorator->getData());
	$this->assertEmpty($decorator->getErrors());

	$decorator->addError(['message' => 'Error message']);
	$this->assertEquals($data, $decorator->getData());
	$this->assertEquals([['message' => 'Error message']], $decorator->getErrors());

	$data = [
	    'title' => 'Some title',
	    'message' => 'Some long message',
	    'errors' => [['message' => 'Some error message']]
	];
	$decorator = new Response($data);
	$this->assertEquals(['title' => 'Some title', 'message' => 'Some long message'], $decorator->getData());
	$this->assertEquals([['message' => 'Some error message']], $decorator->getErrors());

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
	$decorator = new Response($data);

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
	$this->assertEquals($resData, $decorator->getData());

	$errors = [
	    ['message' => 'Some error message'],
	    ['message' => 'Some error message 3']
	];
	$this->assertEquals($errors, $decorator->getErrors());
    }

}
