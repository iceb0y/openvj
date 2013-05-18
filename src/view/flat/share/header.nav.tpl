<div id="nav-top-wrap"><div id="nav-top"><div class="c-wrap"><div class="left">
<span class="nav-li"><a class="nav-li-a ic home" title="首页" href="/"><span class="nav-li-c"><span class="icon"></span></span></a></span>
<span class="nav-li"><a class="nav-li-a ic problems" title="题库" href="/p"><span class="nav-li-c"><span class="icon"></span></span></a></span>
<span class="nav-li"><a class="nav-li-a ic discuss" title="讨论" href="/discuss"><span class="nav-li-c"><span class="icon"></span></span></a></span>
<span class="nav-li"><a class="nav-li-a ic tests" title="比赛" href="/tests"><span class="nav-li-c"><span class="icon"></span></span></a></span>
<span class="nav-li"><a class="nav-li-a ic records" title="记录" href="/records"><span class="nav-li-c"><span class="icon"></span></span></a></span>
<span class="nav-li"><a class="nav-li-a ic skin" title="换肤" href="javascript:;" onclick="vj.ex.navShowDrop.call(this);"><span class="nav-li-c"><span class="icon"></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="downarrow"></span></span></a><div id="skinmenu" class="popform menu">
	<a class="mi" href="/skin/vj2">Vijos 2.0</a>
	<a class="mi" href="/skin/classic">经典</a>
</div></span>
</div><div class="right">{if !isset($global_uname)}
<span class="nav-li login"><a class="nav-li-a" href="javascript:;" onclick="vj.ex.navShowDrop.call(this);"><span class="nav-li-c">登录&nbsp;&nbsp;&nbsp;&nbsp;<span class="downarrow"></span></span></a><div id="loginform" class="popform role-ajax-login">
	<form action="/ajax/login" method="post">
	<div class="desc"></div>
	<div class="l"><label>用户名:</label><input type="text" name="user" class="textbox"></div>
	<div class="l"><label>密码:</label><input type="password" name="pass" class="textbox"></div>
	<div class="l sess"><input type="checkbox" class="checkbox_ap" name="session_save" value="save" id="session_save" /><label for="session_save">1个月内保持登录状态</label></div>
	<input type="hidden" name="type" value="login">
	<input type="hidden" name="redirect" value="{$smarty.server.REQUEST_URI}">
	<div class="l ls"><input type="button" class="button button-def" value="登录"><div class="placeholder"></div><a href="/user/lostpass" target="_self">忘记密码?</a></div>
	</form>
</div></span><span class="nav-li"><a class="nav-li-a" href="/user/register"><span class="nav-li-c">注册</span></a></span>
{else}
<span class="nav-li"><a class="nav-li-a usermenu" href="javascript:;" onclick="vj.ex.navShowDrop.call(this);"><span class="nav-li-c">{$global_uname}<span class="n">{$notify_c}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="downarrow"></span></span></a><div id="usermenu" class="popform menu">
	<div class="msg">当前没有新消息</div>
	<div class="split"></div>
	<a class="mi" href="/home">用户中心</a>
	<a class="mi" href="/home/settings">设置</a>
	<div class="split"></div>
	<a class="mi" href="/app/my">我的应用</a>
	<div class="split"></div>
	<a class="mi" href="/user/logout?sid={$global_sid}">登出</a>
</div></span>
{/if}</div></div></div></div>