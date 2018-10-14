<?php

namespace Ekimik\ApiUtils\Tests\Resource;

use \Ekimik\ApiUtils\Resource\Request;
use \Ekimik\ApiUtils\InputData\Completion;
use \Ekimik\ApiDesc\Resource\Action;
use \Ekimik\ApiDesc\Param\Request as RequestParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class RequestTest extends \PHPUnit\Framework\TestCase {

    /**
     * @covers Request
     */
    public function testRequest() {
        $action = new Action('foobar', 'GET');
        $action->addParam(new RequestParam('foo', 'string', false));
        $action->addParam(new RequestParam('bar', 'integer'));

        $inputData = ['bar' => 123];
        $r = new Request($inputData, $action, new Completion());
        $this->assertFalse($r->isCompleted());
        $reqData = $r->getInputData();
        $this->assertEquals(['foo' => null, 'bar' => 123], $reqData);
        $this->assertTrue($r->isCompleted());
    }

}
