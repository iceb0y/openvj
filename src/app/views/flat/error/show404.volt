{% extends "layout.volt" %}
{% block body %}
{% set EXT_JS = ['lib/traer.js/traer.js': true, 'js/error/404.js': false] %}
<div class="error-404-canvas-container">
<canvas id="canvas" class="error-404-canvas"></canvas>
</div>
{% endblock %}