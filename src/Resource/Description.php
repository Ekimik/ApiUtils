<?php

namespace Ekimik\ApiUtils\Resource;

use \Nette\Object;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Description extends Object implements IDescription {

    protected $aboutInfo = [
        'description' => null,
        'actions' => []
    ];

    public function addResourceAction(IAction $action) {
        $this->aboutInfo['actions'][] = $action->getActionDefinition();
    }

    public function getInfoAboutResource(): array {
        return $this->aboutInfo;
    }

    public function setResourceDescription(string $desc) {
        $this->aboutInfo['description'] = $desc;
    }

}
