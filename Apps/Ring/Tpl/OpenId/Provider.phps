<?php
class R_Tpl_OpenId_Provider extends R_Lf_Template {

	public $mode;
	public $error;
	public $site;

	public function displayContents()
	{
		if ($this->error)
			echo "<h1>", $this->error, "</h1>";

		switch ($this->mode) {
			case "auth" :
				?>
Идентификатор:
<a href="<?=
				R_Mdl_Session::getIdentity()?>">
<?=
				R_Mdl_Session::getIdentity()?>
</a>
<form method="post">Пароль: <input type="password" name="pwd" /><input
	type="submit" value="Login" /></form>
<?
			break;
			case "trust" :
				?>
<p>Сайт <b><a href="<?=
				htmlspecialchars( $this->site );
				?>">
<?=
				htmlspecialchars( $this->site );
				?>
</a></b> просит подтверждения, что <a
	href="<?=
				R_Mdl_Session::getIdentity()?>">
<?=
				R_Mdl_Session::getIdentity()?>
</a> &ndash; Ваш идентификатор OpenId.</p>
<form method="post"><input type="checkbox" name="forever" /> <label
	for="forever">Навсегда</label><br />
<input type="hidden" name="openid_action" value="trust"> <input
	type="submit" name="allow" value="Разрешить"> <input type="submit"
	name="deny" value="Запретить"><?foreach($_POST as $k=>$v) echo "<input type='hidden' name='$k' value='".htmlspecialchars($v)."'/></form>
<?
			break;
		}

	}
}

?>