<?php

class DebugController extends \VJ\Controller\Basic
{
    public function initialize()
    {
        global $__CONFIG;

        if (!$__CONFIG->Debug->enabled) {
            $this->raise404();
        }
    }

    public function errorAction()
    {
        throw new Exception();
    }
}