<?php

namespace Ekimik\ApiUtils\Tests\Security;

use Ekimik\ApiUtils\Security\RequestIntegrity;
use GuzzleHttp\Psr7\ServerRequest;
use Nette\Utils\Json;

class RequestIntegrityTest extends \PHPUnit\Framework\TestCase {

    /** @var RequestIntegrity */
    private $object;
    private $secret = 'secret';

    protected function setUp() {
        parent::setUp();

        $options = [];
        if (in_array($this->getName(), ['testCheckWrongTimestampKey', 'testCheckMissingTimestampKey'])) {
            $options[RequestIntegrity::OPTION_TIMESTAMP_KEY] = 'ts';
        }

        $this->object = new RequestIntegrity($this->secret, $options);
    }

    /**
     * @covers RequestIntegrity::check
     * @expectedException \Ekimik\ApiUtils\Exception\ApiException
     * @expectedExceptionMessageRegExp /^Missing request hash header/
     * @expectedExceptionCode 400
     */
    public function testCheckNoIntegrityHeader() {
        $r = new ServerRequest(
            'GET',
            '/foo/bar'
        );
        $this->object->check($r);
    }

    /**
     * @covers RequestIntegrity::check
     * @expectedException \Ekimik\ApiUtils\Exception\ApiException
     * @expectedExceptionMessage Client and server request hash does not match
     * @expectedExceptionCode 422
     */
    public function testCheckWrongHash() {
        $r = new ServerRequest(
            'GET',
            '/foo/bar?baz=barbar',
            [
                'Content-Type' => 'application/json',
                'X-HTTP-REQ-HASH' => hash_hmac('md5', '', $this->secret),
            ]
        );

        $this->object->check($r);
    }

    /**
     * @covers RequestIntegrity::check
     * @expectedException \Ekimik\ApiUtils\Exception\ApiException
     * @expectedExceptionMessage Request validity interval exceeded
     * @expectedExceptionCode 422
     */
    public function testCheckWrongInterval() {
        $body = Json::encode(['baz' => 'barbar', '_timestamp' => time() - 10]);
        $r = new ServerRequest(
            'POST',
            '/foo/bar',
            [
                'Content-Type' => 'application/json',
                'X-HTTP-REQ-HASH' => hash_hmac('md5', $body, $this->secret),
            ],
            $body
        );

        $this->object->check($r);
    }

    /**
     * @covers RequestIntegrity::check
     * @expectedException \Ekimik\ApiUtils\Exception\ApiException
     * @expectedExceptionMessage Timestamp field 'ts' in request data is missing
     * @expectedExceptionCode 400
     */
    public function testCheckWrongTimestampKey() {
        $body = Json::encode(['baz' => 'barbar', '_timestamp' => time()]);
        $r = new ServerRequest(
            'POST',
            '/foo/bar',
            [
                'Content-Type' => 'application/json',
                'X-HTTP-REQ-HASH' => hash_hmac('md5', $body, $this->secret),
            ],
            $body
        );

        $this->object->check($r);
    }

    /**
     * @covers RequestIntegrity::check
     * @expectedException \Ekimik\ApiUtils\Exception\ApiException
     * @expectedExceptionMessage Timestamp field 'ts' in request data is missing
     * @expectedExceptionCode 400
     */
    public function testCheckMissingTimestampKey() {
        $body = Json::encode(['baz' => 'barbar']);
        $r = new ServerRequest(
            'POST',
            '/foo/bar',
            [
                'Content-Type' => 'application/json',
                'X-HTTP-REQ-HASH' => hash_hmac('md5', $body, $this->secret),
            ],
            $body
        );

        $this->object->check($r);
    }

    /**
     * @covers RequestIntegrity::check
     */
    public function testCheck() {
        $body = Json::encode(['baz' => 'barbar', '_timestamp' => time()]);
        $r = new ServerRequest(
            'POST',
            '/foo/bar',
            [
                'Content-Type' => 'application/json',
                'X-HTTP-REQ-HASH' => hash_hmac('md5', $body, $this->secret),
            ],
            $body
        );

        $this->assertTrue($this->object->check($r));
    }

}
