<?php
class R_Layout extends O_Html_Layout {
	/**
	 * Current or relative to user site
	 *
	 * @var R_Mdl_Site
	 */
	protected $site;

	/**
	 * redefine O_Html_Layout::displayDoctype()
	 *
	 */
	protected function displayDoctype()
	{
		?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><?
	}

	protected function userMenu()
	{
		if (R_Mdl_Session::isLogged()) {

			$site = $this->site;
			if (!$site)
				$site = O_Registry::get( "app/current/site" );
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
<li><?=$sys->link() . ($can_write ? " &nbsp; <small><a href=\"" . $sys->url( "form" ) . "\">Добавить</a></small>" : "")?></li>
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

			$new_msgs = R_Mdl_Session::getUser()->msgs_own->test("readen", 0)->getFunc();

			?>
<p><b><a href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/Msgs/">Внутренняя почта</a></b></p>
<ul>
<?if(R_Mdl_Session::can("write msgs")){?><li><a href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/Msgs/Write">Написать</a></li><?}?>
<li><a href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/Msgs/">Входящие<?=($new_msgs?" <b>(+$new_msgs)</b>":"")?></a></li>
</ul>

<p><b><a href="<?=R_Mdl_Session::getUser()->url()?>">Ваш профиль</a></b></p>
<ul>
	<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/EditProfile">Редактировать
	профиль</a></li>
	<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/Friends">Друзья</a> &nbsp; <small><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/Friends/List">Кто</a></small></li>
	<li><a
		href="http://<?=O_Registry::get( "app/hosts/center" )?>/Own/Favorites">Избранное</a></li>
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

		if(R_Mdl_Session::can( "manage roles" ) || R_Mdl_Session::can( "manage users" )){
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
<?}
	}

	protected function showCounter()
	{
?>
<!--LiveInternet counter--><script type="text/javascript">document.write("<a href='http://www.liveinternet.ru/click;ring' target=_blank><img src='http://counter.yadro.ru/hit;ring?t26.1;r" + escape(document.referrer) + ((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + ";u" + escape(document.URL) +";i" + escape("Жж"+document.title.substring(0,80)) + ";" + Math.random() + "' border=0 width=88 height=15 alt='' title='LiveInternet: показано число посетителей за сегодня'><\/a>")</script><!--/LiveInternet-->
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