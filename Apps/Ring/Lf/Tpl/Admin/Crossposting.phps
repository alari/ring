<?php
class R_Lf_Tpl_Admin_Crossposting extends R_Lf_Template {

	public $services;

	public function displayContents()
	{
		?>
<fieldset><legend>Кросспостинг &ndash; настройки AtomAPI сервисов</legend>
<table width="100%">
	<?
		foreach ($this->services as $serv) {
			?>
<tr>
		<th><a href="<?=$serv->blog_url?>"><?=$serv->blog_url?></a></th>
		<td><small><?=$serv->atomapi?></small></td>
	</tr>
	<?
		}
		?>
</table>
</fieldset>

<form method="post">
<fieldset><legend>Добавить сервис</legend>
<table>
	<tr>
		<td>Адрес блога:</td>
		<td><input type="text" name="blog_url" /></td>
	</tr>
	<tr>
		<td>Имя пользователя:</td>
		<td><input type="text" name="user" /></td>
	</tr>
	<tr>
		<td>Пароль:</td>
		<td><input type="password" name="pwd" /></td>
	</tr>
	</tr>

	<tr>
		<td colspan="2" align="right" /><input type="submit"
			value="Создать" /></td>
	</tr>
</table>
<input type="hidden" name="action" value="add-service" /></fieldset>
</form>
<?
	}

}