<?php

namespace Ekimik\ApiUtils\InputData;

use \Ekimik\ApiDesc\Resource\Action;
use \Ekimik\ApiDesc\Param\Request as RequestParam;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Completion {

    public function complete(array &$inputData, Action $action) {
        $actionParams = $action->getParams();
        foreach ($actionParams as $param) {
            $this->completeParam($inputData, $param);
        }
    }

    protected function completeParam(array &$inputData, RequestParam $param) {
        $paramName = $param->getName();
        $hasSubParams = $param->hasParams();

        if (!key_exists($paramName, $inputData)) {
            $initialVal = null;
            if ($hasSubParams) {
                $initialVal = [];
            }

            $inputData[$paramName] = $initialVal;
        }

        if ($hasSubParams) {
            foreach ($param->getParams() as $p) {
                if (!is_array($inputData[$paramName])) {
                    continue;
                }

                $this->completeParam($inputData[$paramName], $p);
            }
        }
    }

}
