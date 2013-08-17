<?php

namespace VJ\ErrorHandler;

class WhoopsServiceProvider
{

    /**
     * 初始化Whoops错误处理系统
     * modify from https://github.com/filp/whoops/blob/master/src/Whoops/Provider/Phalcon/WhoopsServiceProvider.php
     */
    public function __construct($di = null)
    {

        if (!$di) {
            $di = \Phalcon\DI::getDefault();
        }

        // There's only ever going to be one error page...right?
        $di->setShared('whoops.error_page_handler', function () {
            return new \Whoops\Handler\PrettyPageHandler;
        });

        $json_handler = new \Whoops\Handler\JsonResponseHandler();
        $json_handler->onlyForAjaxRequests(true);

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
        };

        $di->setShared('whoops', function () use ($di, $phalcon_info_handler, $json_handler) {
            $run = new \Whoops\Run;
            $run->pushHandler($di['whoops.error_page_handler']);
            $run->pushHandler($json_handler);
            $run->pushHandler($phalcon_info_handler);

            return $run;
        });

        $di['whoops']->register();

    }

}