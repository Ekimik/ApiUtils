<?php

namespace Ekimik\ApiUtils\Tests\InputData;

use \Ekimik\ApiUtils\InputData\Completion;
use \Ekimik\ApiDesc\Resource\Action;
use \Ekimik\ApiDesc\Param\Request as RequestParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class CompletionTest extends \PHPUnit\Framework\TestCase {

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
            'foo' => null,
            'bar' => null,
            'baz' => [
                'barbar' => null,
                'bazbaz' => [
                    'param1' => null,
                    'param2' => null,
                ],
            ],
        ];
        $this->assertEquals($completedData, $inputData);

        $inputData = [
            'foo' => 'value',
            'baz' => [
                'barbar' => false,
                'bazbaz' => [
                    'param2' => 1.5,
                ],
            ],
        ];
        $completion->complete($inputData, $action);
        $completedData = [
            'foo' => 'value',
            'bar' => null,
            'baz' => [
                'barbar' => false,
                'bazbaz' => [
                    'param1' => null,
                    'param2' => 1.5,
                ],
            ],
        ];
        $this->assertEquals($completedData, $inputData);

        $inputData = [
            'foo' => 'value',
            'baz' => [
                'barbar' => false,
                'bazbaz' => 1,
            ],
        ];
        $completion->complete($inputData, $action);
        $completedData = [
            'foo' => 'value',
            'bar' => null,
            'baz' => [
                'barbar' => false,
                'bazbaz' => 1,
            ],
        ];
        $this->assertEquals($completedData, $inputData);
    }

}
