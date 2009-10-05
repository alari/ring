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
		<th><a href="<?=$serv->blog_url?>" target="_blank"><?=$serv->blog_url?></a></th>
		<td><small><?=$serv->atomapi?></small></td>
		<td><a href="?d=<?=$serv->id?>"
			onclick="return confirm('Удалить сервис кросспостинга? При этом информация о всех выполненных кросспостах будет удалена.')">x</a></td>
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
	<tr>
		<td colspan="2"><input type="hidden" name="no_comments" value="0" /><input
			type="checkbox" name="no_comments" value="1" checked="checked" />
		&ndash; Запретить комментарии на кросспосты</td>
	</tr>
	<tr>
		<td colspan="2"><input type="hidden" name="allow_advs" value="0" /><input
			type="checkbox" name="allow_advs" value="1" checked="checked" />
		&ndash; Разрешить рекламу Кольца в кросспостах</td>
	</tr>
	</tr>

	<tr>
		<td colspan="2" align="right" /><input type="submit" value="Создать" /></td>
	</tr>
</table>
<input type="hidden" name="action" value="add-service" /></fieldset>
</form>
<hr />
<p><b>Кросспостинг</b> – это возможность создания идентичных записей в
разных местах одновременно. Иными словами, добавляя запись в Кольце, вы
можете автоматически создать ее на других сайтах. Подробнее смотрите <a
	href="http://mirari.name/Кросспостинг" target="_blank">здесь</a>.</p>
<?
	}

}