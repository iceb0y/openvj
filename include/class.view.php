<?php

//
// This file contains the View class which extends Blitz
//

class View extends Blitz
{
	private static $s_globals = array();

	public static function setRoot($path)
	{
		ini_set('blitz.path', $path);
	}

	public static function setVariable($var)
	{
		self::$s_globals += $var;
	}

	public function __construct($path)
	{
		parent::__construct($path);
		parent::setGlobals(self::$s_globals);
        }

	public function execution_time()
	{
		return round((microtime(true) - ENV_REQUEST_TIME) * 1000, 5);
	}
}
