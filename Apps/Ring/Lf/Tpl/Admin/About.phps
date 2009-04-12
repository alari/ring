<?php
class R_Lf_Tpl_Admin_About extends R_Lf_Template {
	
	/**
	 * Form processor for site
	 *
	 * @var O_Dao_Renderer_FormProcessor
	 */
	public $form;

	public function displayContents()
	{
		if ($this->form) {
			$this->form->setFormTitle( "Настройки страницы &laquo;О сайте&raquo;" );
			$this->form->setSubmitButtonValue( "Сохранить изменения" );
			$this->form->show( $this->layout() );
		}
	}
}