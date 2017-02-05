<?php

namespace Ekimik\ApiUtils\Tests\ActionValidator;

use \Ekimik\ApiUtils\ActionValidator\Base;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package ClubManager\FileApi
 */
class BaseTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Base::validate
     * @covers Base::isInputDataFieldReuqired
     * @covers Base::addError
     * @covers Base::isValid
     */
    public function testValidate() {
        $validator = new Base();
        $data = ['foo' => 'bar'];
        $validator->validate($data);

        $this->assertFalse($validator->isValid());
        $errors = [
            'foo' => 'Unknown input field \'foo\'',
        ];
        $this->assertEquals($errors, $validator->getErrors());

        $validator = new Base();
        $validator->setAllowedInputFields(['foo', 'bar', 'baz', 'foobar']);
        $data = ['foo' => 'barbar', 'baz' => NULL, 'foobar' => ''];
        $validator->validate($data);

        $this->assertFalse($validator->isValid());
        $errors = [
            'bar' => 'Field \'bar\' is required, but is missing or has empty value',
            'baz' => 'Field \'baz\' is required, but is missing or has empty value',
            'foobar' => 'Field \'foobar\' is required, but is missing or has empty value',
        ];
        $this->assertEquals($errors, $validator->getErrors());

        $validator = new Base();
        $validator->setAllowedInputFields(['foo']);
        $data = ['foo' => 'barbar'];
        $validator->validate($data);
        $this->assertTrue($validator->isValid());

        $validator = new Base();
        $validator->setAllowedInputFields(['foo', 'bar', 'baz']);
        $validator->setRequiredInputFields(['foo']);
        $data = ['foo' => 'trololol'];
        $validator->validate($data);
        $this->assertTrue($validator->isValid());

        $data = [];
        $validator->validate($data);
        $this->assertFalse($validator->isValid());
        $errors = [
            'foo' => 'Field \'foo\' is required, but is missing or has empty value',
        ];
        $this->assertEquals($errors, $validator->getErrors());
    }

}
