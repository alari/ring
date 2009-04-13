<?php
class R_Layout extends O_Html_Layout {
	protected $site;

	protected function userMenu()
	{
		
		if (R_Mdl_Session::isLogged()) {
			
			$site = $this->site;
			if (!$site)
				$site = $this->tpl->site;
			if (!$site)
				$site = R_Mdl_Session::getUser()->site;
			if ($site instanceof R_Mdl_Site) {
				$can_write = R_Mdl_Session::can( "write", $site );
				$systems = $site->getSystems();
				?>
<p><b><a href="<?=$site->url()?>"><?=$site->title?></a></b></p>
<ul><?
				foreach ($systems as $sys) {
					?>
<li><?=$sys->link() . ($can_write ? " <a href=\"" . $sys->url( "form" ) . "\" class=\"sys-add\">Добавить</a>" : "")?></li>
<?
				}
				?></ul>
<?
				if (R_Mdl_Session::can( "manage site", $site )) {
					?>
<p><b><a href="<?=$site->url( "Admin/Site" )?>">Настройки сайта</a></b></p>
<ul>
	<li><a href="<?=$site->url( "Admin/Tags" )?>">Метки</a></li>
	<li><a href="<?=$site->url( "Admin/About" )?>">Страница &laquo;О
	сайте&raquo;</a></li>
	<li><a href="<?=$site->url( "Admin/Systems" )?>">Список систем</a></li>
	<li><a href="<?=$site->url( "Admin/SiteView" )?>">Редактировать
	оформление</a></li>
</ul>
<?
				}
			
			}
			
			?>
<p><b><a href="<?=R_Mdl_Session::getUser()->url()?>">Ваш профиль</a></b></p>
<ul>
	<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/EditProfile">Редактировать
	профиль</a></li>
<?
			if (R_Mdl_Session::getUser()->site) {
				?>
<li><a href="<?=R_Mdl_Session::getUser()->site->url()?>">Сайт <?=R_Mdl_Session::getUser()->site->title?></a></li>
<?
			}
			?>
</ul>
<?
		}
		
		?>
<p><b>Управление</b></p>
<ul>
<?
		if (R_Mdl_Session::can( "manage roles" )) {
			?>
<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/Admin/Roles">Настройки
	ролей</a></li>
<?
		}
		?>
<?

		if (R_Mdl_Session::can( "manage users" )) {
			?>
<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/Admin/User">Новый
	пользователь</a></li>
<?
		}
		?>
</ul>
<?
	}

	protected function openidBox()
	{
		?>
<div id="openid">
<form method="POST"
	action="http://<?=O_Registry::get( "app/hosts/center" )?>/openid/login">

<input type="text" name="openid_identifier" class="openid-blur"
	value="OpenID"
	onfocus="this.className='openid-focus';this.value=this.value=='OpenID'?'':this.value"
	onblur="this.value = this.value ? this.value : 'OpenID';if(this.value=='OpenID') this.className = 'openid-blur'"
	class="openid-blur" /> <input type="submit" value="Вход"
	id="openid-signup" /> <span>(например, логин.livejournal.com)</span> <input
	type="hidden" name="openid_action" value="login" /> <input
	type="hidden" name="redirect"
	value="http://<?=$_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ]?>" />
</form>
</div>
<?
	}

}