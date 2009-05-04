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
			$title = $this->instance->addFormTitle();
		} else {
			$title = $this->instance->editFormTitle();
		}
		$this->form->setFormTitle( "<a href=\"" . $this->instance->system->url() . "\">" . $title . "</a>" );
		$this->layout()->setTitle( $title );
		$this->form->show( $this->layout() );
	}

}