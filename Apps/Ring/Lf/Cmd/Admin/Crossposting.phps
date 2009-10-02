<?php
class R_Lf_Cmd_Admin_Crossposting extends R_Lf_Command {

	public function process()
	{
		if ($this->isMethodPost()) {
			if ($this->getParam( "action" ) == "add-service") {
				$blog_url = $this->getParam( "blog_url" );
				$user = $this->getParam( "user" );
				$pwd = $this->getParam( "pwd" );
				$service = new R_Mdl_Site_CrosspostService($this->getSite(), $blog_url, $user, $pwd);
				if(!$service) {
					$this->setNotice("Не удалось создать новый сервис. Возможно, блог не поддерживает Atom API.");
				}
				return $this->redirect();
			}
		}

		$tpl = $this->getTemplate();
		$tpl->services = $this->getSite()->crosspost_services;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}