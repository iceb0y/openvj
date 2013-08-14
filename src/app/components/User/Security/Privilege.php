<?php

namespace VJ\User\Security;

class Privilege
{

    /**
     * 初始化组权限表
     */
    public static function initialize()
    {

        global $__GROUP_PRIV;

        $__GROUP_PRIV = apc_fetch('openvj-cache-grouppriv');

        if ($__GROUP_PRIV === false) {

            global $mongo;

            $rec          = $mongo->System->findOne(array('_id' => 'privtable'));
            $__GROUP_PRIV = $rec['v'];

            apc_store('openvj-cache-grouppriv', $__GROUP_PRIV);

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

            global $mongo;
            $rec = $mongo->User->findOne(array('_id' => (int)$uid));

            if ($rec == null)
                return false;

            $_PRIV = self::merge($rec['priv'], $rec['group']);

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