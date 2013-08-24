<?php

namespace VJ\User\Security;

use \VJ\Models;

class ACL
{

    const CACHE_ACL_KEY = 'openvj-cache-groupacl';
    const SYSTEM_ID_ACL = 'acl';
    const SYSTEM_ID_ACL_RULES = 'acl_rules';

    /**
     * 初始化组权限表
     */
    public static function initialize()
    {

        global $__GROUP_ACL;

        $cache = \Phalcon\DI::getDefault()->getShared('cache');

        $__GROUP_ACL = $cache->get(self::CACHE_ACL_KEY);

        if ($__GROUP_ACL === false) {

            $mongo        = \Phalcon\DI::getDefault()->getShared('mongo');
            $rec          = $mongo->System->findOne(['_id' => self::SYSTEM_ID_ACL]);
            $__GROUP_ACL = $rec['v'];

            $cache->save(self::CACHE_ACL_KEY, $__GROUP_ACL);

        }

    }

    /**
     * 根据用户自身权限和用户组计算最终权限
     *
     * @param $userPriv
     * @param $group
     *
     * @return mixed
     */
    public static function merge($userACL, $group)
    {

        global $__GROUP_ACL;

        return $userACL + $__GROUP_ACL[(int)$group];
    }

    /**
     * 判断当前或特定用户是否有某项权限
     *
     * @param      $priv
     * @param null $uid
     *
     * @return bool
     */
    public static function has($priv, $uid = null)
    {

        if ($uid !== null) {

            $u = Models\User::findFirst([
                'conditions' => ['_id' => (int)$uid]
            ]);

            if ($u == false) {
                return false;
            }

            $_ACL = self::merge($u->acl, $u->group);

            if (!isset($_ACL[$priv])) {
                return false;
            }

            return (bool)$_ACL[$priv];

        } else {

            global $_ACL;

            if ($_ACL == null || !isset($_ACL[$priv])) {
                return false;
            }

            return (bool)$_ACL[$priv];

        }

    }

    /**
     * 保存ACL规则
     *
     * @param $ACL
     * @param $ACLRule
     */
    public static function save($ACL, $ACLRule)
    {

        //TODO: Check privilege

        global $__GROUP_ACL;

        // update records
        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
        $mongo->System->update(['_id' => self::SYSTEM_ID_ACL], [
            '$set' => [
                'v' => $ACL
            ]
        ], ['upsert' => true]);
        $mongo->System->update(['_id' => self::SYSTEM_ID_ACL_RULES], [
            '$set' => [
                'v' => $ACLRule
            ]
        ], ['upsert' => true]);

        // update cache
        $cache = \Phalcon\DI::getDefault()->getShared('cache');
        $cache->save(self::CACHE_ACL_KEY, $ACL);

        $__GROUP_ACL = $ACL;

        return true;

    }

    /**
     * 查询权限表
     *
     * @return array
     */
    public static function queryPrivilegeTable()
    {

        $fp = fopen(APP_DIR.'includes/privilege.php', 'r');

        $priv = [];
        $flag = false;

        while (!feof($fp)) {

            $line = fgets($fp);

            if (strpos($line, 'PRIVILEGE-TABLE-BEGIN') !== false) {
                $flag = true;
                continue;
            }

            if ($flag === false) {
                continue;
            }

            if (strpos($line, 'PRIVILEGE-TABLE-END') !== false) {
                break;
            }

            if (strlen(trim($line)) === 0) {
                continue;
            }

            preg_match('/const\s*(\w+)\s*=\s*(\d+);\s*\/\/([\s\S]*)$/', $line, $matches);

            if ($matches != null) {

                $priv[$matches[1]] = [
                    'v' => (int)$matches[2],
                    'd' => trim($matches[3])
                ];

            }
        }

        fclose($fp);

        return $priv;

    }

    /**
     * 查询权限规则表
     *
     * @return mixed
     */
    public static function queryRules()
    {

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');
        $rec   = $mongo->System->findOne(['_id' => self::SYSTEM_ID_ACL_RULES]);

        return $rec['v'];

    }

    /**
     * 将权限表转换为权限树
     *
     * @param $privFlat
     *
     * @return array
     */
    public static function convertToTree($privFlat)
    {

        $tree = [];

        foreach ($privFlat as $name => $value) {

            $namespace = explode('_', $name);

            $ref = & $tree;

            foreach ($namespace as $n) {

                if (!isset($ref[$n])) {
                    $ref[$n] = [];
                }

                $ref = & $ref[$n];
            }

            $ref['_v'] = $value['v'];
            $ref['_d'] = $value['d'];

        }

        return $tree;

    }


}