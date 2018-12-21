<?php

namespace Ekimik\ApiUtils\Security\AuthObject;

class Authorization {

    const PROP_TOKEN = 'token';
    const PROP_TIMESTAMP = 'timestamp';
    const PROP_CLIENT_IDENT = 'clientIdent';
    const PROP_USER = 'user';
    const PROP_RESOURCE = 'resource';
    const PROP_PRIVILEGE = 'privilege';

    private $defaultPropNames = [
        self::PROP_TOKEN => '_token',
        self::PROP_TIMESTAMP => '_timestamp',
        self::PROP_CLIENT_IDENT => '_clientIdent',
        self::PROP_USER => 'userId',
        self::PROP_RESOURCE => 'resource',
        self::PROP_PRIVILEGE => 'privilege',
    ];

    private $props;
    private $data = [];

    public function __construct(string $clientIdent, array $authPropNames = []) {
        $this->props = array_merge($this->defaultPropNames, $authPropNames);

        $key = $this->props[self::PROP_CLIENT_IDENT];
        $this->data['body'][$key] = $clientIdent;
    }

    public function against(string $endpoint) {
        $this->data['endpoint'] = $endpoint;
        return $this;
    }

    public function who($userId) {
        $key = $this->props[self::PROP_USER];
        $this->data['body'][$key] = $userId;
        return $this;
    }

    public function where(string $resource, string $privilege) {
        $key = $this->props[self::PROP_RESOURCE];
        $this->data['body'][$key] = $resource;

        $key = $this->props[self::PROP_PRIVILEGE];
        $this->data['body'][$key] = $privilege;

        return $this;
    }

    public function withToken(string $token) {
        $key = $this->props[self::PROP_TOKEN];
        $this->data['body'][$key] = $token;
        return $this;
    }

    public function getAuthParams(): array {
        $data = $this->data;
        $data['body'][$this->props[self::PROP_TIMESTAMP]] = (string) time();

        return $data;
    }

}