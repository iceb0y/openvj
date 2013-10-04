{% extends "layout.volt" %}
{% block body %}
<div class="hello-container">
    
    <div class="hello-screen" data-id="land"><div class="cont-wrap"><div class="grid_12">
        <div class="hello-cont">
        <h1>Welcome to Vijos!</h1>
        <h3>我们会引导您进行偏好设置，并教您更好地使用功能。</h3>
        <div class="form-line-extrabig"><input type="button" class="button button-def button-big role-next" value="Next"></div>
        </div>
    </div></div></div>

    <div class="hello-screen" data-id="lang"><div class="cont-wrap"><div class="grid_12">
        <div class="hello-cont">
        <h1>Select your preferred language</h1>
        <h3>选择一个您常用的编程语言</h3>
        <div class="form-line-big">
            <div class="form-line"><input type="radio" name="hello-lang" id="hello-lang-pascal"><label for="hello-lang-pascal" class="hello-label-lang">Pascal</label></div>
            <div class="form-line"><input type="radio" name="hello-lang" id="hello-lang-c"><label for="hello-lang-c" class="hello-label-lang">C</label></div>
            <div class="form-line"><input type="radio" name="hello-lang" id="hello-lang-cpp"><label for="hello-lang-cpp" class="hello-label-lang">C++</label></div>
            <div class="form-line"><input type="radio" name="hello-lang" id="hello-lang-python2"><label for="hello-lang-python2" class="hello-label-lang">Python 2</label></div>
            <div class="form-line"><input type="radio" name="hello-lang" id="hello-lang-java"><label for="hello-lang-java" class="hello-label-lang">Java</label></div>
            <div class="form-line"><input type="radio" name="hello-lang" id="hello-lang-js"><label for="hello-lang-js" class="hello-label-lang">JavaScript</label></div>
        </div>
        <div class="form-line-extrabig"><input type="button" class="button button-def button-big role-next" value="Next"></div>
        </div>
    </div></div></div>

    <div class="hello-screen" data-id="markdown"><div class="cont-wrap"><div class="grid_12">
        <div class="hello-cont">
        <h1>A better editor</h1>
        <h3>Vijos在讨论区使用Markdown标记语言，快来试试吧</h3>
        <div class="form-line-extrabig"><input type="button" class="button button-def button-big role-next" value="Next"></div>
        </div>
    </div></div></div>

    <div class="hello-screen" data-id="signature"><div class="cont-wrap"><div class="grid_12">
        <div class="hello-cont">
        <h1>My signature</h1>
        <h3>写点儿个性化的语言，让别人更了解你</h3>
        <div class="form-line-extrabig"><input type="button" class="button button-def button-big role-next" value="Next"><input type="button" class="button button-big role-next" value="Skip"></div>
        </div>
    </div></div></div>

    <div class="hello-screen" data-id="details"><div class="cont-wrap"><div class="grid_12">
        <div class="hello-cont">
        <h1>More details</h1>
        <h3>补充完善一下您的联系方式吧 ^_^</h3>
        <div class="form-line-extrabig"><input type="button" class="button button-def button-big role-next" value="Next"><input type="button" class="button button-big role-next" value="Skip"></div>
        </div>
    </div></div></div>

    <div class="hello-screen" data-id="land-feature"><div class="cont-wrap"><div class="grid_12">
        <div class="hello-cont">
        <h1>Vijos Features</h1>
        <h3>要不看看Vijos有哪些特色功能? </h3>
        <div class="form-line-extrabig"><input type="button" class="button button-def button-big role-next" value="Start tour"><input type="button" class="button button-big role-skip" value="Skip" data-skip="end"></div>
        </div>
    </div></div></div>

    <div class="hello-screen" data-id="end"><div class="cont-wrap"><div class="grid_12">
        <div class="hello-cont">
        <h1>Well done!</h1>
        <div class="form-line-extrabig"><input type="button" class="button button-def button-big role-end" value="Goto homepage"></div>
        </div>
    </div></div></div>

</div>

<div class="hello-parallax-container">

</div>
{% endblock %}
{% block footer %}
<script>$_init(['stellar', {{ view_static('js/user/reg.js', false)|json_encode }}]);</script>
{% endblock %}