<?php
class R_Ctr_Lo_Main extends R_Layout {

	public function displayBody()
	{
		$this->addCssSrc('ctr/style.css');
		?>
<div id="wrap">
	
	<div id="cont">
		<div class="cont"><?$this->tpl->displayContents();?></div>
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
Привет, <?=R_Mdl_Session::getUser()->link()?>! <a
			href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a>
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
	
</div>
<?
	}

}