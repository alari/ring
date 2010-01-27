<?php
class R_Ctr_Cmd_Admin_Comm extends R_Command {

	public function process()
	{
		if ($this->getParam( "action" ) == "create" && $this->can("create community")) {
			try {

				$host = $this->getParam( "host" );
				if(!$host) throw new Exception("Host must be specified");
				$user = R_Mdl_User::getByIdentity($this->getParam("leader"));
				if(!$user) $user = R_Mdl_Session::getUser();

				$site = new R_Mdl_Site( $host, $user, R_Mdl_Site::TYPE_COMM );
				$user->setCommFlags($site, R_Mdl_User_Relation::FLAG_IS_LEADER | R_Mdl_User_Relation::FLAG_WATCH, "Руководитель");

			}
			catch (Exception $e) {
				$this->setNotice("Error. Unable to create new site.");
				return $this->redirect( );
			}
			return $this->redirect( $site->url() );
		} elseif($this->getParam("action") == "delete" && $this->can("delete community")) {
			$host = $this->getParam( "host" );
			if(!$host) {
				$this->setNotice("Host must be specified");
				return $this->redirect();
			}
			$site = R_Mdl_Site::getByHost($host);
			if($site && $site["type"] == R_Mdl_Site::TYPE_COMM) {
				$site->delete();
				$this->setNotice("Community was deleted successfully");
			} elseif(!$site) {
				$this->setNotice("Site not found");
			} elseif($site["type"] != R_Mdl_Site::TYPE_COMM) {
				$this->setNotice("It is not a community.");
			}
			return $this->redirect();
		}

		$tpl = $this->getTemplate();
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->can( "create community" ) || $this->can("delete community");
	}

}