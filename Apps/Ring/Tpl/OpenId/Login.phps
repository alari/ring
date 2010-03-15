<?php
class R_Tpl_OpenId_Login extends R_Template {

	public $mode;
	public $error;
	public $identity;

	public function displayContents()
	{
		if ($this->error)
			echo "<h1>", $this->error, "</h1>";

		?>
<fieldset>
<legend>Войти или зарегистрироваться</legend>

<form method="POST" action="<?=O_Registry::get( "env/request_url" )?>">

	<label><span>Email / Логин / Наш OpenID:</span><input type="text" name="openid_identifier"	value="<?=$this->identity?>" /></label>
	<label><span>Пароль:</span> <input type="password" name="pwd" /></label>
	<label><input type="submit" value="Войти" /></label>

	<input type="hidden" name="openid_action" value="login" /><input type="hidden" name="redirect"	value="<?=@$_SESSION[ "redirect" ]?>" />
</form>
</fieldset>

<br/><br/>
	<div id="notice">
		<b>Для наших авторов:</b> Из-за изменения алгоритма хранения пароля, все старые пароли были утеряны. Вам нужно получить новый. Для этого обратитесь к Алари (icq 5630024, name.alari@gmail.com, звонить тоже можно).
	</div>

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