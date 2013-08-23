{% extends "layout.volt" %}
{% block predefine %}{% include "manage/predefine.volt" %}{% set EXT_JS = ['js/manage/acl.js': false] %}{% endblock %}
{% block body %}
{% include "manage/body_header.volt" %}

<script>var privTree = {{ ACL_PRIVTREE|json }}, userGroups = {{ ACL_GROUPS|json }}, groupPriv = {{ ACL_RULES|json }}, privFlat = {{ ACL_PRIVTABLE|json }}</script>

<div id="privTable"><div id="freezing"><div class="thead"></div><div class="tbody"></div></div></div>
<div class="form-line"><input type="button" class="button button-def role-acl-save" value="保存ACL规则"></div>

{% include "manage/body_footer.volt" %}
{% endblock %}