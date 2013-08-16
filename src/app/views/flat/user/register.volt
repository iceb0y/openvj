{% extends "layout.volt" %}
{% block body %}
{% set EXT_JS = ['js/user/reg.js': false] %}
<div class="reg-bg-container">
<div class="reg-bg-left"></div>
<div class="reg-bg-right"></div>
<div class="reg-bg">
    <div class="cont-wrap"><div class="grid_12">
    <div class="reg-form"><div class="reg-form-content">
        <h1>Register</h1>
        <div class="reg-cont">
        {% if STEP == 1 %}
            <div class="reg-step1">
                <h3>Step 1 / 2</h3>
                <div class="form-line">Please enter your Email:</div>
                <div class="form-line"><input type="text" class="textbox role-reg-email"><input type="button" class="button button-def" value="Confirm"></div>
            </div>
            <div class="reg-step1-result" style="display:none;">
                Well done! We have sent you an email.
            </div>
        {% else %}

        {% endif %}
        </div>
    </div></div>
    </div></div>
</div>
</div>
{% endblock %}