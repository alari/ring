<?php
class R_Lf_Cmd_Admin_Site extends R_Lf_Command {

	public function process()
	{
		$formProcessor = $this->getSite()->form();
		if ($this->can( "manage tech" )) {
			$formProcessor->setType( "adm" );
		}
		$formProcessor->addHidden( "action", "main-process" );
		if ($this->isMethodPost() && $this->getParam( "action" ) == "main-process") {
			$formProcessor->handle();
			$this->setNotice("Изменения сохранены");
			return $this->redirect();
		} elseif ($this->isMethodPost() && $this->getParam( "action" ) == "tech:host" && $this->can(
				"manage tech" )) {
			$host = $this->getParam( "host" );
			$pwd = $this->getParam( "pwd", "12345" );
			if ($this->getSite()->setHost( $host, $pwd )) {
				$this->setNotice(
						"Сайт был успешно переименован. Отредактируйте стили и уведомьте владельца о новом пароле!" );
				return $this->redirect( $this->getSite()->url() );
			} else {
				$this->setNotice( "Сайт не был переименован." );
				return $this->redirect();
			}
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