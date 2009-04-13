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
<?=R_Mdl_Session::getUser()->link()?>!
<a href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a>
<br />
<a href="javascript:void(0)" onclick="R.UserMenu.toggle()">Возможности</a>
<div id="user-menu"><?
			$this->userMenu()?></div>
<?
		} else {
			parent::openidBox();
		}
	}

	/**
	 * User menu contents
	 *
	 */
	protected function userMenu()
	{

		parent::userMenu();
	}

}