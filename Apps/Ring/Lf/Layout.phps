<?php
class R_Lf_Layout extends R_Layout {

	protected $bodyClass;

	public function setBodyClass($class) {
		$this->bodyClass = $class;
	}


	/**
	 * Main body
	 *
	 */
	public function displayBody()
	{
		if (!$this->site)
			$this->site = O_Registry::get( "app/current/site" );

		O_Js_Middleware::getFramework()->addSrc( $this );

		$this->addCssSrc('bases.css');
		$this->addCssSrc( $this->site->staticUrl( "style.css" ) );

		if (!O_Registry::get( "app/env/process_url" ) && $this->site->owner)
			$this->addHeadLink( "openid.provider", $this->site->url( "openid/provider" ) );
		$this->addJavaScriptSrc( "ring.js" );
		if (!R_Mdl_Session::isLogged())
			$this->addJavaScriptSrc(
					"http://" . O_Registry::get( "app/hosts/center" ) . "/JsLogin?ref=http://" . $this->site->host .
						 $_SERVER[ 'REQUEST_URI' ] );

		$this->tpl->prepareMeta();
		?>
<div id="wrap<?=($this->bodyClass?" ".$this->bodyClass:"")?>">
	<div id="cont">
		<div class="cont">
<?
		$this->showNotice();
		?>
<?

		$this->tpl->displayContents();
		?>
		</div>
	</div>
	<div id="rcol">
		<div class="cont"><?=$this->tpl->displayNav()?></div>
	</div>
	<div id="head">
		<div id="logo"><a href="/" id="logo" title="<?=$this->site->title?>">&nbsp;</a></div>
		<div id="user-box"><? $this->loginBox() ?></div>
	</div>
	<div id="main-menu"><? $this->mainMenu(); ?></div><!--[if IE]><br/><br/><![endif]-->
	<div id="foot">
		<div class="cont">
			<span>&copy; <?=$this->site->copyright?></span>
			<span>Сайт входит в <a href="http://<?=O_Registry::get( "app/hosts/project" )?>/">кольцо
Mirari.Name</a> <?=round( microtime( true ) - O_Registry::get( "start-time" ), 4 )?></span>

<div style="float:right">
<?$this->showCounter();?>
</div>
		</div>
	</div>
</div>


<?
		$this->setTitle( $this->title . ($this->title ? " - " : "") . $this->site->title );
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
		foreach ($this->site->getSystems() as $sys) {
			?>
<li><?=$sys->link()?></li>
<?
		}
		?>
<li><a href="/about"><?=$this->site->about?></a></li>
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
<p>Привет,
<?=R_Mdl_Session::getUser()->link()?>! <a href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a></p>
<p><a href="javascript:void(0)" onclick="R.UserMenu.toggle()">Возможности</a></p>
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