<?php

namespace VJ;

use Exception;
use Phalcon\Mvc\Dispatcher as PhalconDispatcher;

class Dispatcher extends PhalconDispatcher
{

    /**
     * Dispatches a handle action taking into account the routing parameters
     *
     * @return \Phalcon\Mvc\Controller
     * @throws Exception
     */
    public function dispatch()
    {
        try {
            return parent::dispatch();
        } catch (Exception $exception) {
            $result = $this->getEventsManager()->fire('dispatch:beforeException', $this, $exception);
            if ($result === false) {
                return parent::dispatch();
            } else {
                throw $exception;
            }
        }
    }
}