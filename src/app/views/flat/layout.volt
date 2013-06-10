<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
<meta name="keywords" content="{% block meta_keyword %}Vijos,OJ,Online Judge,ACM,NOI,NOIP,信息学,在线评测,题库,评测系统{% endblock %}"/>
<meta name="description" content="{% block meta_desc %}Vijos 是国内最著名的信息学在线评测 (OnlineJudge) 系统之一，为用户提供在线评测服务，并集成了模拟测试、讨论、题解、团队等交互性功能。{% endblock %}"/>
<title>{{ TITLE }}{{ TITLE_SUFFIX }}</title>
<script type="text/javascript" charset="UTF-8">if(top.location!==self.location){top.location=self.location}else{if(top!==self){if(confirm("是否重新载入页面?")){top.location.reload()}}};</script>
<script type="text/javascript" charset="UTF-8">var _hmt=_hmt||[];(function(){var b=document.createElement("script");b.src="//hm.baidu.com/hm.js?a44424b774e6b920eb7d4a02fa11498f";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();</script>
<link href="{{ view_static('css/base.css') }}" rel="stylesheet" type="text/css" charset="UTF-8" />
{% if EXT_CSS is defined %}{% for PATH in EXT_CSS %}
<link href="{{ view_static(PATH) }}" rel="stylesheet" type="text/css" charset="UTF-8" />
{% endfor %}{% endif %}
</head>
<body>
<div id="container">
<div class="cont-nav">
    <div class="cont-nav-li"><a class="cont-nav-a cont-nav-active" href="#"><span class="cont-nav-icon icon-symbol icon-home"></span></a></div>
    <div class="cont-nav-li"><a class="cont-nav-a" href="#"><span class="cont-nav-icon icon-symbol icon-problems"></span></a></div>
    <div class="cont-nav-li"><a class="cont-nav-a" href="#"><span class="cont-nav-icon icon-symbol icon-discussion"></span></a></div>
</div>
<div class="cont-body">
<div id="content">
{% block body %}{% endblock %}
</div>
<div id="footer">苏ICP备13006782号, Powered by OpenVJ α.<br>© Copyright 2013 Vijos. Process in {{ view_processTime() }} ms</div>
</div>
</div>
<script type="text/javascript" src="{{ view_static('lib/jquery/jquery-1.9.1.min.js', true) }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ view_static('lib/vijos-ext/vijos-ext.js', true) }}" charset="UTF-8"></script>
{% if EXT_JS is defined %}{% for PATH in EXT_JS %}
<script type="text/javascript" src="{{ view_static(PATH) }}" charset="UTF-8"></script>
{% endfor %}{% endif %}
</body>
</html>