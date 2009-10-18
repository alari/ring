<?php
class R_Lf_Cmd_Admin_About extends R_Lf_Command {

	public function process()
	{
		$handler = new O_Form_Handler( );
		if (!$this->getSite()->about_page)
			$this->getSite()->about_page = new R_Mdl_Site_About( );
		$handler->setClassOrRecord($this->getSite()->about_page);
		$handler->addHidden( "action", "main-process" );
		if (O_Registry::get( "app/env/request_method" ) == "POST" && $this->getParam( "action" ) ==
			 "main-process") {
				$handler->responseAjax( 1 );
			return null;
		} else {
			$tpl = $this->getTemplate();
			$tpl->form = $handler->getForm();
			return $tpl;
		}

	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}