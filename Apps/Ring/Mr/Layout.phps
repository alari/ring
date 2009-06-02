<?php
class R_Mr_Layout extends R_Layout {

	public function displayBody()
	{
		$this->addCssSrc('bases.css');
		$this->addCssSrc('ctr/style.css');
		O_Js_Middleware::getFramework()->addSrc($this);
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
		
	public function setTitle($title="") {
		parent::setTitle($title . ($title ? " - " : "") . "Кольцо творческих сайтов");
	}
	
}