{% extends "layout.volt" %}
{% block predefine %}{% include "manage/predefine.volt" %}{% endblock %}
{% block body %}
{% include "manage/body_header.volt" %}
<script>var ACL_PRIVTREE = {{ ACL_PRIVTREE|json_encode }}, ACL_PRIVTABLE = {{ ACL_PRIVTABLE|json_encode }}, ACL_GROUPS = {{ ACL_GROUPS|json_encode }}, ACL_RULES = {{ ACL_RULES|json_encode }};</script>
<div class="manage-cont">
<div id="privTable"><div id="freezing"><div class="thead"></div><div class="tbody"></div></div></div>
<div class="form-line"><input type="button" class="button button-def role-acl-save" value="保存ACL规则"><input type="button" class="button" value="Export" onclick="window.location='{{ BASE_PREFIX }}/manage/acl?export=true&token={{ USER_DATA['csrf-token'] }}'"></div>
</div>
{% include "manage/body_footer.volt" %}
{% endblock %}
{% block footer %}
<script>$_init([{{ view_static('js/manage/acl.js', false)|json_encode }}]);</script>
{% endblock %}