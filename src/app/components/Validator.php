<?php

namespace VJ;

use \VJ\I;
use \VJ\Utils;

class Validator
{

    const REGEX_EMAIL = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';

    /**
     * 检查参数是否缺失
     *
     * @param $params
     * @param $checkArray
     *
     * @return array|bool
     */
    public static function required($checkArray, $params)
    {

        foreach ($params as $param) {
            if (!isset($checkArray[$param])) {
                return I::error('ARGUMENT_MISSING', $param);
            }
        }

        return true;
    }

    /**
     * 检验参数是否有效
     *
     * @param $checkArray
     * @param $rule_container
     *
     * @return array|bool
     */
    public static function validate($checkArray, $rule_container)
    {

        foreach ($rule_container as $key => $rules) {

            if (!isset($checkArray[$key])) {
                return I::error('ARGUMENT_MISSING', $key);
            }

            $value = $checkArray[$key];

            foreach ($rules as $ruleName => $ruleValue) {

                switch ($ruleName) {

                    case 'in':

                        if (!in_array($value, $ruleValue)) {
                            return I::error('ARGUMENT_INVALID', $key);
                        }

                        break;

                    case 'length':

                        if ($ruleValue[0] == null) {
                            $ruleValue[0] = (int)(PHP_INT_MAX + 1);
                        }
                        if ($ruleValue[1] == null) {
                            $ruleValue[1] = PHP_INT_MAX;
                        }

                        $length = Utils::len($value);

                        if ((int)$length < $ruleValue[0] || (int)$length > $ruleValue[1]) {
                            return I::error('ARGUMENT_INVALID', $key);
                        }

                        break;

                    case 'regex':

                        if (!preg_match($ruleValue, $value)) {
                            return I::error('ARGUMENT_INVALID', $key);
                        }

                        break;

                }

            }

        }

        return true;

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

        $ret = [];

        foreach ($rules as $key => $_rule) {

            $ret[$key] = $data[$key];

            if (!is_array($_rule)) {
                $_rule = [$_rule];
            }

            foreach ($_rule as $rule) {

                switch ($rule) {

                    case 'int':
                        $ret[$key] = (int)$ret[$key];
                        break;

                    case 'string':
                        $ret[$key] = (string)$ret[$key];
                        break;

                    case 'html':
                        $ret[$key] = \VJ\Escaper::html($ret[$key]);
                        break;

                    case 'trim':
                        $ret[$key] = trim($ret[$key]);
                        break;

                    case 'lower':
                        $ret[$key] = strtolower($ret[$key]);
                        break;

                    case 'upper':
                        $ret[$key] = strtoupper($ret[$key]);
                        break;

                }

            }

        }

        return $ret;

    }

    /**
     * 检查是否是一个Email地址
     *
     * @param $p
     *
     * @return bool
     */
    public static function email($p)
    {

        $p = (string)$p;

        return (bool)preg_match(self::REGEX_EMAIL, $p);

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

        } catch (\MongoException $e) {

            return null;

        }

    }

}