{{ get_doctype() }}
{% block predefine %}{% endblock %}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
<meta name="keywords" content="{% block meta_keyword %}{{ META_KEYWORD }}{% endblock %}"/>
<meta name="description" content="{% block meta_desc %}{{ META_DESC }}{% endblock %}"/>
<title>{{ TITLE }}{{ TITLE_SUFFIX }}</title>
<script type="text/javascript">
if(top.location!==self.location){top.location=self.location}else{if(top!==self){if(confirm("Reload?")){top.location.reload()}}};
</script>
<script type="text/javascript">
//<![CDATA[
(function(global)
{
    global.CONFIG = {{ APP_CONFIG|json_encode }};
    global.USER = {{ USER_DATA|json_encode }};
})(window);
//]]>
</script>
<link href="{{ view_static('css/base.css') }}" rel="stylesheet" type="text/css" charset="UTF-8" />
<link href="{{ view_static('css/page.css') }}" rel="stylesheet" type="text/css" charset="UTF-8" />
{% if EXT_CSS is defined %}{% for PATH, IS_BASE in EXT_CSS %}
<link href="{{ view_static(PATH, IS_BASE) }}" rel="stylesheet" type="text/css" charset="UTF-8" />
{% endfor %}{% endif %}
</head>
<body class="page_{{ PAGE_CLASS }}{% if STYLE_WIDE is defined %} page_widescreen{% endif %}">
<div id="container" class="vj-dlg-under">
{% if STYLE_NO_USERBAR is not defined %}{% include "vendor/userbar.volt" %}{% endif %}
{% if STYLE_NO_NAVBAR is not defined %}{% include "vendor/navbar.volt" %}{% endif %}
<div class="cont-body">
{% block body %}{% endblock %}
</div>
</div>
{% include "vendor/footer.volt" %}
<script type="text/javascript" src="{{ view_static('lib/jquery/jquery-1.10.2.min.js', true) }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ view_static('lib/vijos-ext/vijos-ext.js', true) }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ view_static('lib/vijos-ext/vijos.js', true) }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ view_static('i18n/zh_CN.js', true) }}" charset="UTF-8"></script>
{% if EXT_JS is defined %}{% for PATH, IS_BASE in EXT_JS %}
<script type="text/javascript" src="{{ view_static(PATH, IS_BASE) }}" charset="UTF-8"></script>
{% endfor %}{% endif %}
</body>
</html>