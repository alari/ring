<?php
class R_Lf_Sys_Cmd_SystemAdmin extends R_Lf_Sys_Command {

	public function process()
	{
		if ($this->isMethodPost() && $this->getParam( "action" ) == "delete") {
			$this->setNotice( "Система безвозвратно удалена." );
			$this->instance->system->delete();
			return $this->redirect( "/" );
		}

		$form_processor = $this->instance->form();
		$form_processor->setAjax();
		$form_processor->addHidden( "action", "process" );
		if (O_Registry::get( "app/env/request_method" ) == "POST" && $this->getParam( "action" ) ==
			 "process") {
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