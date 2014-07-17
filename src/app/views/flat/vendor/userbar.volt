<div class="cont-userbar"><div class="cont-wrap">{% if STYLE_WIDE is not defined %}<div class="grid_12">{% endif %}
    <div class="float-left">{% block userbar_content %}Vijos 由湖南师大附中和北京八十中共同赞助{% endblock %}</div>
    <div class="float-right">
{% if hasPriv('PRIV_USER_BASIC') %}
        <span class="userbar-link">{% include "model/user/face" with ['_data_user': USER_DATA, 'SIZE': 20] %}{% include "model/user/name" with ['_data_user': USER_DATA] %}</span>{% if hasPriv('PRIV_ADMIN_ACCESS') %}<a href="{{ BASE_PREFIX }}/manage" target="_self" title="管理中心" class="userbar-link"><span class="icon-dashboard"></span></a>{% endif %}<a href="{{ BASE_PREFIX }}/user/settings" target="_self" title="设置" class="userbar-link"><span class="icon-settings"></span></a><a href="{{ BASE_PREFIX }}/user/logout?token={{ USER_DATA['csrf-token'] }}" target="_self" title="登出" class="userbar-link"><span class="icon-logout"></span></a>
{% else %}
        <a href="javascript:VJ.Utils.showLogin();">登录</a>&nbsp;|&nbsp;<a href="{{ BASE_PREFIX }}/user/register" target="_blank">注册</a>
{% endif %}
    </div>
    <div class="clear"></div>
{% if STYLE_WIDE is not defined %}</div>{% endif %}<div class="clear"></div></div></div>
