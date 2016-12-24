<?php

namespace Ekimik\ApiUtils\Resource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Action implements IAction {

    protected $actionInfo = [
        'name' => null,
        'description' => null,
        'method' => null,
        'params' => [],
        'response' => []
    ];

    public function __construct(string $actionName, string $method, string $desc) {
        $this->actionInfo['name'] = $actionName;
        $this->actionInfo['description'] = $desc;
        $this->actionInfo['method'] = $method;
    }

    public function getActionDefinition() : array {
        return $this->actionInfo;
    }

    public function setParams(array $params) {
        foreach ($params as $param) {
            $this->addParam($param['name'], $param['type'], $param['additionalInfo'] ?? []);
        }
    }

    public function addParam(string $name, string $type, array $aditionalInfo) {
        $paramDefinition = [
            'type' => $type,
            'additionalInfo' => $aditionalInfo,
        ];

        if (!isset($this->actionInfo['params'][$name])) {
            $this->actionInfo['params'][$name] = $paramDefinition;
        }
    }

    public function setResponse(array $response) {
        $this->actionInfo['response'] = $response;
    }

}
