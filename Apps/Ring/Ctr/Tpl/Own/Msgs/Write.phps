<?php
class R_Ctr_Tpl_Own_Msgs_Write extends R_Ctr_Template {

	/**
	 * Form processor for site
	 *
	 * @var O_Form_Handler
	 */
	public $form;

	public function displayContents()
	{
		if ($this->form) {
			$this->form->render( $this->layout() );
		}
	}
}