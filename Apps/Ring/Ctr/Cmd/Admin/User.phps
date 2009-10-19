<?php
class R_Ctr_Cmd_Admin_User extends R_Command {

	public function process()
	{
		/* @var $user R_Mdl_User */
		$user = null;
		if ($this->getParam( "id" )) {
			$user = O_Dao_ActiveRecord::getById( $this->getParam( "id" ), "R_Mdl_User" );
		}
		if ($user && $this->getParam( "action" ) == "edit") {
			if ($user->isOurUser() && $this->getParam( "pwd" )) {
				$user->setPwd( $this->getParam( "pwd" ) );
			}
			$role = O_Dao_ActiveRecord::getById( $this->getParam( "role" ), "O_Acl_Role" );
			if ($role)
				$user->role = $role;
			$user->save();
			return $this->redirect( O_UrlBuilder::get( "Admin/User", array ("id" => $user->id) ) );
			// Create user
		} elseif ($this->getParam( "action" ) == "create") {
			try {

				$identity = $this->getParam( "identity" );
				$role = O_Dao_ActiveRecord::getById( $this->getParam( "role" ), "O_Acl_Role" );
				$pwd = $this->getParam( "pwd" );
				O_Db_Manager::getConnection()->beginTransaction();
				$user = new R_Mdl_User( $identity, $role );
				// Our user has site
				if ($user && $pwd) {
					$user->setPwd( $pwd );
					$user->site = new R_Mdl_Site( $identity );
				}
			}
			catch (Exception $e) {
				O_Db_Manager::getConnection()->rollBack();
				if (O_Registry::get( "app/mode" ) == "debug") {
					echo $e;
					exit();
				}
				return $this->redirect( O_UrlBuilder::get( "Admin/User" ) );
			}
			O_Db_Manager::getConnection()->commit();
			return $this->redirect( O_UrlBuilder::get( "Admin/User", array ("id" => $user->id) ) );
		} elseif ($this->getParam( "action" ) == "delete") {
			$user->delete();
			return $this->redirect( O_UrlBuilder::get( "Admin/User" ) );
		}

		if ($user) {
			$form = $user->form();
			if ($this->getParam( "action" ) == "process" && $form->handle()) {
				return $this->redirect(
						O_UrlBuilder::get( "Admin/User", array ("id" => $user->id) ) );
			}
			$form->addHidden( "action", "process" );
		} else {
			$form = null;
		}

		$tpl = $this->getTemplate();
		$tpl->form = $form;
		$tpl->user = $user;
		$tpl->roles = O_Dao_Query::get( "O_Acl_Role" );
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->can( "manage users" );
	}

}