<?php

namespace VJ;

class Exception extends \Exception
{
    const CODE_USER_ERROR = 100;

    public function __construct()
    {
        $message = call_user_func_array('\VJ\I18N::get', func_get_args());
        parent::__construct($message, self::CODE_USER_ERROR);
    }
    

    public function toAjaxObject()
    {
        global $__CONFIG;

        $obj = ['succeeded' => false, 'message' => $this->message];
        if ($__CONFIG->Debug->enabled) {
            $obj['file'] = $this->file;
            $obj['line'] = $this->line;
            $obj['trace'] = $this->getTraceAsString();
        }

        return $obj;
    }
}