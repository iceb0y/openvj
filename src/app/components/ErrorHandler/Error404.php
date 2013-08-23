<?php

namespace VJ\ErrorHandler;

use \Phalcon\Mvc\Dispatcher as PhDispatcher;

class Error404
{

    public static function attach()
    {

        $di = \Phalcon\DI::getDefault();

        $di->setShared('dispatcher', function () use ($di) {

            $evManager = $di->getShared('eventsManager');
            $evManager->attach('dispatch:beforeException', function ($event, $dispatcher, $exception) {
                switch ($exception->getCode()) {
                    case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND: // through
                    case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:

                        $dispatcher->forward([
                            'controller' => 'error',
                            'action'     => 'show404',
                        ]);

                        return false;

                        break;
                }
            });

            $dispatcher = new PhDispatcher();
            $dispatcher->setEventsManager($evManager);

            return $dispatcher;

        });
    }

}