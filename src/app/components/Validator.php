<?php

namespace VJ;

class Validator
{

    public static function required($params, $checkArray)
    {

        foreach ($params as $param)
        {
            if (!isset($checkArray[$param]))
            {
                return \VJ\I::error('MISSING_ARGUMENT', $param);
            }
        }

        return true;
    }
    
}