<?php
class R_Lf_Cmd_Admin_Crossposting extends R_Lf_Command {

	public function process()
	{
		if ($this->isMethodPost()) {
			if ($this->getParam( "action" ) == "add-service") {
				$blog_url = $this->getParam( "blog_url" );
				$user = $this->getParam( "user" );
				$pwd = $this->getParam( "pwd" );
				$type = $this->getParam( "type" );
				$no_comments = $this->getParam( "no_comments" );
				$allow_advs = $this->getParam( "allow_advs" );
				$service = new R_Mdl_Site_Crosspost_Service( $this->getSite(), $blog_url, $user, 
						$pwd, $type, $no_comments, $allow_advs );
				if (!$service || !$service->id) {
					$this->setNotice( 
							"Не удалось создать новый сервис. Возможно, блог не поддерживает Atom API или его адрес введён неправильно." );
				}
				return $this->redirect();
			}
		}
		if ($this->getParam( "d" )) {
			$o = $this->getSite()->crosspost_services[ $this->getParam( "d" ) ];
			if ($o && $o->delete())
				$this->setNotice( "Сервис удалён" );
		}
		
		$tpl = $this->getTemplate();
		$tpl->services = $this->getSite()->crosspost_services;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() ) && $this->can( "crosspost", 
				$this->getSite() );
	}

}