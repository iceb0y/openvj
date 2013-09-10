{{ DCZ|json_encode }}
<div class="dcz-container" data-id="{{ DCZ['id']|escape_attr }}">
{% for dcz in DCZ['comment'] %}
<div class="dcz-item">
    {{ getuser(dcz['uid'])|json_encode }}
    {% set USER = getuser(dcz['uid']) %}
    {% include "model/user/face" with ['USER': USER] %}
    {% include "model/user/name" with ['USER': USER] %}
    {{ dcz['text'] }}
</div>
{% endfor %}
<div class="dcz-item">
    <div class="dcz-form">
        <div class="form-line">
            <textarea class="textbox role-dcz-input"></textarea>
        </div>
        <div class="form-line">
            <input type="button" class="button button-def role-dcz-submit" value="Submit">
        </div>
    </div>
</div>
</div>
