<?php
class R_Lf_Sys_Tpl_SystemAdmin extends R_Lf_Sys_Template {
	/**
	 * Form processor
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;

	public function displayContents()
	{
		$title = "Настройки: " . ($this->instance->title ? " &laquo;" . $this->instance->title . "&raquo;" : "");
		$this->form->setFormTitle( $title );
		$this->layout()->setTitle( $title );
		$this->form->show( $this->layout() );
		?>
<br />
<br />
<form method="post"
	onsubmit="return confirm('Вы уверены, что хотите удалить эту систему? Восстановление невозможно. Будет удалена вся связанная информация.') && prompt('Введите 12354 для подтверждения своего поступка.')=='12354'">
<fieldset><legend>Удаление системы</legend>
<center><input type="submit" value="Удалить систему" /></center>
</fieldset>
<input type="hidden" name="action" value="delete" /></form>

<?
	}

}