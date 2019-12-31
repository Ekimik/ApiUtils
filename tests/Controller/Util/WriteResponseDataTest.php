<?php

namespace Ekimik\ApiUtils\Tests\Controller\Util;

use Ekimik\ApiUtils\Controller\Util\WriteResponseData;
use GuzzleHttp\Psr7\Response;
use Ekimik\ApiUtils\Resource\Response as ResponseObj;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\TestCase;

class WriteResponseDataTest extends TestCase {

	private $file = DIR_TESTS_TMP . '/dummyfile';

	protected function setUp() {
		parent::setUp();
		FileSystem::write($this->file, '');
	}

	protected function tearDown() {
		parent::tearDown();
		FileSystem::delete($this->file);
	}

	/**
	 * @covers WriteResponseData::
	 */
	public function testWriteResponseData() {
		$mock = $this->getMockBuilder(WriteResponseData::class)
			->getMockForTrait();
		$rc = new \ReflectionClass($mock);
		$m = $rc->getMethod('writeResponseData');
		$m->setAccessible(true);

		$r = new Response(200, [], fopen($this->file, 'r+'));
		$m->invoke($mock, $r, ['id' => 1, 'name' => 'foobar']);
		$this->assertEquals(
			(string) new ResponseObj(['id' => 1, 'name' => 'foobar']),
			(string) $r->getBody()
		);
	}

}
