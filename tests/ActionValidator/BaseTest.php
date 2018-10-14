<?php

namespace Ekimik\ApiUtils\Tests\ActionValidator;

use \Ekimik\ApiUtils\ActionValidator\Base;
use \Ekimik\Validators\ValidatorFactory;
use \Ekimik\ApiDesc\Resource\Action;
use \Ekimik\ApiDesc\Param\Request as RequestParam;
use \Ekimik\ApiUtils\Resource\Request;
use \Ekimik\ApiUtils\InputData\Completion;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class BaseTest extends \PHPUnit\Framework\TestCase {

    /** @var Base */
    private $object;
    /** @var Action */
    private $action;

    protected function setUp() {
        parent::setUp();

        $this->action = new Action('foobar', 'GET');
        $this->action->addParam(new RequestParam('foo', 'string', false));
        $this->action->addParam(new RequestParam('bar', 'integer'));
        $rp = new RequestParam('baz', 'array');
        $rp->addParam(new RequestParam('barbar', 'boolean'));
        $rpSub2 = new RequestParam('bazbaz', 'array');
        $rpSub2->addParam(new RequestParam('param1', 'string'));
        $rpSub2->addParam(new RequestParam('param2', 'float', false));
        $rp->addParam($rpSub2);
        $this->action->addParam($rp);

        $this->object = new Base(new ValidatorFactory());
    }

    /**
     * @covers Base
     */
    public function testValidate() {
        $input = [
            'foo' => 'asds',
            'bar' => 1234,
            'baz' => [
                'barbar' => false
            ],
            'some_val' => null,
        ];
        $r = new Request($input, $this->action, new Completion());
        $this->object->validate($r);
        $this->assertFalse($this->object->isValid());
        $errors = $this->object->getErrors();
        $assert = [
            'param1' => 'Field \'param1\' is required, but is missing or has empty value',
            'some_val' => 'Unknown input field \'some_val\'',
        ];
        $this->assertEquals($assert, $errors);

        $input = [
            'bar' => 1234,
            'baz' => [
                'barbar' => false
            ],
        ];
        $r = new Request($input, $this->action, new Completion());
        $this->object->validate($r);
        $this->assertFalse($this->object->isValid());
        $errors = $this->object->getErrors();
        $assert = [
            'param1' => 'Field \'param1\' is required, but is missing or has empty value',
        ];
        $this->assertEquals($assert, $errors);

        $input = [
            'foo' => false,
            'bar' => 'asds',
            'baz' => [
                'barbar' => 1.5,
                'bazbaz' => 1
            ],
        ];
        $r = new Request($input, $this->action, new Completion());
        $this->object->validate($r);
        $this->assertFalse($this->object->isValid());
        $errors = $this->object->getErrors();
        $assert = [
            'foo' => 'Field \'foo\' should be of type \'string\', but \'boolean\' given',
            'bar' => 'Field \'bar\' should be of type \'integer\', but \'string\' given',
            'barbar' => 'Field \'barbar\' should be of type \'boolean\', but \'double\' given',
            'bazbaz' => 'Field \'bazbaz\' should be of type \'array\', but \'integer\' given',
        ];
        $this->assertEquals($assert, $errors);

        $input = [
            'foo' => 'asds',
            'bar' => 1234,
            'baz' => [
                'barbar' => false,
                'bazbaz' => [
                    'param1' => 'dsas',
                    'param2' => 1.6,
                ]
            ],
        ];
        $r = new Request($input, $this->action, new Completion());
        $this->object->validate($r);
        $this->assertTrue($this->object->isValid());

        $input = [
            'bar' => 1234,
            'baz' => [
                'barbar' => false,
                'bazbaz' => [
                    'param1' => 'dsas',
                ]
            ],
        ];
        $r = new Request($input, $this->action, new Completion());
        $this->object->validate($r);
        $this->assertTrue($this->object->isValid());
    }

}
