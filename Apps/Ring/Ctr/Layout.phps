<?php
class R_Ctr_Layout extends R_Layout {

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
		<div class="cont"><?parent::userMenu();?></div>
	</div>

	<div id="head">
		<div id="logo">&nbsp;</div>
		<div id="user-box">
	<?
		if (R_Mdl_Session::isLogged()) {
			?>
			<div class="info-box">
<p>Привет, <?=R_Mdl_Session::getUser()->link()?>!</p>
<p><a href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a></p>
			</div>
<?
		} else {
			?>
			<div class="login-box">
	<?parent::openidBox();?>
			</div>
<?
		}

		?>
		</div>
	</div>
		<div id="foot">
		<div class="cont">
			<span><?=$this->getPhrase("lo.bottom", "http://".O_Registry::get( "app/hosts/project" )."/")?></span>
		</div>
<div style="float:right">
<?$this->showCounter();?>
</div>
	</div>

</div>
<?
	}

}