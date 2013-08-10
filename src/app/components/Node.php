<?php

namespace VJ;

class Node
{
	public static function io($location, $get = null, $post = null, $getResponse = false)
	{
		global $config;
		//处理请求数据
		$url = ENV_NODE_HOST_IO.'/'.$location;

		if ($get != null && is_array($get)) {
			$url = $url.'?'.http_build_query($get, '', '&');
		}

		//初始化
		$curl = curl_init($url);
		curl_setopt_array($curl, array
		(
			CURLOPT_CONNECTTIMEOUT => 2,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_PORT           => ENV_NODE_PORT_IO,
			CURLOPT_HEADER         => false,
			CURLOPT_NOBODY         => !$getResponse
		));

		//处理POST
		if ($post != null && is_array($post)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('json' => json_encode($post)), '', '&'));
		}

		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}
}
