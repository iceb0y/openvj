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

    public function exceptionAction()
    {
        throw new Exception();
    }

    public function vjexceptionAction()
    {
        throw new \VJ\Exception();
    }
}