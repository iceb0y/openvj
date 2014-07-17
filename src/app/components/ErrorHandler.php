<?php

namespace VJ;

class ErrorHandler
{

    public static function whoops()
    {
        $di = \Phalcon\DI::getDefault();

        // There's only ever going to be one error page...right?
        $di->setShared('whoops.error_page_handler', function () {
            return new \Whoops\Handler\PrettyPageHandler;
        });

        // Retrieves info on the Phalcon environment and ships it off
        // to the PrettyPageHandler's data tables:
        // This works by adding a new handler to the stack that runs
        // before the error page, retrieving the shared page handler
        // instance, and working with it to add new data tables
        $phalcon_info_handler = function () use ($di) {
            try {
                $request = $di['request'];
            } catch (Exception $e) {
                // This error occurred too early in the application's life
                // and the request instance is not yet available.
                return;
            }

            // Request info:
            $di['whoops.error_page_handler']->addDataTable('Phalcon Application (Request)', [
                'URI'          => $request->getScheme().'://'.$request->getServer('HTTP_HOST').$request->getServer('REQUEST_URI'),
                'Request URI'  => $request->getServer('REQUEST_URI'),
                'Path Info'    => $request->getServer('PATH_INFO'),
                'Query String' => $request->getServer('QUERY_STRING') ? : '<none>',
                'HTTP Method'  => $request->getMethod(),
                'Script Name'  => $request->getServer('SCRIPT_NAME'),
                //'Base Path'   => $request->getBasePath(),
                //'Base URL'    => $request->getBaseUrl(),
                'Scheme'       => $request->getScheme(),
                'Port'         => $request->getServer('SERVER_PORT'),
                'Host'         => $request->getServerName(),
            ]);

            // Session info:
            global $__SESSION, $__CONFIG;

            $di['whoops.error_page_handler']->addDataTable('OpenVJ Session', (array)$__SESSION);
            $di['whoops.error_page_handler']->addDataTable('OpenVJ Session Properties', [

                'save session'   => \VJ\Session\Utils::$save,
                'session cookie' => \VJ\Session\Utils::$sessname,
                'session id'     => \VJ\Session\Utils::$sessid,
                'configs'        => (array)$__CONFIG->Session

            ]);

            $di['whoops.error_page_handler']->addDataTable('Application config', (array)$__CONFIG);
        };

        $di->setShared('whoops', function () use ($di, $phalcon_info_handler) {
            $run = new \Whoops\Run;

            if (\VJ\Utils::isAjax()) {
                $run->pushHandler(new \VJ\ErrorHandler\Json());
            } else {
                $run->pushHandler($di['whoops.error_page_handler']);
                $run->pushHandler($phalcon_info_handler);
            }

            return $run;
        });

        $di['whoops']->register();
    }

    public static function phalcon()
    {
        $di = \Phalcon\DI::getDefault();
        $di->setShared('dispatcher', function () use ($di) {

            $eventsManager = new \Phalcon\Events\Manager();
            $eventsManager->attach('dispatch:beforeException', function ($event, $dispatcher, $exception) use ($di) {

                if ($exception instanceof \VJ\Exception) {
                    $di->getShared('view')->EXCEPTION = $exception;
                    $dispatcher->forward([
                        'controller' => 'error',
                        'action'     => 'general'
                    ]);

                    return false;
                }

                if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
                    $dispatcher->forward([
                        'controller' => 'error',
                        'action'     => 'show404',
                    ]);

                    return false;
                }
            });

            $dispatcher = new \VJ\Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }
}