<?php

namespace VJ;

class Validator
{

    /**
     * 检查参数是否缺失
     *
     * @param $params
     * @param $checkArray
     *
     * @return array|bool
     */
    public static function required($params, $checkArray)
    {

        foreach ($params as $param) {
            if (!isset($checkArray[$param])) {
                return \VJ\I::error('MISSING_ARGUMENT', $param);
            }
        }

        return true;
    }

}