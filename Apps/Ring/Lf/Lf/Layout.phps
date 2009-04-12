<?php
class R_Lf_Layout extends R_Layout {

	/**
	 * Main body
	 *
	 */
	public function displayBody()
	{
		O_Js_Middleware::getFramework()->addSrc( $this );
		$this->addCssSrc( $this->tpl->site->staticUrl( "style.css" ) );
		if (!O_Registry::get( "app/env/process_url" ) && $this->tpl->site->owner)
			$this->addHeadLink( "openid.provider", $this->tpl->site->url( "openid/provider" ) );
		$this->addJavaScriptSrc(O_Registry::get( "app/sites/static_urlbase" ) . "ring.js" );
		if (!R_Mdl_Session::isLogged())
			$this->addJavaScriptSrc(
					"http://" . O_Registry::get( "app/hosts/center" ) . "/JsLogin?ref=http://" . $this->tpl->site->host .
						 $_SERVER[ 'REQUEST_URI' ] );
		?>
<div id="header">
<div id="logo"><a href="/" id="logo"
	title="<?=$this->tpl->site->title?>">&nbsp;</a></div>
<div id="login-box"><?
		$this->loginBox()?></div>
</div>
<div id="wrapper">
<div id="main-menu"><?
		$this->mainMenu()?>
</div>
<div id="content">
<?
		if (isset( $_SESSION[ "notice" ] )) {
			?>
<div id="notice" onclick="$(this).fade('out')"><?=$_SESSION[ "notice" ]?></div>
<?
			unset( $_SESSION[ "notice" ] );
		}
		?>
<?

		$this->tpl->displayContents();
		?>
</div>
<div id="local-nav"><?=$this->tpl->displayNav()?></div>
</div>
<div id="footer"><span>&copy; <?=$this->tpl->site->copyright?></span> <span>Сайт
входит в <a href="http://<?=O_Registry::get( "app/hosts/project" )?>/">кольцо
Mirari.Name</a> <?=round( microtime( true ) - O_Registry::get( "start-time" ), 4 )?></span>
</div>
<?
		$this->setTitle( $this->title . ($this->title ? " - " : "") . $this->tpl->site->title );
	}

	/**
	 * Main menu block
	 *
	 */
	protected function mainMenu()
	{
		?>
<ul>
<?
		foreach ($this->tpl->site->getSystems() as $sys) {
			?>
<li><?=$sys->link()?></li>
<?
		}
		?>
<li><a href="/about"><?=$this->tpl->site->about?></a></li>
</ul>
<?
	}

	/**
	 * Login box / logged abilities
	 *
	 */
	protected function loginBox()
	{
		if (R_Mdl_Session::isLogged()) {
			?>
Привет,
<u><?=R_Mdl_Session::getUser()->identity?></u>
!
<a href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a>
<br />
<a href="javascript:void(0)" onclick="R.UserMenu.toggle()">Возможности</a>
<div id="user-menu"><?
			$this->userMenu()?></div>
<?
		} else {
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

	/**
	 * User menu contents
	 *
	 */
	protected function userMenu()
	{
		$can_write = R_Mdl_Session::can( "write", $this->tpl->site );
		$systems = $this->tpl->site->getSystems();
		?>
<p><b><a href="/"><?=$this->tpl->site->title?></a></b></p>
<ul><?
		foreach ($systems as $sys) {
			?>
<li><?=$sys->link() . ($can_write ? " <a href=\"" . $sys->url( "form" ) . "\" class=\"sys-add\">Добавить</a>" : "")?></li>
<?
		}
		?></ul>
<?
		if ($can_write) {
			?>
<p><b><a href="<?=$this->url( "Admin/Site" )?>">Настройки сайта</a></b></p>
<ul>
	<li><a href="<?=$this->url( "Admin/About" )?>">Страница &laquo;О
	сайте&raquo;</a></li>
	<li><a href="<?=$this->url( "Admin/Systems" )?>">Список систем</a></li>
	<li><a href="<?=$this->url( "Admin/SiteView" )?>">Редактировать
	оформление</a></li>
</ul>
<?
		}
		?>
<p><b><a href="<?=R_Mdl_Session::getUser()->url()?>">Ваш профиль</a></b></p>
<ul>
<li><a href="http://<?=O_Registry::get("app/hosts/center")?>/Own/EditProfile">Редактировать профиль</a></li>
</ul>
<?
	}

}