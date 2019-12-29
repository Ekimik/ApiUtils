<?php

namespace Ekimik\ApiUtils\Security;

use Ekimik\ApiUtils\Exception\ApiException;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Psr\Http\Message\ServerRequestInterface as Request;

class RequestIntegrity {

    const OPTION_INTEGRITY_HEADER = 'integrityHeader';
    const OPTION_ALGORITHM = 'algorithm';
    const OPTION_VALIDITY_WINDOW = 'validityWindow';
    const OPTION_TIMESTAMP_KEY = 'timestampKey';

    private $secret;
    private $options = [];
    private $defaultOptions = [
        self::OPTION_INTEGRITY_HEADER => 'X-HTTP-REQ-HASH',
        self::OPTION_ALGORITHM => 'md5',
        self::OPTION_VALIDITY_WINDOW => 5, // sec
        self::OPTION_TIMESTAMP_KEY => '_timestamp',
    ];

    /**
     * @param string $secret
     * @param array $options see OPTION_* constants for list of all supported options
     */
    public function __construct(string $secret, array $options = []) {
        $this->secret = $secret;
        $this->options = array_merge($this->defaultOptions, $options);
    }

    /**
     * @param Request $request
     * @return true
     * @throws ApiException
     */
    public function check(Request $request) {
        $options = $this->options;
        $reqHash = $request->getHeader($options[self::OPTION_INTEGRITY_HEADER]);
        $reqHash = array_reverse($reqHash)[0] ?? null;
        if (empty($reqHash)) {
            throw new ApiException("Missing request hash header '" . $options[self::OPTION_INTEGRITY_HEADER] . "'", 400);
        }

        try {
            $rawData = null;
            $data = null;

            if (in_array($request->getMethod(), ['GET', 'DELETE'])) {
                $rawData = $request->getQueryParams();
                $rawData = Json::encode($rawData);
            } else {
                $rawData = $request->getBody()->getContents();
            	$request->getBody()->rewind();
            }

            $calcHash = hash_hmac($options[self::OPTION_ALGORITHM], $rawData, $this->secret);
            if ($calcHash !== $reqHash) {
                throw new ApiException('Client and server request hash does not match', 422);
            }

            $data = $rawData;
            if ($request->getHeader('Content-Type')[0] === 'application/json') {
                $data = Json::decode($rawData, Json::FORCE_ARRAY);
            }

            $reqTimestamp = $data[$options[self::OPTION_TIMESTAMP_KEY]] ?? null;
            if (empty($reqTimestamp)) {
                throw new ApiException("Timestamp field '". $options[self::OPTION_TIMESTAMP_KEY] . "' in request data is missing", 400);
            }

            $diff = time() - $reqTimestamp;
            if ($diff > $options[self::OPTION_VALIDITY_WINDOW]) {
                throw new ApiException('Request validity interval exceeded', 422);
            }
        } catch (JsonException $e) {
            $e = new ApiException('Cannot decode request data', 400);
            $e->setErrors([
                ['message' => $e->getMessage()]
            ]);
        }

        return true;
    }

}