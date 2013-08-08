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
                return \VJ\I::error('ARGUMENT_MISSING', $param);
            }
        }

        return true;
    }

    /**
     * 检查并返回MongoId对象，若失败则返回null
     *
     * @param $p
     *
     * @return \MongoId|null
     */
    public static function mongoId($p)
    {

        $p = (string)$p;

        if (strlen($p) !== 24) {
            return null;
        }

        try {

            $p = new \MongoId($p);
            return $p;

        } catch(\MongoException $e) {

            return null;

        }

    }

    /**
     * 根据指定的规则过滤数组
     *
     * @param $data
     * @param $rules
     *
     * @return array
     */
    public static function filter($data, $rules)
    {

        $ret = array();

        foreach ($rules as $key => $rule)
        {

            switch($rule)
            {
                case 'int':
                    $ret[$key] = (int)$data[$key];
                    break;

                case 'string':
                    $ret[$key] = (string)$data[$key];
                    break;

                case 'html':
                    $ret[$key] = \VJ\Escaper::html($data[$key]);
                    break;

                case null:
                    $ret[$key] = $data[$key];
                    break;
            }

        }

        return $ret;

    }

}