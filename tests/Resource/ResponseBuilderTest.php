<?php


namespace Ekimik\ApiUtils\Tests\Resource;

use Ekimik\ApiUtils\Exception\ApiException;
use Ekimik\ApiUtils\Resource\ResponseBuilder;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class ResponseBuilderTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers ResponseBuilder::createErrorResponseFromException
	 */
	public function testCreateErrorResponseFromException() {
		$response = [
			'responseData' => [],
			'errors' => [
				['message' => 'Foo bar'],
			],
		];
		$e = new \Exception('Foo bar', 123);
		$this->assertEquals($response, ResponseBuilder::createErrorResponseFromException($e)->getResponse());

		$response = [
			'responseData' => [],
			'errors' => [
				['message' => 'Foo bar'],
				['message' => 'Foo baz'],
			],
		];
		$e = new ApiException('Foo bar', 123);
		$e->setErrors(['message' => 'Foo baz']);
		$this->assertEquals($response, ResponseBuilder::createErrorResponseFromException($e)->getResponse());
	}

	/**
	 * @covers ResponseBuilder::createErrorResponse
	 */
	public function testCreateErrorResponse() {
		$response = [
			'responseData' => [],
			'errors' => [
				['message' => 'Foo bar'],
			],
		];
		$this->assertEquals($response, ResponseBuilder::createErrorResponse([['message' => 'Foo bar']])->getResponse());
	}

	/**
	 * @covers ResponseBuilder::createResponse
	 */
	public function testResponse() {
		$this->assertEquals(['responseData' => [], 'errors' => []], ResponseBuilder::createResponse()->getResponse());

		$response = [
			'responseData' => [
				'id' => 123,
			],
			'errors' => [
				['message' => 'Foo bar'],
			],
		];
		$this->assertEquals($response, ResponseBuilder::createResponse(['id' => 123], [['message' => 'Foo bar']])->getResponse());
	}

}