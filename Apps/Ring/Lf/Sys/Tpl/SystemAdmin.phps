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
		$title = "Настройки блога" . ($this->instance->title ? " &laquo;" . $this->instance->title . "&raquo;" : "");
		$this->form->setFormTitle( $title );
		$this->layout()->setTitle( $title );
		$this->form->show( $this->layout() );
	}

}