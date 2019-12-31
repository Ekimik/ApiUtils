<?php

namespace Ekimik\ApiUtils\Tests\Controller\Util;

use Ekimik\ApiUtils\Controller\Util\GetRequestData;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class GetRequestDataTest extends TestCase {

	/**
	 * @covers GetRequestData::
	 */
	public function testGetRequestData() {
		$mock = $this->getMockBuilder(GetRequestData::class)
			->getMockForTrait();
		$rc = new \ReflectionClass($mock);
		$m = $rc->getMethod('getRequestData');
		$m->setAccessible(true);

		$r = new ServerRequest('HEAD', 'http://www.example.com/foo');
		$this->assertEmpty($m->invoke($mock, $r));

		$r = new ServerRequest('GET', 'http://www.example.com/foo');
		$this->assertEmpty($m->invoke($mock, $r));

		$r = new ServerRequest('GET', 'http://www.example.com/foo?id=1&name=bar');
		$data = [
			'id' => 1,
			'name' => 'bar'
		];
		$this->assertEquals($data, $m->invoke($mock, $r));

		$r = new ServerRequest('GET', 'http://www.example.com/foo', [], 'foobar');
		$this->assertEquals('foobar', $m->invoke($mock, $r));

		$r = new ServerRequest(
			'GET',
			'http://www.example.com/foo',
			['Content-Type' => 'application/json'],
			'{"id":1,"name":"bar"}'
		);
		$data = [
			'id' => 1,
			'name' => 'bar'
		];
		$this->assertEquals($data, $m->invoke($mock, $r));

		$r = new ServerRequest('POST', 'http://www.example.com/foo', [], 'foobar');
		$this->assertEquals('foobar', $m->invoke($mock, $r));

		$r = new ServerRequest(
			'POST',
			'http://www.example.com/foo',
			['Content-Type' => 'application/json'],
			'{"id":1,"name":"bar"}'
		);
		$data = [
			'id' => 1,
			'name' => 'bar'
		];
		$this->assertEquals($data, $m->invoke($mock, $r));
	}

}
