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
            <script>var REG_STEP = {{ STEP|json }};</script>
{% if STEP == 0 %}
            <div class="reg-step reg-step0" style="opacity:0;">
                <h2>Error</h2>
                <p>{{ ERROR|html }}</p>
                <p><a href="{{ BASE_PREFIX }}/user/register" class="role-resend dark">Enter a new email</a></p>
            </div>
{% elseif STEP == 1 %}
            <div class="reg-step reg-step1" style="opacity:0;">
                <h2>Step 1 / 2</h2>
                <div class="form-line">Please enter your Email:</div>
                <div class="form-line"><input type="text" class="textbox role-reg-email"><input type="button" class="button button-def role-reg-email-confirm" value="Confirm"></div>
                <div class="form-line reg-hint"></div>
            </div>
            <div class="reg-step1-result" style="display:none;opacity:0;">
                <h2>Step 1 / 2</h2>
                <p>Well done! An email has been sent to <span class="role-email"></span>.</p><p>Please open your inbox and confirm it.</p>
                <p><a href="javascript:;" class="role-resend dark">Enter a new email</a></p>
            </div>
{% elseif STEP == 2 %}
            <div class="reg-step reg-step2" style="opacity:0;">
                <h2>Step 2 / 2</h2>
                <script>var REG_PARAM = {{ REG_PARAM|json }};</script>
                <div class="form-line"><label>Email:</label>{{ REG_MAIL }}</div>
                <div class="form-line"><label>Username:</label><input type="text" class="textbox role-reg-username"></div>
                <div class="form-line"><label>Password:</label><input type="text" class="textbox role-reg-password"></div>
                <div class="form-line-big"><input type="button" class="button button-def role-reg-submit" value="Sign up!"></div>
            </div>
{% endif %}
        </div>
    </div></div>
    </div></div>
</div>
</div>
{% endblock %}