<?php

namespace VJ\ErrorHandler;

use Whoops\Handler\Handler;

class Json extends Handler
{
    /**
     * @return int
     */
    public function handle()
    {
        if (!\VJ\Utils::isAjax()) {
            return Handler::DONE;
        }

        global $__CONFIG;

        $exception = $this->getInspector()->getException();
        $obj = ['succeeded' => false, 'error' => ['type' => 'Exception', 'message' => $exception->getMessage()]];
        if ($__CONFIG->Debug->enabled) {
            $obj['error']['file'] = $exception->getFile();
            $obj['error']['line'] = $exception->getLine();
            $obj['error']['trace'] = $exception->getTraceAsString();
        }

        if (\Whoops\Util\Misc::canSendHeaders()) {
            header('Content-Type: application/json');
        }

        echo json_encode($obj);
        return Handler::QUIT;
    }
}