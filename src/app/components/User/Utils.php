<?php

namespace VJ\User;

use \VJ\Models;

class Utils
{

    private static $emptyUserModel = ['uid' => 1, 'nick' => '[Deleted]', 'gmd5' => '', 'flag_missing' => true];

    const _QUERY_MAX_CHUNK = 30;

    /**
     * 根据登录用户名获取UID
     *
     * @param $username
     *
     * @return int|null
     */
    public static function getUidByUsername($username)
    {

        $username = strtolower($username);

        $user = Models\User::findFirst([
            'conditions' => ['luser' => $username],
            'fields'     => ['uid' => 1]
        ]);

        if ($user) {
            return (int)$user->uid;
        } else {
            return null;
        }

    }

    /**
     * 查询单个用户的信息
     *
     * @param $uid
     *
     * @return array
     */
    public static function getUserInfo($uid)
    {

        $uid = (int)$uid;

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

        $result = $mongo->User->findOne(
            ['uid' => $uid],
            ['_id' => 0, 'uid' => 1, 'nick' => 1, 'gmd5' => 1]
        );

        if ($result == null) {
            $result = self::$emptyUserModel;
        }

        return $result;

    }

    /**
     * 查询多个用户的信息，以关联数组形式返回
     *
     * @param $uidList
     *
     * @return array
     */
    public static function getUserInfoArray($uidList)
    {

        $uidList  = array_map('intval', $uidList);
        $uidLists = array_chunk($uidList, self::_QUERY_MAX_CHUNK);

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

        $result = [];

        // Separate into many chunks. (Too large queries will cause errors)
        foreach ($uidLists as $list) {

            $cursor = $mongo->User->find(
                ['uid' => ['$in' => $list]],
                ['_id' => 0, 'uid' => 1, 'nick' => 1, 'gmd5' => 1]
            );

            foreach ($cursor as $user) {
                $result[$user['uid']] = $user;
            }

        }

        // Fill missing users
        foreach ($uidList as $uid) {

            if (!isset($result[$uid])) {
                $result[$uid] = self::$emptyUserModel;
            }

        }

        return $result;

    }

}