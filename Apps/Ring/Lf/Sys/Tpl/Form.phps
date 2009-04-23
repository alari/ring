<?php
class R_Lf_Sys_Tpl_Form extends R_Lf_Sys_Template {
	/**
	 * Form processor
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;
	public $isCreateMode;

	public function displayContents()
	{
		if ($this->isCreateMode) {
			$title = "Новая запись в блоге" . ($this->instance->title ? " &laquo;" . $this->instance->title . "&raquo;" : "");
		} else {
			$title = "Правка записи в блоге" . ($this->instance->title ? " &laquo;" . $this->instance->title . "&raquo;" : "");
		}
		$this->form->setFormTitle( "<a href=\"" . $this->instance->system->url() . "\">" . $title . "</a>" );
		$this->layout()->setTitle( $title );
		$this->form->show( $this->layout() );
	}

}