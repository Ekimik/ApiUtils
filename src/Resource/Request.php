<?php

namespace Ekimik\ApiUtils\Resource;

use \Ekimik\ApiUtils\InputData\Completion;
use \Ekimik\ApiDesc\Resource\Action;

/**
 * @author Jan Jíša <j.jisa@seznam.cz>
 * @package Ekimik\ApiUtils
 */
class Request {

    /** @var Completion */
    protected $completion;
    /** @var Action */
    protected $action;
    /** @var array */
    protected $inputData;

    private $completed = false;

    public function __construct(array $inputData, Action $action, Completion $completion) {
        $this->inputData = $inputData;
        $this->action = $action;
        $this->completion = $completion;
    }

    public function getInputData(): array {
        if (!$this->isCompleted()) {
            $this->completion->complete($this->inputData, $this->action);
            $this->completed = true;
        }

        return $this->inputData;
    }

    public function getAction(): Action {
        return $this->action;
    }

    public function isCompleted(): bool {
        return $this->completed;
    }

}
