<?php

namespace VJ\Controller;

class Basic extends \Phalcon\Mvc\Controller
{
    /**
     * 重定向到404页面
     *
     * @return bool
     */
    public function raise404()
    {
        $this->response->setStatusCode(404, 'Not Found');

        $this->dispatcher->forward([
            'controller' => 'error',
            'action'     => 'show404',
        ]);

        return false;
    }

    /**
     * 重定向到Ajax反馈
     *
     * @param null $data
     *
     * @return bool
     */
    public function forwardAjax($data = null)
    {
        if ($data == null) {
            $data = [];
        }

        $this->view->AJAX_DATA = $data;

        $this->dispatcher->forward([
            'controller' => 'ajax',
            'action'     => 'forwarded'
        ]);

        return false;
    }

}