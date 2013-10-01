{% for dcz in DCZ['comment'] %}
<div class="dcz-item">
{% set _data_user = getuser(dcz['uid']) %}
{% set _data_dcz = dcz %}
{% include "model/discussion/_content.volt" %}
{% if dcz['r']>0 %}
<div class="dcz-item-reply">
{% for subdcz in dcz['r'] %}
<div class="dcz-item dcz-item-sub">
{% set _data_user = getuser(subdcz['uid']) %}
{% set _data_dcz = subdcz %}
{% include "model/discussion/_content.volt" %}
</div>
{% endfor %}
</div>
{% endif %}
</div>
{% endfor %}