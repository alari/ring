<?php
class R_Ctr_Tpl_Own_EditProfile extends R_Ctr_Template {
	public $form;

	public function displayContents()
	{
		$this->layout()->setTitle( "Ваш профиль - настройки" );
		?><h1>Ваш профиль - настройки</h1><?
		
		$this->form->show( $this->layout() );
		
		?>
<form method="post" enctype="multipart/form-data">
<fieldset><legend>Фотография</legend>
<table>
	<tr>
		<td width="200" rowspan="2"><img
			src="<?=R_Mdl_Session::getUser()->ava_full?>" /></td>
		<td height="200">Вы можете закачать файл фотографии: <br />
		<input type="file" name="ava_full" /><input type="submit"
			value="Закачать" /> <input type="hidden" name="action"
			value="upload-ava" /></td>
	</tr>
	<tr>
		<td>Вы можете удалить свою фотографию. Тогда настоятельно
		рекомендуется оперативно закачать новую, иначе вместо фотографии будет
		отображаться что-то несимпатичное.<br />
		<input type="submit" value="Удалить" /></td>
	</tr>
</table>
</fieldset>
</form>
<?
		if (R_Mdl_Session::getUser()->isOurUser()) {
			?>

<form method="post" enctype="multipart/form-data">
<fieldset><legend>Сменить пароль</legend>
<table>
	<tr>
		<td>Новый пароль:</td>
		<td><input type="password" name="pwd" /></td>
	</tr>
	<tr>
		<td>Повторите пароль:</td>
		<td><input type="password" name="pwd_reply" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Сменить" /><input type="hidden"
			name="action" value="ch-pwd" /></td>
	</tr>
</table>
</fieldset>
</form>
<?}
	}

}