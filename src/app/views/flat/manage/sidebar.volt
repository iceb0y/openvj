{% for li in MANAGE_MENU %}
{% if li['type'] == 'link' %}
<div><a class="manage-sidebar-a{% if CURRENT_ACTION == li['action'] %} active{% endif %}" href="{{ BASE_PREFIX }}{{ li['href']|attr }}" target="_self">{{ li['text']|html }}</a></div>
{% elseif li['type'] == 'headline' %}
<div><h4 class="manage-sidebar-headline">{{ li['text']|html }}</h4></div>
{% endif %}
{% endfor %}