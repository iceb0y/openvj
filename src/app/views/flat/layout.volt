<!DOCTYPE html>
{% block predefine %}{% endblock %}
<html xmlns="http://www.w3.org/1999/xhtml" class="page_{{ PAGE_CLASS }}{% if STYLE_WIDE is defined %} page_widescreen{% endif %}">
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
    global.CONFIG = {{ APP_CONFIG|json }};
    global.USER = {{ USER_DATA|json }};
})(window);
//]]>
</script>
<link href="{{ template('css/base.css') }}" rel="stylesheet" type="text/css" charset="UTF-8" />
<link href="{{ template('css/page.css') }}" rel="stylesheet" type="text/css" charset="UTF-8" />
</head>
<body>
<div id="container" class="vj-dlg-under">
{% if STYLE_NO_USERBAR is not defined %}{% include "vendor/userbar.volt" %}{% endif %}
{% if STYLE_NO_NAVBAR is not defined %}{% include "vendor/navbar.volt" %}{% endif %}
<div class="cont-body">
{% block body %}{% endblock %}
</div>
</div>
{% include "vendor/footer.volt" %}
<script type="text/javascript" src="{{ asset('lib/bower_components/requirejs/require.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
require.config({ baseUrl: {{ asset('lib', false)|json }} });
</script>
<script type="text/javascript" src="{{ asset('lib/require-config.js') }}" charset="UTF-8"></script>
{% block footer %}{% endblock %}
</body>
</html>