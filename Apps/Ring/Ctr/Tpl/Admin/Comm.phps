<?php
class R_Ctr_Tpl_Admin_Comm extends R_Ctr_Template {

	public function displayContents()
	{?>
<form method="POST">
<fieldset><legend>Создание нового сообщества</legend>
<table>
	<tr>
		<td>Хост:</td>
		<td><input type="text" name="host" value="" /></td>
	</tr>

<tr>
		<td>Лидер:</td>
		<td><input type="text" name="leader" value="<?=R_Mdl_Session::getIdentity()?>" /></td>
	</tr>
	<tr>
		<th colspan="2"><input type="submit" value="Сохранить" /></th>
	</tr>
</table>
</fieldset>
</form>
<?
	}
}