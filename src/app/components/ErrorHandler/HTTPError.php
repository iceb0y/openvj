<?php

namespace VJ\ErrorHandler;

class HTTPError
{

    public static function attach()
    {

        $di = \Phalcon\DI::getDefault();

        $di->setShared('dispatcher', function () use ($di) {

            $evManager = $di->getShared('eventsManager');
            $evManager->attach('dispatch:beforeException', function ($event, $dispatcher, $exception) {

                if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
                    $dispatcher->forward([
                        'controller' => 'error',
                        'action'     => 'show404',
                    ]);

                    return false;
                }

            });

            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($evManager);

            return $dispatcher;

        });
    }

}