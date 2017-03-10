<?php

namespace Ekimik\ApiUtils\Resource;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Description implements IDescription {

    protected $aboutInfo = [
        'name' => NULL,
        'description' => NULL,
        'actions' => []
    ];

    public function __construct(string $resourceName) {
        $this->aboutInfo['name'] = $resourceName;
    }

    public function addResourceAction(IAction $action) {
        $this->aboutInfo['actions'][] = $action->getActionDefinition();
    }

    public function getActions(): array {
	return $this->aboutInfo['actions'];
    }

    public function getInfoAboutResource(): array {
        return $this->aboutInfo;
    }

    public function setResourceDescription(string $desc) {
        $this->aboutInfo['description'] = $desc;
    }

}
