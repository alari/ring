<?php
class R_Ctr_Cmd_Own_Msgs_Write extends R_Command {

	public function process()
	{
		$formProcessor = new O_Dao_Renderer_FormProcessor( );
		$formProcessor->setClass( "R_Mdl_User_Msg" );
		$formProcessor->setCreateMode();
		$formProcessor->setAjaxMode();
		if ($formProcessor->isFormRequest()) {
			$adresate = $this->getParam( "target" );
			if ($adresate) {
				$adresate = R_Mdl_User::getByIdentity( $adresate );
				if (!$adresate)
					$adresate = new R_Mdl_User( $adresate, O_Acl_Role::getByName( "OpenId User" ) );
				O_Registry::set( "app/env/params/target", $adresate->id );
			}

			if ($formProcessor->handle()) {
				$sent = $formProcessor->getActiveRecord();
				$sent[ "box" ] = "sent";
				$sent[ "readen" ] = 1;
				if (!$sent[ "title" ])
					$sent[ "title" ] = "New Private Message";
				$sent->owner = R_Mdl_Session::getUser();
				$sent->save();

				$sent->createInboxCopy();
			}
			$formProcessor->responseAjax( null, "Ваше сообщение доставлено адресату." );
			return null;
		} else {
			$tpl = $this->getTemplate();
			$tpl->form = $formProcessor;
			return $tpl;
		}

	}

	public function isAuthenticated()
	{
		return $this->can( "write msgs" );
	}

}