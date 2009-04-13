<?php
class R_Ctr_Tpl_Admin_User extends R_Ctr_Template {

	/**
	 * Form with editable user fields
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;

	/**
	 * Current user
	 *
	 * @var R_Mdl_User
	 */
	public $user;

	/**
	 * Query with available user roles
	 *
	 * @var O_Dao_Query
	 */
	public $roles;

	public function displayContents()
	{
		if ($this->user) {
			$isOur = $this->user->isOurUser();
			$role = $this->user->role;
		} else {
			$isOur = -1;
			$role = O_Acl_Role::getByName( "OpenId User" );
		}
		?>
<form method="POST">
<fieldset><legend>Основные параметры пользователя</legend>
<table>
	<tr>
		<td>OpenId:</td>
		<td>
<?
		if ($this->user)
			echo $this->user->identity;
		else {
			?>
<input type="text" name="identity" value="" /><?
		}
		?></td>
	</tr>
<?
		if ($isOur) {
			?>
<tr>
		<td>Пароль:</td>
		<td><input type="password" name="pwd" /></td>
	</tr>
<?
			if (!$this->user) {
				?><tr>
		<td colspan="2">Вводить только для наших пользователей. Автоматически
		будет создан сайт.</td>
	</tr>
<?
			} else {
				?>
<tr>
		<td>Адрес сайта:</td>
		<td><a href="<?=$this->user->url()?>"><?=$this->user->identity?></a><?
				if ($this->user->site) {
					?>&nbsp; (<a
			href="<?=$this->user->site->url("Admin/Site")?>">настройки</a>)<?
				}
				?></td>
	</tr>
<?
			}
			?>
<?
		}
		?>
<tr>
		<td>Роль:</td>
		<td><select name="role">
		<?
		foreach ($this->roles as $r) {
			?>
			<option value="<?=$r->id?>"
				<?=($r->id == $role->id ? ' selected="yes"' : "")?>><?=$r->name?></option>
		<?
		}
		?>
	</select></td>
	</tr>
	<tr>
		<th colspan="2"><input type="submit" value="Сохранить" /></th>
	</tr>
</table>
<?
		if ($this->user) {
			?><input type="hidden" name="id" value="<?=$this->user->id?>" /><input
	type="hidden" name="action" value="edit" /><?
		} else {
			?>
<input type="hidden" name="action" value="create" />
<?
		}
		?>
</fieldset>
</form>
<?

		if ($this->form) {
			$this->form->show( $this->layout() );
		}

		if ($this->user) {
			?>
<form method="post"
	onsubmit="return (confirm('Требуется подтверждение. Вся связанная с пользователем информация будет удалена!') && prompt('Введите в поле 12354')==12354)">
<fieldset><legend>Удаление пользователя</legend>
<center><input type="submit" value="Удалить" /><input type="hidden"
	name="action" value="delete" /><input type="hidden" name="id"
	value="<?=$this->user->id?>" /></center>
</fieldset>
</form><?
		}
	}
}