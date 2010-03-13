<?php
class R_Tpl_OpenId_Login extends R_Template {

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
<form method="POST" id="openid-login-form"
	action="<?=
				O_Registry::get( "env/request_url" )?>"><label><span>OpenId:</span>
<input type="text" name="openid_identifier"
	value="<?=
				$this->identity?>" /></label>

	<?
				if ($this->mode == "our") {
					?>
	<br />
<label><span>Пароль:</span> <input type="password" name="pwd" /></label>
<label><input type="submit" value="Войти" /></label>
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
				@$_SESSION[ "redirect" ]?>" /></form>
	<div class="notice">
		<b>Для наших авторов:</b> Из-за изменения алгоритма хранения пароля, все старые пароли были утеряны. Вам нужно получить новый. Для этого обратитесь к Алари (icq 5630024, name.alari@gmail.com, звонить тоже можно).
	</div>
<?
			break;
		}

	}
}