<?php

namespace Ekimik\ApiUtils\Tests\Security;

use Ekimik\ApiUtils\Security\Authorizator;

class AuthorizatorTest extends \PHPUnit\Framework\TestCase {

    /** @var Authorizator */
    private $object;

    protected function setUp() {
        parent::setUp();

        $url = 'http://api.example.com/v1';
        if ($this->getName() === 'testAuthorizeNoUrl') {
            $url = null;
        }
        $this->object = new Authorizator($url);
    }

    /**
     * @covers Authorizator::authorize
     */
    public function testAuthorizeNoUrl() {
        $this->assertTrue($this->object->authorize());
    }

    /**
     * @covers Authorizator::authorize
     * @expectedException \LogicException
     * @expectedExceptionMessage No auth request initialized, see Ekimik\ApiUtils\Security\Authorizator::createAuthRequest
     */
    public function testAuthorizeNoRequest() {
        $this->object->authorize();
    }

    /**
     * @covers Authorizator::authorize
     * @expectedException \LogicException
     * @expectedExceptionMessage Undefined API authorization endpoint
     */
    public function testAuthorizeWrongAuthParams() {
        $this->object->createAuthRequest('foo')->where('foobar', 'read')->against('');
        $this->object->authorize();
    }

}
