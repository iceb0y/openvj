<?php

namespace VJ\User;

class Utils
{

    private static $userInfoFilter = ['uid' => 1, 'nick' => 1, 'g' => 1];
    private static $emptyUserModel = ['uid' => 1, 'nick' => '[Deleted]', 'g' => '', 'flag_missing' => true];

    const queryMaxChunk = 30;

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
            self::$userInfoFilter
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

        $uidList = array_map('intval', $uidList);
        $uidLists = array_chunk($uidList, self::queryMaxChunk);

        $mongo = \Phalcon\DI::getDefault()->getShared('mongo');

        $result = [];

        // Separate into many chunks. (Too large queries will cause errors)
        foreach ($uidLists as $list) {

            $cursor = $mongo->User->find(
                ['uid' => ['$in' => $list]],
                self::$userInfoFilter
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