{% extends "layout.volt" %}
{% block body %}
<p><span class="larger">{{ "Good! You're almost done with the sign-up process!" }}</span></p>
<p style="padding:10px 0 30px 0;"><a href="{{ REG_URI|attr }}" target="_blank" class="button">{{ 'Confirm and complete signing-up'|i18n }}</a></p>
<p style="color:#888;">{{ 'Please complete the process as soon as possible for this validation email is only valid for 24 hours.'|i18n }}</p>
{% endblock %}
{% block footer %}
{% include "vendor/footer/reg.volt" %}
{% endblock %}