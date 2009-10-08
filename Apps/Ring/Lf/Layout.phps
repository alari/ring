<?php
class R_Lf_Layout extends R_Layout {
	
	protected $bodyClass;

	public function setBodyClass( $class )
	{
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
		
		$this->addCssSrc( 'bases.css' );
		$this->addCssSrc( $this->site->staticUrl( "style.css" ) );
		
		// Openid provider
		if (!O_Registry::get( "app/env/process_url" ) && $this->site->owner) {
			$this->addHeadLink( "openid2.provider openid.server", 
					"http://" . O_Registry::get( "app/env/http_host" ) . "/openid/provider" );
			Header( 
					"X-XRDS-Location: http://" . O_Registry::get( "app/env/http_host" ) . "/openid/provider/user-xrds" );
		}
		
		$this->addJavaScriptSrc( "ring.js" );
		
		// Authentication
		if (!R_Mdl_Session::isLogged())
			$this->addJavaScriptSrc( 
					"http://" . O_Registry::get( "app/hosts/center" ) . "/JsLogin?ref=http://" .
						 $this->site->host . $_SERVER[ 'REQUEST_URI' ] );
		
		if (is_file( $this->site->staticPath( "favicon.ico" ) )) {
			$this->addHeadLink( "SHORTCUT ICON", $this->site->staticUrl( "favicon.ico" ) );
		}
		
		$this->tpl->prepareMeta();
		?>
<div id="wrap"
	<?=($this->bodyClass ? " class=\"" . $this->bodyClass . "\"" : "")?>>
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
<div id="user-box">
<div class="info-box"><?
		$this->loginBox()?></div>
</div>
</div>
<div id="main-menu"><?
		$this->mainMenu();
		?></div>
<!--[if IE]><br/><br/><![endif]-->
<div id="foot">
<div class="cont"><span>&copy; <?=$this->site->copyright?></span> <span>Сайт
входит в <a href="http://<?=O_Registry::get( "app/hosts/project" )?>/">Кольцо
Mirari.Name</a></span>

<div id="bot_cnt">
<?
		$this->showCounter();
		?>
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
	 * User menu contents
	 *
	 */
	protected function userMenu()
	{
		parent::userMenu();
	}

}