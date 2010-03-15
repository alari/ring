<?php
class R_Tpl_OpenId_Login extends R_Template {

	public $mode;
	public $error;
	public $identity;

	public function displayContents()
	{
		if ($this->error)
			echo "<h1>", $this->error, "</h1>";

		$ourForm = new O_Form_Builder(O_Registry::get( "env/request_url" ), "Войти в существующий аккаунт");
		$ourForm->setMethod("POST");
		$ourForm->addHidden("openid_action", "login");
		$ourForm->addHidden("redirect", @$_SESSION[ "redirect" ]);

		$ourForm->addRow(new O_Form_Row_String("openid_identifier", "Email / Логин / Наш OpenID", "", $this->identity));
		$ourForm->addRow(new O_Form_Row_Password("pwd", "Пароль"));
		$ourForm->addSubmitButton("Войти");
		$ourForm->render($this->layout());

		?>

<br/>
	<div id="notice">
		<b>Для наших авторов:</b> Из-за изменения алгоритма хранения пароля, все старые пароли были утеряны. Вам нужно получить новый. Для этого обратитесь к Алари (icq 5630024, name.alari@gmail.com, звонить тоже можно).
	</div><br/>

<form method="post">
<fieldset>
<legend>Создать новый аккаунт</legend>
</fieldset>
</form>
<br/>

	<!-- Simple OpenID Selector -->
    <form method="post" id="openid_form">
        <fieldset>
            <legend>Авторизуйтесь с помощью аккаунта на другом сайте</legend>

            <div id="openid_choice">
                <p>Кликните:</p>
                <div id="openid_btns"></div>
            </div>
            <div id="openid_input_area">
                <input id="openid_identifier" name="openid_identifier" type="text" value="http://" />
                <input id="openid_submit" type="submit" value="Sign-In"/>
            </div>
        </fieldset>
         <input type="hidden" name="openid_action" value="login" /> <input type="hidden" name="redirect" value="<?=@$_SESSION[ "redirect" ]?>" />
    </form>
    <!-- /Simple OpenID Selector -->
    <script type="text/javascript">
    	Om.use('OpenIdSelector', function(){
    		new OpenIdSelector('openid_identifier');
        });
    </script>
<?
	}
}