<style>
@import url(http://fonts.googleapis.com/css?family=Open+Sans);

body, .vijos { margin:0;color:#333;font-size:14px;font-family:"Open Sans","Segoe UI","Tahoma","Verdana","微软雅黑","Microsoft YaHei","宋体";background:#F2F2F2; }
.vijos { padding:100px 30px; }
.vijos-box { background:#fff;box-shadow:0 0 15px rgba(0,0,0,0.3);border-radius:5px;max-width:900px;margin:0 auto; }
.vijos-header { background:#3AA9DE;color:#FFF;padding:10px;line-height:200%;font-size:15px;border-radius:5px 5px 0 0;border-bottom:3px solid #85CAEB; }
.vijos-footer { font-size:12px;color:#444;padding:10px;border-top:1px solid #DDD;background:#F4F4F4;border-radius:0 0 5px 5px; }
.vijos-content { padding:30px; }
.vijos-content p { margin:5px 0; }
.vijos a,
.vijos a:visited,
.vijos a:link,
.vijos a:active { color:#1D76C7;text-decoration:none; }
.vijos a:hover { color:#3A93E2;text-decoration:underline; }
.vijos blockquote { padding:5px 10px;border-left:10px solid #E2EFFA;margin:20px;margin-left:0;_zoom:1; }

</style>
<div class="vijos">
<div class="vijos-box">
<div class="vijos-header">{{ TITLE }}{{ TITLE_SUFFIX }}</div>
<div class="vijos-content">
{% block body %}{% endblock %}
</div>
<div class="vijos-footer">
{% block footer %}{% endblock %}
</div>
</div>
</div>