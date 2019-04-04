<?php

namespace Ekimik\ApiUtils\Security;

use Ekimik\ApiUtils\Exception\ApiException;
use Ekimik\ApiUtils\Security\AuthObject\Authorization;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\RequestOptions;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class Authorizator {

    /** @var Client */
    private $http;
    /** @var Authorization */
    private $ao;
    private $authApiUrl;

    public function __construct($authApiUrl) {
        $this->authApiUrl = $authApiUrl;
    }

    public function createAuthRequest(string $clientIdent, array $authPropNames = []): Authorization {
        $this->ao = new Authorization($clientIdent, $authPropNames);
        $this->ao->against('/user/authorize');

        return $this->ao;
    }

    public function authorize(): bool {
        if (empty($this->authApiUrl)) {
            return true;
        }

        if (empty($this->ao)) {
            throw new \LogicException('No auth request initialized, see ' . self::class . '::createAuthRequest');
        }

        $authParams = $this->ao->getAuthParams();
        if (empty($authParams['endpoint'])) {
            throw new \LogicException('Undefined API authorization endpoint');
        }

        try {
            $response = $this->getHttp()->get(
                $authParams['endpoint'],
                [
                    RequestOptions::QUERY => $authParams['body'],
                    RequestOptions::HEADERS => $authParams['headers'],
                ]
            );

            $authResult = $response->getBody()->getContents();
            $authResult = Json::decode($authResult);
            return $authResult->hasAccess;
        } catch (JsonException $e) {
            throw new ApiException('Cannot decode authorization response ', 500, $e);
        } catch (ClientException $e) {
            if ($e->getCode() == 401) {
                throw new ApiException('You are not logged in', 401);
            }

            throw new ApiException('Problem occurred in authorization process', 500, $e);
        } catch (TransferException $e) {
            throw new ApiException('Serious problem occurred in authorization process', 500, $e);
        } finally {
            $this->ao = null;
        }
    }

    protected function getHttp(): Client {
        if (empty($this->http)) {
            $config = [
                'base_uri' => $this->authApiUrl,
                'timeout' => 5,
            ];

            $this->http = new Client($config);
        }

        return $this->http;
    }

}