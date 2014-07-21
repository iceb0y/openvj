{% extends "layout.volt" %}
{% block body %}
<div class="cont-wrap"><div class="grid_12">
This is index.
<p>
    <?php echo \Phalcon\Tag::linkTo("test","DataBase Test!"); ?>
</p>
</div></div>
{% endblock %}
