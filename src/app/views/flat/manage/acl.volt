{% extends "layout.volt" %}
{% block predefine %}{% include "manage/predefine.volt" %}{% set EXT_JS = ['js/manage/acl.js': false] %}{% endblock %}
{% block body %}
{% include "manage/body_header.volt" %}

<script>var ACL_PRIVTREE = {{ ACL_PRIVTREE|json }}, ACL_PRIVTABLE = {{ ACL_PRIVTABLE|json }}, ACL_GROUPS = {{ ACL_GROUPS|json }}, ACL_RULES = {{ ACL_RULES|json }};</script>

<div class="manage-cont">
<div id="privTable"><div id="freezing"><div class="thead"></div><div class="tbody"></div></div></div>
<div class="form-line"><input type="button" class="button button-def role-acl-save" value="保存ACL规则"></div>
</div>

{% include "manage/body_footer.volt" %}
{% endblock %}