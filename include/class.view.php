<?php

//
// This file contains the View class which extends Blitz
//

class View extends Blitz
{
	private static $s_globals = array();

	public static function set_root($path)
	{
		ini_set('blitz.path', $path);
		ini_set('blitz.tag_open', '{%');
		ini_set('blitz.tag_close', '%}');
	}

	public static function set_variable($var)
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
    
    //no leading slashes!
    public function static_revision()
    {
        $output = $file = implode(func_get_args());
        
        $mtime = filemtime(ROOT_DIR.'www/'.$file);
        
        if ($mtime)
            $output.='?v='.$mtime;
        
        return $output;
    }
}
