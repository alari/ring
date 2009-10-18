<?php
class R_Lf_Sys_Tpl_Form extends R_Lf_Sys_Template {
	/**
	 * Form processor
	 *
	 * @var O_Form_Handler
	 */
	public $form;
	public $isCreateMode;

	public function displayContents()
	{
		if ($this->isCreateMode) {
			$title = $this->instance->addFormTitle();
			$crosspostRow = new O_Form_Row_BoxList("crosspost", "Кросспостинг");
			$crosspostRow->setOptions($this->getSite()->crosspost_services, "blog_url");
			$crosspostRow->setMultiple();
$crosspostRow->render();
			$this->form->generate();
			$this->form->addRowAfter($crosspostRow, "tags" );
		} else {
			$title = $this->instance->editFormTitle();
		}
		$this->form->getFieldset()->setLegend(
				"<a href=\"" . $this->instance->system->url() . "\">" . $title . "</a>" );
		$this->layout()->setTitle( $title );
		$this->form->render( $this->layout() );
	}

}