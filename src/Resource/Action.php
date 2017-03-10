<?php

namespace Ekimik\ApiUtils\Resource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Action implements IAction {

    protected $actionInfo = [
	'name' => NULL,
	'description' => NULL,
	'method' => NULL,
	'params' => [],
	'response' => []
    ];

    public function __construct(string $actionName, string $method, string $desc) {
	$this->actionInfo['name'] = $actionName;
	$this->actionInfo['method'] = $method;
	$this->actionInfo['description'] = $desc;
    }

    public function getActionDefinition(): array {
	return $this->actionInfo;
    }

    /**
     * @param string $name
     * @param string $type
     * @param bool $isRequired
     * @param Param[] $subParams
     * @return Param|null
     */
    public function addParam(string $name, string $type, bool $isRequired, array $subParams = []) {
	$param = new Param($name, $type, $isRequired);

	if (!empty($subParams)) {
	    $param->setSubParams($subParams);
	}

	if (!isset($this->actionInfo['params'][$name])) {
	    $this->actionInfo['params'][$name] = $param;
	    return $param;
	}

	return NULL;
    }

    /**
     * @return Param[]
     */
    public function getParams(): array {
	return $this->actionInfo['params'];
    }

    public function setResponse(string $type, string $desc) {
	$this->actionInfo['response'] = ['type' => $type, 'description' => $desc];
    }

    public function getParam(string $paramPath) {
	$paramPathParts = explode(Param::PATH_SEPARATOR, $paramPath);
	$paramPathPartsClone = $paramPathParts;
	$paramPathPartsCount = count($paramPathParts);
	$params = $this->getParams();
	$lastFoundParamInPath = NULL;
	$iterableParams = $params;

	while ($pathPart = array_shift($paramPathPartsClone)) {
	    if (empty($iterableParams[$pathPart])) {
		break;
	    } else if ($paramPathPartsCount > 1 && !$iterableParams[$pathPart]->hasSubParams() && $iterableParams[$pathPart]->getName() !== $pathPart) {
		break;
	    }

	    $lastFoundParamInPath = $iterableParams[$pathPart];
	    $iterableParams = $iterableParams[$pathPart]->getSubParams();
	}

	if (!empty($lastFoundParamInPath && $lastFoundParamInPath->getName() === $paramPathParts[$paramPathPartsCount - 1])) {
	    return $lastFoundParamInPath;
	}

	return NULL;
    }

}
