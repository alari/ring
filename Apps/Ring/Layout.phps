<?php
class R_Layout extends O_Html_Layout {
	protected function userMenu() {
		if(R_Mdl_Session::isLogged()){
		?>
<p><b><a href="<?=R_Mdl_Session::getUser()->url()?>">Ваш профиль</a></b></p>
<ul>
<li><a href="http://<?=O_Registry::get("app/hosts/center")?>/Own/EditProfile">Редактировать профиль</a></li>
<?if(R_Mdl_Session::getUser()->site){?>
<li><a href="<?=R_Mdl_Session::getUser()->site->url()?>">Сайт <?=R_Mdl_Session::getUser()->site->title?></a></li>
<?}?>
</ul>
		<?
		}


		?>
<p><b>Админка</b></p>
<ul>
<?if(R_Mdl_Session::can("manage roles")) {?>
<li><a href="http://<?=O_Registry::get("app/hosts/center")?>/Admin/Roles">Настройки ролей</a></li>
<?}?>
<?if(R_Mdl_Session::can("manage users")) {?>
<li><a href="http://<?=O_Registry::get("app/hosts/center")?>/Admin/Users">Новый пользователь</a></li>
<?}?>
</ul>
		<?
	}

	protected function openidBox() {
?>
<div id="openid">
		<form method="POST"
			action="http://<?=O_Registry::get( "app/hosts/center" )?>/openid/login">

		<input type="text" name="openid_identifier" class="openid-blur"
			value="OpenID"
			onfocus="this.className='openid-focus';this.value=this.value=='OpenID'?'':this.value"
			onblur="this.value = this.value ? this.value : 'OpenID';if(this.value=='OpenID') this.className = 'openid-blur'"
			class="openid-blur" /> <input type="submit" value="Вход"
			id="openid-signup" /> <span>(например, логин.livejournal.com)</span>

		<input type="hidden" name="openid_action" value="login" /> <input
			type="hidden" name="redirect"
			value="http://<?=$_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ]?>" />
		</form>
		</div>
<?
	}


}