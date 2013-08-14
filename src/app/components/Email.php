<?php

namespace VJ;

class Email
{
    private static $view = null;

    /**
     * 发送Email
     *
     * @param $email
     * @param $subject
     * @param $body
     *
     * @return bool
     */
    public static function send($email, $subject, $body)
    {
        \VJ\IO\Node::request('mail/send', null, array(
            'to'      => $email,
            'subject' => $subject,
            'html'    => $body
        ));

        return true;
    }

    /*
    public static function sendFromTemplate($email, $subject, $controller_name, $action_name, $vars)
    {
        if (self::$view == null)
        {
            self::$view = new \Phalcon\Mvc\View();

            self::$view->setViewsDir('../app/views/mail/');
            self::$view->registerEngines(array('.volt' => function ($view) {

                $volt = new \Phalcon\Mvc\View\Engine\Volt($view);
                \VJ\View::extendVolt($volt, $view);

                return $volt;

            }));

            \VJ\View::extendView(self::$view);
        }

        self::$view->setVars($vars);

        self::$view->start();
        self::$view->render($controller_name, $action_name);
        self::$view->finish();

        return self::$view->getContent();
    }
    */

}