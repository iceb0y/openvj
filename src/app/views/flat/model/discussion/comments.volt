{% for dcz in DCZ['comment'] %}
<div class="dcz-item">
{% include "model/discussion/_content" with ['USER': getuser(dcz['uid']), 'dcz': dcz] %}
{% if dcz['r']>0 %}
<div class="dcz-item-reply">
{% for subdcz in dcz['r'] %}
<div class="dcz-item dcz-item-sub">
{% include "model/discussion/_content" with ['USER': getuser(subdcz['uid']), 'dcz': subdcz] %}
</div>
{% endfor %}
</div>
{% endif %}
</div>
{% endfor %}