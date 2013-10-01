<style>

@import url(http://fonts.googleapis.com/css?family=Open+Sans);

.vijos ::selection {background:#72D0EB;color:#FFF;text-shadow:none;}

body, .vijos { margin:0;color:#333;font-size:14px;font-family:"Open Sans","Segoe UI","Tahoma","Verdana","微软雅黑","Microsoft YaHei","宋体";background:#F2F2F2; }
.vijos { padding:100px 30px; }
.vijos-box { background:#fff;box-shadow:0 0 15px rgba(0,0,0,0.3);border-radius:5px;max-width:900px;margin:0 auto; }
.vijos-header { background:#3AA9DE;color:#FFF;padding:10px;line-height:200%;font-size:15px;border-radius:5px 5px 0 0;border-bottom:3px solid #85CAEB; }
.vijos-footer { font-size:12px;color:#444;padding:10px;border-top:1px solid #DDD;background:#F4F4F4;border-radius:0 0 5px 5px; }
.vijos-content { padding:60px 30px; }
.vijos-content p { margin:5px 0; }
.vijos a,
.vijos a:visited,
.vijos a:active { color:#1D76C7;text-decoration:none; }
.vijos a:hover { color:#3A93E2;text-decoration:underline; }
.vijos blockquote { padding:5px 10px;border-left:10px solid #E2EFFA;margin:20px;margin-left:0;_zoom:1; }

.vijos .larger { font-size:1.3em; }

.vijos .button {display:inline-block;height:28px;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;-ie-box-sizing:content-box;box-sizing:content-box;padding:4px 25px;line-height:28px;border:none;background:#FF6A6A;color:#F8F8F8!important;-webkit-transition:all 0.2s linear;-moz-transition:all 0.2s linear;-ie-transition:all 0.2s linear;transition:all 0.2s linear;text-decoration:none!important; }
.vijos .button:active {background:#FD0000;text-decoration:none!important;color:#FFF!important;}
.vijos .button:hover {background:#FF3434;text-decoration:none!important;color:#FFF!important; }
.vijos .button:visited { text-decoration:none!important;color:#F8F8F8!important; }

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