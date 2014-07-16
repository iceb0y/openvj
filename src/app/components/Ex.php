<?php

namespace VJ;

class Ex extends \Phalcon\Exception {

	protected $args;

	function __construct() {
		parent::__construct();

		$this->args=func_get_args();

	}
	
	public function getArgs() {
		return $this->args;
	}
}	
