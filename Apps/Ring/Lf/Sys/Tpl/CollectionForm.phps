<?php
class R_Lf_Sys_Tpl_CollectionForm extends R_Lf_Sys_Template {
	/**
	 * Form processor
	 *
	 * @var O_Form_Handler
	 */
	public $form;

	public function displayContents()
	{
		$this->form->getFieldset()->setLegend(
				"Настройки коллекции: <a href=\"" . $this->collection->url() . "\">" . $this->collection->title .
					 "</a>" );
		$this->layout()->setTitle( "Настройки коллекции: " . $this->collection->title );
		$this->form->render( $this->layout() );
	}

}