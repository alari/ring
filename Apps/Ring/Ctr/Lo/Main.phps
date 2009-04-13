<?php
class R_Ctr_Lo_Main extends R_Layout {

	public function displayBody()
	{
		?>
<style type="text/css">
html,body {
	padding: 0;
	margin: 0;
	height: 100%
}

* {
	box-sizing: border-box;
	-moz-box-sizing: border-box;
}

#u {
	height: 229px;
}

#ul {
	display: block;
	float: left;
	height: 229px;
	width: 179px;
}

#ur {
	display: block;
	float: right;
	height: 229px;
	width: 179px;
}

#l {
	width: 179px;
}

#r {
	width: 179px
}

#d {
	height: 248px;
}

#dl {
	display: block;
	float: left;
	height: 248px;
	width: 179px;
}

#dr {
	display: block;
	float: right;
	height: 248px;
	width: 179px;
}

#container {
	width: 100%;
	height: 100%
}

#content {
	text-align: center;
}

.openid-focus {
	color: black
}

.openid-blur {
	color: silver;
	font-style: italic
}

#openid {
	background: url(<?=$this->

		 staticUrl("im/openid.gif") ?>     ) 0 2px no-repeat;
	height: 40px;
	width: 240px;
	white-space: nowrap;
}

#openid span {
	display: block;
	color: gray;
	font-size: x-small
}

#openid form {
	margin: 0
}
</style>
<table id="container" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3" id="u"><span id="ul"></span> <span id="ur"></span></td>
	</tr>
	<tr>
		<td id="l">&nbsp;</td>
		<td id="content">
<?
		if (R_Mdl_Session::isLogged()) {
			?>
Привет, <?=R_Mdl_Session::getUser()->link()?>! <a
			href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a>
<?
		} else {
			?>

<center>
<?parent::openidBox();?>
		</center>
<?
		}
		$this->tpl->displayContents();
		?>
</td>
		<td id="r"><?parent::userMenu()?></td>
	</tr>
	<tr>
		<td colspan="3" id="d"><span id="dl"></span> <span id="dr"></span></td>
	</tr>
</table>

<?
	}

}