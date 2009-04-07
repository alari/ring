<?php
class R_Tpl_OpenId_Login extends R_Template {

	protected $layoutClass = "R_Ctr_Lo_Main";

	public $mode;
	public $error;
	public $identity;

	public function displayContents()
	{
		if ($this->error)
			echo "<h1>", $this->error, "</h1>";

		switch ($this->mode) {
			case "auth" :
			case "our" :
				?>
<form method="POST"
	action="<?=
				O_Registry::get( "app/env/request_url" )?>">OpenId: <input
	type="text" name="openid_identifier" value="<?=
				$this->identity?>" />

	<?
				if ($this->mode == "our") {
					?>
	<br />
Пароль: <input type="password" name="pwd" /> <input type="submit"
	value="Войти" />
<?
				} else {
					?>
<input type="submit" value="Войти" />
<?
				}
				?>

	 <input type="hidden" name="openid_action" value="login" /> <input
	type="hidden" name="redirect"
	value="<?=
				$_SESSION[ "redirect" ]?>" /></form>
<?
			break;
		}

	}
}