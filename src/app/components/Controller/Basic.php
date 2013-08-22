<?php

namespace VJ\Controller;

class Basic extends \Phalcon\Mvc\Controller
{

    /**
     * 重定向到错误页面
     *
     * @param null $err
     *
     * @return bool
     */
    public function raiseError($err = null)
    {

        if (!is_array($err)) {
            $errorObject = call_user_func_array(['\VJ\I', 'error'], func_get_args());
        } else {
            $errorObject = $err;
        }

        $this->view->ERROR_OBJECT = $errorObject;

        $this->dispatcher->forward([
            'controller' => 'error',
            'action'     => 'general'
        ]);

        return false;
    }

    /**
     * 重定向到404页面
     *
     * @return bool
     */
    public function raise404()
    {
        $this->dispatcher->forward([
            'controller' => 'error',
            'action'     => 'show404',
        ]);

        return false;
    }

}