<?php
class R_Lf_Cmd_Admin_Site extends R_Lf_Command {

	public function process()
	{
		$formProcessor = new O_Dao_Renderer_FormProcessor( );
		$formProcessor->setActiveRecord( $this->getSite() );
		$formProcessor->setAjaxMode();
		$formProcessor->addHiddenField( "action", "main-process" );
		if (O_Registry::get( "app/env/request_method" ) == "POST" && $this->getParam( "action" ) == "main-process") {
			$formProcessor->responseAjax( 1 );
			return null;
		} else {
			$tpl = $this->getTemplate();
			$tpl->form = $formProcessor;
			return $tpl;
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}