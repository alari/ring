<?php
class R_Lf_Tpl_About extends R_Lf_Template {

	public function displayContents()
	{
		$this->layout()->setTitle( $this->getSite()->about );
		$this->getSite()->about_page->show( $this->layout() );
	}
}