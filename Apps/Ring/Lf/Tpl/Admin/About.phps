<?php
class R_Lf_Tpl_Admin_About extends R_Lf_Template {

	/**
	 * Form processor for site
	 *
	 * @var O_Form_Generator
	 */
	public $form;

	public function displayContents()
	{
		if ($this->form) {
			$this->form->getFieldset()->setLegend( "Настройки страницы &laquo;О сайте&raquo;" );
			$this->form->render( $this->layout(), true );
		}
	}
}