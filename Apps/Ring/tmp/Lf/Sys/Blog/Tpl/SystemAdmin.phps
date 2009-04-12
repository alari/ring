<?php
class R_Lf_Sys_Blog_Tpl_SystemAdmin extends R_Lf_Sys_Blog_Template {
	/**
	 * Form processor
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;

	public function displayContents()
	{
		$title = "Настройки блога" . ($this->blog->title ? " &laquo;" . $this->blog->title . "&raquo;" : "");
		$this->form->setFormTitle( $title );
		$this->layout()->setTitle( $title );
		$this->form->show( $this->layout() );
	}

}