<?php

namespace Ekimik\ApiUtils\Tests\InputData;

use \Ekimik\ApiUtils\InputData\Completion;
use \Ekimik\ApiDesc\Resource\Action;
use \Ekimik\ApiDesc\Param\Request as RequestParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class CompletionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Completion::complete
     */
    public function testComplete() {
	$completion = new Completion();
	$action = new Action('foobar', 'GET');
	$action->addParam(new RequestParam('foo', 'string'));
	$action->addParam(new RequestParam('bar', 'integer'));
	$rp = new RequestParam('baz', 'array');
	$rp->addParam(new RequestParam('barbar', 'boolean'));
	$rpSub2 = new RequestParam('bazbaz', 'array');
	$rpSub2->addParam(new RequestParam('param1', 'string'));
	$rpSub2->addParam(new RequestParam('param2', 'float'));
	$rp->addParam($rpSub2);
	$action->addParam($rp);

	$inputData = [];
	$completion->complete($inputData, $action);
	$completedData = [
	    'foo' => NULL,
	    'bar' => NULL,
	    'baz' => [
		'barbar' => NULL,
		'bazbaz' => [
		    'param1' => NULL,
		    'param2' => NULL,
		],
	    ],
	];
	$this->assertEquals($completedData, $inputData);

	$inputData = [
	    'foo' => 'value',
	    'baz' => [
		'barbar' => FALSE,
		'bazbaz' => [
		    'param2' => 1.5,
		],
	    ],
	];
	$completion->complete($inputData, $action);
	$completedData = [
	    'foo' => 'value',
	    'bar' => NULL,
	    'baz' => [
		'barbar' => FALSE,
		'bazbaz' => [
		    'param1' => NULL,
		    'param2' => 1.5,
		],
	    ],
	];
	$this->assertEquals($completedData, $inputData);

	$inputData = [
	    'foo' => 'value',
	    'baz' => [
		'barbar' => FALSE,
		'bazbaz' => 1,
	    ],
	];
	$completion->complete($inputData, $action);
	$completedData = [
	    'foo' => 'value',
	    'bar' => NULL,
	    'baz' => [
		'barbar' => FALSE,
		'bazbaz' => 1,
	    ],
	];
	$this->assertEquals($completedData, $inputData);
    }

}
