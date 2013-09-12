{% for li in MANAGE_MENU %}
{% if li['type'] == 'link' %}
<div><a class="manage-sidebar-a{% if CURRENT_ACTION == li['action'] %} active{% endif %}" href="{{ BASE_PREFIX }}{{ li['href']|escape_attr }}" target="_self">{{ li['text']|e }}</a></div>
{% elseif li['type'] == 'headline' %}
<div><h4 class="manage-sidebar-headline">{{ li['text']|e }}</h4></div>
{% endif %}
{% endfor %}