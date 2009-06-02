<?php
class R_Mr_Layout extends R_Layout {

	public function displayBody()
	{
		$this->addCssSrc('bases.css');
		$this->addCssSrc('ctr/style.css');
		O_Js_Middleware::getFramework()->addSrc($this);
		
		// Authentication
		if (!R_Mdl_Session::isLogged())
			$this->addJavaScriptSrc(
					"http://" . O_Registry::get( "app/hosts/center" ) . "/JsLogin?ref=http://" . O_Registry::get("app/hosts/project") .
						 $_SERVER[ 'REQUEST_URI' ] );
		$this->addJavaScriptSrc( "ring.js" );
?>
<div id="wrap">

	<div id="cont">
		<div class="cont">

		<?
		$this->showNotice();
		?>

		<?$this->tpl->displayContents();?></div>
	</div>

	<div id="rcol">
		<div class="cont"><?$this->rightColumn();?></div>
	</div>

	<div id="head">
		<div id="user-box"><div class="info-box"><? $this->loginBox() ?></div></div>
	</div>
	
		<div id="foot">
		<div class="cont">
			<span>Кольцо творческих сайтов Mirari.Name | <a href="http://<?=O_Registry::get( "app/hosts/center" )?>/">Оглядеться из центра</a></span>
		</div>
<div style="float:right">
<?$this->showCounter();?>
</div>
	</div>
</div>
<?
	}
	
	public function rightColumn() {
		?>
<p><a href="/">Кольцо творческих сайтов</a></p>
<p><a href="http://<?=O_Registry::get( "app/hosts/center" )?>/">Оглядеться из центра</a></p>
<br/><br/>
		<?
		if(method_exists($this->tpl, "displayRightColumn")) $this->tpl->displayRightColumn();
	}
	
		
	public function setTitle($title="") {
		parent::setTitle($title . ($title ? " - " : "") . "Кольцо творческих сайтов");
	}
	
}