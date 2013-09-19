<div class="dcz-item-left float-left">
<div class="dcz-face">{% include "model/user/face.volt" %}</div>
</div>
<div class="dcz-item-right">
<div class="dcz-head">
<span class="dcz-name">{% include "model/user/name.volt" %}</span><span class="dcz-time">{{ _data_dcz['time']|datetime }}</span>
</div>
<div class="dcz-content">
{{ _data_dcz['text'] }}
</div>
</div>
<div class="clear"></div>