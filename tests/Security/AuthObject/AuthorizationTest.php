<?php

namespace Ekimik\ApiUtils\Tests\Security\AuthObject;

use Ekimik\ApiUtils\Security\AuthObject\Authorization;

class AuthorizationTest extends \PHPUnit\Framework\TestCase {

    /** @var Authorization */
    private $object;

    protected function setUp() {
        parent::setUp();

        $propNames = [
            Authorization::PROP_TIMESTAMP => 'ts',
            Authorization::PROP_CLIENT_IDENT => 'client',
        ];
        $this->object = new Authorization('foobar', $propNames);
    }

    /**
     * @covers Authorization::getAuthParams
     */
    public function testGetAuthParamsNoParams() {
        $params = $this->object->getAuthParams();
        $this->assertCount(2, $params['body']);
        $this->assertNotEmpty($params['body']['ts']);
        $this->assertEquals('foobar', $params['body']['client']);
    }

    /**
     * @covers Authorization::getAuthParams
     */
    public function testGetAuthParams() {
        $this->object->where('foo', 'read')
            ->against('user/auth')
            ->withToken('123456');

        $params = $this->object->getAuthParams();
        $this->assertEquals('user/auth', $params['endpoint']);
        $this->assertCount(5, $params['body']);
        $this->assertNotEmpty($params['body']['ts']);
        $this->assertEquals('foobar', $params['body']['client']);
        $this->assertEquals('123456', $params['body']['_token']);
        $this->assertEquals('foo', $params['body']['resource']);
        $this->assertEquals('read', $params['body']['privilege']);
    }

}
