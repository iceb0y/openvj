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

    /**
     * 使用模板发送Email
     *
     * @param      $email
     * @param      $subject
     * @param      $controller_name
     * @param      $action_name
     * @param null $vars
     *
     * @return bool
     */
    public static function sendByTemplate($email, $subject, $controller_name, $action_name, $vars = null)
    {
        if (self::$view == null)
        {
            self::$view = new \Phalcon\Mvc\View();

            self::$view->setDI(new \Phalcon\DI\FactoryDefault());
            self::$view->setViewsDir('../app/views/mail/');
            self::$view->registerEngines(array('.volt' => function ($view) {

                $volt = new \Phalcon\Mvc\View\Engine\Volt($view);
                \VJ\View::extendVolt($volt, $view);

                return $volt;

            }));

            \VJ\View::extendView(self::$view);
        }

        if ($vars) {
            self::$view->setVars($vars);
        }

        self::$view->start();
        self::$view->render($controller_name, $action_name);
        self::$view->finish();

        $body = self::$view->getContent();

        return self::send($email, $subject, $body);
    }

}