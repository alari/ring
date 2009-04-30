<?php
class R_Ctr_Tpl_Own_Msgs_Write extends R_Ctr_Template {

	/**
	 * Form processor for site
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;

	public function displayContents()
	{
		if ($this->form) {
			$this->form->show( $this->layout() );
		}
	}
}