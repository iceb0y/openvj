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
                throw new \VJ\Exception('ERR_ARGUMENT_MISSING', $param);
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
                throw new \VJ\Exception('ERR_ARGUMENT_MISSING', $key);
                
            }

            $value = $checkArray[$key];

            foreach ($rules as $ruleName => $ruleValue) {

                switch ($ruleName) {

                    case 'in':

                        if (!in_array($value, $ruleValue)) {
                            throw new \VJ\Exception('ERR_ARGUMENT_INVALID',$key);
                            
                        }

                        break;

                    case 'length':
                    case 'contentlength':

                        if ($ruleValue[0] == null) {
                            $ruleValue[0] = (int)(PHP_INT_MAX + 1);
                        }
                        if ($ruleValue[1] == null) {
                            $ruleValue[1] = PHP_INT_MAX;
                        }

                        $length = Utils::len($value);

                        if ($length < $ruleValue[0]) {
                            if ($ruleName == 'length') {
                                throw new \VJ\Exception('ERR_ARGUMENT_INVALID',$key);
                                
                            } else {
                                throw new \VJ\Exception('ERR_CONTENT_TOOSHORT',$ruleValue[0]);
                                
                            }
                        } else if ($length > $ruleValue[1]) {
                            if ($ruleName == 'length') {
                                throw new \VJ\Exception('ERR_ARGUMENT_INVALID',$key);
                                
                            } else {
                                throw new \VJ\Exception('ERR_CONTENT_TOOLONG',$ruleValue[1]);
                                
                            }
                        }

                        break;

                    case 'regex':

                        if (!preg_match($ruleValue, $value)) {
                            throw new \VJ\Exception('ERR_ARGUMENT_INVALID',$key);
                            
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
     * @return bool
     */
    public static function filter($data, $rules)
    {

        foreach ($rules as $key => $_rule) {

            if (!is_array($_rule)) {
                $_rule = [$_rule];
            }

            foreach ($_rule as $rule) {

                switch ($rule) {

                    case 'int':
                        $data[$key] = (int)$data[$key];
                        break;

                    case 'string':
                        $data[$key] = (string)$data[$key];
                        break;

                    case 'html':
                        $data[$key] = \VJ\Escaper::html($data[$key]);
                        break;

                    case 'trim':
                        $data[$key] = trim($data[$key]);
                        break;

                    case 'lower':
                        $ret[$key] = strtolower($data[$key]);
                        break;

                    case 'upper':
                        $data[$key] = strtoupper($data[$key]);
                        break;

                }

            }

        }

        //unset not used members
        foreach ($data as $key => &$value) {
            if (!isset($rules[$key])) {
                unset($data[$key]);
            }
        }

        return true;

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
