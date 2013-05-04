<?php

//
// This file contains the View class which extends Blitz
//

class View extends Blitz
{
    private $inc_dir_base = '/';
    private $globals = array();

    function __construct($include_dir_base)
    {
        $this->inc_dir_base = $include_dir_base;
    }

    function setVariable($var)
    {
        $this->globals = $this->globals + $var;
    }

    function load($file)
    {
        parent::__construct($this->inc_dir_base.$file);
        parent::setGlobals($this->globals);
    }

    function execution_time()
    {
        return round((microtime(true) - ENV_REQUEST_TIME) * 1000, 5);
    }
}
