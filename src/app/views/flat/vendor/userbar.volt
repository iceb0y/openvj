<div class="cont-userbar"><div class="cont-wrap">{% if STYLE_WIDE is not defined %}<div class="grid_12">{% endif %}
    <div class="float-left">{% block userbar_content %}Vijos 由湖南师大附中和北京八十中共同赞助{% endblock %}</div>
    <div class="float-right">
        <a href="javascript:VJ.Utils.showLogin();">登录</a>&nbsp;|&nbsp;<a href="/user/register">注册</a>
    </div>
    <div class="clear"></div>
{% if STYLE_WIDE is not defined %}</div>{% endif %}<div class="clear"></div></div></div>