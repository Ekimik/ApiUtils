<?php

namespace Ekimik\ApiUtils\Resource;

use \Nette\Utils\Json;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Param implements IParam {

    const PATH_SEPARATOR = '.';

    protected $paramDefinition = [
	'name' => NULL,
	'type' => NULL,
	'required' => TRUE,
	'additionalInfo' => [],
	'params' => [],
    ];

    public function __construct(string $name, string $type, bool $isRequired = TRUE) {
	$this->paramDefinition['name'] = $name;
	$this->paramDefinition['type'] = $type;
	$this->paramDefinition['required'] = $isRequired;
    }

    public function getParamDefinition(): array {
	return $this->paramDefinition;
    }

    public function getName(): string {
	return $this->paramDefinition['name'];
    }

    public function getType(): string {
	return $this->paramDefinition['type'];
    }

    /**
     * @return Param[]
     */
    public function getSubParams(): array {
	return $this->paramDefinition['params'];
    }

    public function isRequired(): bool {
	return $this->paramDefinition['required'];
    }

    public function setName(string $name) {
	$this->paramDefinition['name'] = $name;
    }

    public function setType(string $type) {
	$this->paramDefinition['type'] = $type;
    }

    /**
     * @param Param[] $params
     */
    public function setSubParams(array $params) {
	foreach ($params as $param) {
	    if (!$param instanceof Param) {
		throw new \InvalidArgumentException('Every item have to be instance of ' . self::class);
	    }

	    $this->paramDefinition['params'][$param->getName()] = $param;
	}
    }

    public function hasSubParams(): bool {
	return !empty($this->paramDefinition['params']);
    }

    public function setAdditionalInfo(array $aditionalInfo) {
	$this->paramDefinition['additionalInfo'] = $aditionalInfo;
    }

    public function setParamAditionalInfoKey(string $key, $value) {
	$this->paramDefinition['additionalInfo'][$key] = $value;
    }

    public function setRequired(bool $isRequired) {
	$this->paramDefinition['required'] = $isRequired;
    }

    public function toArray(): array {
	$paramDefinition = $this->getParamDefinition();

	foreach ($paramDefinition['params'] as $paramName => $param) {
	    if ($param->hasSubParams()) {
		$paramDefinition['params'][$paramName] = $param->toArray();
	    } else {
		$encodedParam = Json::encode($param->getParamDefinition());
		$paramDefinition['params'][$paramName] = Json::decode($encodedParam, 1);
	    }
	}

	return $paramDefinition;
    }

}
