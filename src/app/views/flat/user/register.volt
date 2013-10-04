{% extends "layout.volt" %}
{% block body %}
<div class="reg-bg-container">
<div class="reg-bg-left"></div>
<div class="reg-bg-right"></div>
<div class="reg-bg">
    <div class="cont-wrap"><div class="grid_12">
    <div class="reg-form"><div class="reg-form-content">
        <h1>Register</h1>
        <div class="reg-cont">
            <script>var REG_STEP = {{ STEP|json_encode }};</script>
{% if STEP == 0 %}
            <div class="reg-step reg-step0" style="opacity:0;">
                <h2>Error</h2>
                <p>{{ ERROR|e }}</p>
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
                <script>var REG_PARAM = {{ REG_PARAM|json_encode }};</script>
                <div class="form-line"><label class="reg-label-t">Email:</label>{{ REG_MAIL }}</div>
                <div class="form-line"><label class="reg-label-t">Nickname:</label><input type="text" class="textbox role-reg-nickname" autocomplete="off" data-tip="用于显示的昵称，长度1~15字符，不能有空格"></div>
                <div class="form-line"><label class="reg-label-t">Username:</label><input type="text" class="textbox role-reg-username" autocomplete="off" data-tip="登录用户名，长度3~30字符，不能有空格"></div>
                <div class="form-line"><label class="reg-label-t">Password:</label><input type="password" class="textbox role-reg-password" autocomplete="off" style="ime-mode:disabled;" data-tip="登录密码，长度5~30字符"></div>
                <div class="form-line">
                <input type="radio" name="reg-gender" id="reg-gender-male" value="0" checked><label for="reg-gender-male" class="reg-label-input reg-label-margin">Male</label>
                <input type="radio" name="reg-gender" id="reg-gender-female" value="1"><label for="reg-gender-female" class="reg-label-input reg-label-margin">Female</label>
                <input type="radio" name="reg-gender" id="reg-gender-other" value="2"><label for="reg-gender-other" class="reg-label-input">Other</label> 
                </div>
                <div class="form-line">
                <input type="checkbox" name="reg-agree" id="reg-agree" class="role-reg-agree" data-tip="您必须同意许可协议"><label for="reg-agree" class="reg-label-input">I agree to the </label><a href="{{ BASE_PREFIX }}/help" target="_blank" class="dark reg-label-input">Vijos Terms</a>
                </div>

                <div class="form-line-big"><input type="button" class="button button-def role-reg-submit" value="Sign up!"></div>
            </div>
{% endif %}
        </div>
    </div></div>
    </div></div>
</div>
</div>
{% endblock %}
{% block footer %}
<script>$_init([{{ view_static('js/user/reg.js', false)|json_encode }}]);</script>
{% endblock %}