<?php

namespace Ekimik\ApiUtils\Controller\Util;

use Nette\Utils\Json;
use Psr\Http\Message\ServerRequestInterface as Request;

trait GetRequestData {

	/**
	 * @param Request $request
	 * @return mixed|null
	 * @throws \Nette\Utils\JsonException
	 */
	protected function getRequestData(Request $request) {
		$method = $request->getMethod();

		if (
			in_array($method, ['POST', 'PUT']) ||
			($method === 'GET' && !empty($request->getBody()->getSize()))
		) {
			$requestBody = $request->getBody()->getContents();
			if (
				$request->hasHeader('Content-Type')
				&& $request->getHeader('Content-Type')[0] === 'application/json'
			) {
				return Json::decode($requestBody, Json::FORCE_ARRAY);
			}

			return $requestBody;
		}

		if (in_array($method, ['GET', 'DELETE'])) {
			$query = $request->getUri()->getQuery();
			parse_str($query, $queryParams);
			return $queryParams;
		}

		return null;
	}

}