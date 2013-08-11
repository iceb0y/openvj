<?php

namespace VJ;

class Email
{
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
}