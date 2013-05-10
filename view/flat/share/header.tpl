<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
<meta name="keywords" content="{% IF $HEADER_META_KEYWORD %}{% $HEADER_META_KEYWORD %}{% ELSE %}Vijos,OJ,Online Judge,ACM,NOI,NOIP,信息学,在线评测,题库,评测系统{% END %}"/>
<meta name="description" content="{% IF $HEADER_META_DESC %}{% $HEADER_META_DESC %}{% ELSE %}Vijos 是国内最著名的信息学在线评测 (OnlineJudge) 系统之一，为用户提供在线评测服务，并集成了模拟测试、讨论、题解、团队等交互性功能。{% END %}"/>
<title>{% $PAGE_TITLE %} - {% $PAGE_TITLE_SUFFIX %}</title>
<script type="text/javascript">if(top.location!==self.location){top.location=self.location}else{if(top!==self){if(confirm("是否重新载入页面?")){top.location.reload()}}};</script>
<link href="{% $ENV_CDN %}/{% this::static_revision($ENV_TEMPLATE_DIR, '/css/base.css') %}" rel="stylesheet" type="text/css" />
<script type="text/javascript">var _hmt=_hmt||[];(function(){var b=document.createElement("script");b.src="//hm.baidu.com/hm.js?a44424b774e6b920eb7d4a02fa11498f";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();</script>
</head>
<body>