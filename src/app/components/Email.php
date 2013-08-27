<?php

namespace VJ;

use \VJ\I;

class Email
{

    /**
     * 发送Email
     *
     * @param $email
     * @param $subject
     * @param $body
     *
     * @return array|bool|mixed
     */
    public static function send($email, $subject, $body)
    {

        global $__CONFIG;

        $result = \VJ\IO\Bgservice::post('/mail/send', [
            'receiver'  => $email,
            'subject'   => $__CONFIG->Mail->subjectPrefix.$subject,
            'body'      => $body
        ]);

        if (I::isError($result)) {
            return $result;
        } else {
            return true;
        }
    }

    /**
     * 使用模板发送Email
     *
     * @param      $email
     * @param      $subject
     * @param      $controller_name
     * @param      $action_name
     * @param null $vars
     *
     * @return array|bool|mixed
     */
    public static function sendByTemplate($email, $subject, $controller_name, $action_name, $vars = null)
    {
        $view = new \VJ\View\Email();

        $view->setVar('TARGET_MAIL', \VJ\Escaper::html($email));

        if ($vars) {
            $view->setVars($vars);
        }

        $view->start();
        $view->render($controller_name, $action_name);
        $view->finish();

        $body = $view->getContent();

        return self::send($email, $subject, $body);
    }

}