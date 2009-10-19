<?php
class R_Lf_Tpl_Admin_Site extends R_Lf_Template {

	/**
	 * Form processor for site
	 *
	 * @var O_Form_Handler
	 */
	public $form;

	public function displayContents()
	{
		if ($this->form) {
			$this->form->getFieldset()->setLegend( "Настройки сайта как целого" );
			$this->form->addSubmitButton( "Сохранить изменения" );
			$this->form->render( $this->layout() );
		}
		if (R_Mdl_Session::can( "manage tech" )) {
			?>
<br />
<br />
<form method="post"
	onsubmit="return promt('Введите 12354, чтобы подтвердить переименование сайта')=='12354'">
<fieldset><legend>Смена хоста</legend>
<table>
	<tr>
		<td>Новый хост:</td>
		<td><input type="text" name="host" /></td>
	</tr>
	<tr>
		<td>Новый пароль владельца:</td>
		<td><input type="text" name="pwd" /></td>
	</tr>
	<tr>
		<th><input type="submit" value="Сменить хост" /></th>
	</tr>
</table>
<input type="hidden" name="action" value="tech:host" /></fieldset>
</form>
<?
		}
	}
}