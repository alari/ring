<?php
class R_Ctr_Cmd_Admin_Comm extends R_Command {

	public function process()
	{
		if ($this->getParam( "action" ) == "create" && $this->can("create community")) {
			try {

				$host = $this->getParam( "host" );
				if(!$host) throw new Exception("Host must be specified");
				$site = new R_Mdl_Site( $host, R_Mdl_Site::TYPE_COMM );
				$user = R_Mdl_User::getByIdentity($this->getParam("leader"));
				if(!$user) $user = R_Mdl_Session::getUser();
				$user->setCommFlags($site, R_Mdl_User_Relation::FLAG_IS_LEADER, "Руководитель");

			}
			catch (Exception $e) {
				O_Db_Manager::getConnection()->rollBack();
				$this->setNotice("Error. Unable to create new site.");
				return $this->redirect( );
			}
			O_Db_Manager::getConnection()->commit();
			return $this->redirect( $site->url() );
		} elseif($this->getParam("action") == "delete" && $this->can("delete community")) {
			$host = $this->getParam( "host" );
			if(!$host) throw new Exception("Host must be specified");
			$site = R_Mdl_Site::getByHost($host);
			if($site && $site->type == R_Mdl_Site::TYPE_COMM) {
				$site->delete();
				$this->setNotice("Community was deleted successfully");
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