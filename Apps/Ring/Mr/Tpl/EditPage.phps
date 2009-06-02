<?php
class R_Mr_Tpl_EditPage extends R_Template {
	public $form;
	
	public function displayContents()
	{
		$this->form->show($this->layout());
	}

}