<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
<meta name="keywords" content="{% block meta_keyword %}{{ META_KEYWORD }}{% endblock %}"/>
<meta name="description" content="{% block meta_desc %}{{ META_DESC }}{% endblock %}"/>
<title>{{ TITLE }}{{ TITLE_SUFFIX }}</title>
<script type="text/javascript" charset="UTF-8">if(top.location!==self.location){top.location=self.location}else{if(top!==self){if(confirm("Reload?")){top.location.reload()}}};</script>
<link href="{{ view_static('css/base.css') }}" rel="stylesheet" type="text/css" charset="UTF-8" />
{% if EXT_CSS is defined %}{% for PATH in EXT_CSS %}
<link href="{{ view_static(PATH) }}" rel="stylesheet" type="text/css" charset="UTF-8" />
{% endfor %}{% endif %}
</head>
<body id="page_{{ PAGE_CLASS }}">
<div id="container">
{% include "vendor/userbar.volt" %}
{% include "vendor/navbar.volt" %}
<div class="cont-body"><div class="cont-wrap">
{% block body %}{% endblock %}
</div></div>
</div>
{% include "vendor/footer.volt" %}
<script type="text/javascript" src="{{ view_static('lib/jquery/jquery-1.9.1.min.js', true) }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ view_static('lib/vijos-ext/vijos-ext.js', true) }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ view_static('lib/vijos-ext/vijos.js', true) }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ view_static('i18n/zh_CN.js', true) }}" charset="UTF-8"></script>
{% if EXT_JS is defined %}{% for PATH in EXT_JS %}
<script type="text/javascript" src="{{ view_static(PATH) }}" charset="UTF-8"></script>
{% endfor %}{% endif %}
</body>
</html>