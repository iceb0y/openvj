<?php

namespace VJ\User\Security;

use \VJ\Models;

class ACL
{

    /**
     * 初始化组权限表
     */
    public static function initialize()
    {

        global $__GROUP_PRIV;

        $cache = \Phalcon\DI::getDefault()->getShared('cache');
        $key = 'openvj-cache-grouppriv';

        $__GROUP_PRIV = $cache->get($key);

        if ($__GROUP_PRIV === false) {
            
            $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

            $rec          = $mongo->System->findOne(['_id' => 'privtable']);
            $__GROUP_PRIV = $rec['v'];

            $cache->save($key, $__GROUP_PRIV);

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
    public static function merge($userPriv, $group)
    {

        global $__GROUP_PRIV;

        return $userPriv + $__GROUP_PRIV[(int)$group];
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

            $_PRIV = self::merge($u->priv, $u->group);

            if (!isset($_PRIV[$priv])) {
                return false;
            }

            return (bool)$_PRIV[$priv];

        } else {

            global $_PRIV;

            if ($_PRIV == null || !isset($_PRIV[$priv])) {
                return false;
            }

            return (bool)$_PRIV[$priv];

        }

    }

}