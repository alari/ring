<?php
class R_Ctr_Lo_Main extends O_Html_Layout {

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
	background: url(<?=$this->
		staticUrl( "im/ctr/u.png" )?> ) repeat-x
}

#ul {
	display: block;
	float: left;
	height: 229px;
	width: 179px;
	background: url(<?=$this->
		staticUrl( "im/ctr/ul.png" )?> ) left top
		no-repeat;
}

#ur {
	display: block;
	float: right;
	height: 229px;
	width: 179px;
	background: url(<?=$this->
		staticUrl( "im/ctr/ur.png" )?> ) left top
		no-repeat;
}

#l {
	background: url(<?=$this->
		staticUrl( "im/ctr/l.png" )?> ) repeat-y;
	width: 179px;
}

#r {
	background: url(<?=$this->
		staticUrl( "im/ctr/r.png" )?> ) repeat-y;
	width: 179px
}

#d {
	height: 248px;
	background: url(<?=$this->
		staticUrl( "im/ctr/d.png" )?> ) repeat-x
}

#dl {
	display: block;
	float: left;
	height: 248px;
	width: 179px;
	background: url(<?=$this->
		staticUrl( "im/ctr/dl.png" )?> ) left top
		no-repeat;
}

#dr {
	display: block;
	float: right;
	height: 248px;
	width: 179px;
	background: url(<?=$this->
		staticUrl( "im/ctr/dr.png" )?> ) left top
		no-repeat;
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
		staticUrl( "im/openid.gif" )?> ) 0 2px
		no-repeat;
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
Привет, <u><?=R_Mdl_Session::getUser()->identity?></u>! <a
			href="<?=O_UrlBuilder::get( "openid/logout" )?>">Выход</a>
<?
		} else {
			?>

<center>
		<div id="openid">
		<form method="POST"
			action="http://<?=O_Registry::get( "app/hosts/center" )?>/openid/login">

		<input type="text" name="openid_identifier" class="openid-blur"
			value="OpenID"
			onfocus="this.className='openid-focus';this.value=this.value=='OpenID'?'':this.value"
			onblur="this.value = this.value ? this.value : 'OpenID';if(this.value=='OpenID') this.className = 'openid-blur'"
			class="openid-blur" /> <input type="submit" value="Вход"
			id="openid-signup" /> <span>(например, логин.livejournal.com)</span>

		<input type="hidden" name="openid_action" value="login" /> <input
			type="hidden" name="redirect"
			value="http://<?=$_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ]?>" />
		</form>
		</div>
		</center>
<?
		}
		$this->tpl->displayContents();
?>
</td>
		<td id="r">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" id="d"><span id="dl"></span> <span id="dr"></span></td>
	</tr>
</table>

<?
	}

}