<?php
class R_Ctr_Tpl_Admin_Comm extends R_Ctr_Template {

	public function displayContents()
	{
		if (R_Mdl_Session::can( "create community" )) {

			?>
<form method="POST">
<fieldset><legend>Создание нового сообщества</legend>
<table>
	<tr>
		<td>Хост:</td>
		<td><input type="text" name="host" value="" /></td>
	</tr>

	<tr>
		<td>Лидер:</td>
		<td><input type="text" name="leader"
			value="<?=R_Mdl_Session::getIdentity()?>" /></td>
	</tr>
	<tr>
		<th colspan="2"><input type="submit" value="Создать" /><input
			type="hidden" name="action" value="create" /></th>
	</tr>
</table>
</fieldset>
</form>


<?
		}
		if (R_Mdl_Session::can( "delete community" )) {
			?>
<br />
<hr />
<br />

<form method="post">
<fieldset><legend>Удаление сообщества</legend>

<table>
	<tr>
		<td>Хост:</td>
		<td><input type="text" name="host" value="" /></td>
	</tr>
	<tr>
		<th colspan="2"><input type="submit" value="Удалить" /><input
			type="hidden" name="action" value="delete" /></th>
	</tr>
</table>

</form>
</fieldset>

<?}

	}
}