<?php

namespace VJ;

class Exception extends \Exception
{
    const CODE_LOGIC_ERROR = 0;

    public function __construct()
    {
        $message = call_user_func_array('\VJ\I18N::get', func_get_args());
        parent::__construct($message, self::CODE_LOGIC_ERROR);
    }
}