{% extends "layout.volt" %}
{% block body %}
<div class="cont-wrap"><div class="grid_12">
<h2>Oops, an error happened: {{ ERROR_OBJECT['errorCode'] }}</h2>
<h3>{{ ERROR_OBJECT['errorMsg'] }}</h3>
</div></div>
{% endblock %}