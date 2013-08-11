<?php

namespace VJ;

class email
{
	/**
	* 发送电子邮件
	*/
	public static function send($email, $subject, $body)
	{
		\VJ\Node::io('mail/send', null, array
		(
			'to'      => $email,
			'subject' => $subject,
			'html'    => $body
		));

	return true;
	}
}