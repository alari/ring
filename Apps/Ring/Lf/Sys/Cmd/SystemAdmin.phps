<?php
class R_Lf_Sys_Cmd_SystemAdmin extends R_Lf_Sys_Command {

	public function process()
	{

		$form_processor = new O_Dao_Renderer_FormProcessor( );
		$form_processor->setActiveRecord( $this->instance );
		$form_processor->setAjaxMode();
		$form_processor->addHiddenField( "action", "process" );
		if (O_Registry::get( "app/env/request_method" ) == "POST" && $this->getParam( "action" ) == "process") {
			$form_processor->responseAjax( null, "Изменения успешно сохранены." );
			return null;
		} else {
			$tpl = $this->getTemplate();
			$tpl->form = $form_processor;
			return $tpl;
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}