<?php

namespace Ekimik\ApiUtils\Controller\Util;

use Ekimik\ApiUtils\Resource\ResponseBuilder;
use Psr\Http\Message\ResponseInterface as Response;

trait WriteResponseData {

	protected function writeResponseData(Response $response, array $data) {
		$r = ResponseBuilder::createResponse($data);
		$response->getBody()->write((string) $r);
	}

}