<?php

namespace Ekimik\ApiUtils\ActionValidator;

use Ekimik\ApiDesc\Resource\Action;

interface IFactory {

    public function create(Action $action): IActionValidator;

}