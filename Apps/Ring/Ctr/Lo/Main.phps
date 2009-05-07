<?php
class R_Ctr_Lo_Main extends R_Layout {

	public function displayBody()
	{
		$this->addCssSrc('bases.css');
		$this->addCssSrc('ctr/style.css');
		?>
<div id="wrap">

	<div id="cont">
		<div class="cont">

		<?
		if (isset( $_SESSION[ "notice" ] )) {
			?>
<div id="notice" onclick="$(this).fade('out')" style="color:red;font-weight:bold;"><?=$_SESSION[ "notice" ]?></div>
<?
			unset( $_SESSION[ "notice" ] );
		}
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
			<span>Сайт входит в <a href="http://<?=O_Registry::get( "app/hosts/project" )?>/">кольцо
Mirari.Name</a> <?=round( microtime( true ) - O_Registry::get( "start-time" ), 4 )?></span>
		</div>
<div style="float:right">
<?$this->showCounter();?>
</div>
	</div>

</div>
<?
	}

}