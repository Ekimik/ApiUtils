<?php


namespace Ekimik\ApiUtils\Resource;

use Ekimik\ApiUtils\Exception\ApiException;

class ResponseBuilder {

	public static function createErrorResponseFromException(\Throwable $e): Response {
		$errors = [
			'message' => $e->getMessage()
		];

		if ($e instanceof ApiException) {
			$errors = array_merge($errors, $e->getErrors());
		}

		return self::createErrorResponse($errors);
	}

	public static function createErrorResponse(array $errors): Response {
		return self::createResponse([], $errors);
	}

	public static function createResponse(array $data = [], array $errors = []): Response {
		$r = new Response();
		$r->setData($data);
		$r->setErrors($errors);

		return $r;
	}

}