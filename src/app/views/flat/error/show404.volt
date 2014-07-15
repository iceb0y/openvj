{% extends "layout.volt" %}
{% block body %}
<div class="error-404-canvas-container">
<canvas id="canvas" class="error-404-canvas"></canvas>
</div>
{% endblock %}
{% block footer %}
<script>$_init(['traer', 'raf', {{ template('js/error/404.js')|json }}]);</script>
{% endblock %}