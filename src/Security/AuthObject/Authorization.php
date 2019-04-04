<?php

namespace Ekimik\ApiUtils\Security\AuthObject;

class Authorization {

    const PROP_TOKEN = 'token';
    const PROP_TIMESTAMP = 'timestamp';
    const PROP_CLIENT_IDENT = 'clientIdent';
    const PROP_RESOURCE = 'resource';
    const PROP_PRIVILEGE = 'privilege';

    private $defaultPropNames = [
        self::PROP_TOKEN => 'X-AUTH-TOKEN',
        self::PROP_TIMESTAMP => '_timestamp',
        self::PROP_CLIENT_IDENT => '_clientIdent',
        self::PROP_RESOURCE => 'resource',
        self::PROP_PRIVILEGE => 'privilege',
    ];

    private $props;
    private $authData = [];

    public function __construct(string $clientIdent, array $authPropNames = []) {
        $this->props = array_merge($this->defaultPropNames, $authPropNames);

        $key = $this->props[self::PROP_CLIENT_IDENT];
        $this->authData['body'][$key] = $clientIdent;
    }

    public function against(string $endpoint) {
        $this->authData['endpoint'] = $endpoint;
        return $this;
    }

    public function where(string $resource, string $privilege) {
        $key = $this->props[self::PROP_RESOURCE];
        $this->authData['body'][$key] = $resource;

        $key = $this->props[self::PROP_PRIVILEGE];
        $this->authData['body'][$key] = $privilege;

        return $this;
    }

    public function withToken(string $token) {
        $key = $this->props[self::PROP_TOKEN];
        $this->authData['headers'][$key] = $token;
        return $this;
    }

    public function getAuthParams(): array {
        $data = $this->authData;
        $data['body'][$this->props[self::PROP_TIMESTAMP]] = (string) time();

        return $data;
    }

}