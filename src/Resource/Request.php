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

    public function __construct(array $inputData, Action $action, Completion $completion) {
	$this->inputData = $inputData;
	$this->action = $action;
	$this->completion = $completion;
    }

    public function getInputData(): array {
	$this->completion->complete($this->inputData, $this->action);
	return $this->inputData;
    }

}
