<?php

namespace VJ\Session;

interface SessionProvider
{
    /**
     * 建立一个新的Session
     *
     * @param $sess_id
     * @param $data
     *
     * @return mixed
     */
    public function newSession($sess_id, $data);

    /**
     * 获取一个Session的内容
     *
     * @param $sess_id
     *
     * @return mixed
     */
    public function getSession($sess_id);

    /**
     * 保存一个Session
     *
     * @param $sess_id
     * @param $data
     *
     * @return mixed
     */
    public function saveSession($sess_id, $data);

    /**
     * 删除一个Session
     *
     * @param $sess_id
     *
     * @return mixed
     */
    public function deleteSession($sess_id);

}