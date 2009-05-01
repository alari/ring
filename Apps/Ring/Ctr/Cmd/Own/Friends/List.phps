<?php
class R_Ctr_Cmd_Own_Friends_List extends R_Command {

	public function process()
	{
		$user = R_Mdl_Session::getUser();

		if ($this->isMethodPost() && $this->getParam( "friend_openid" )) {
			$friend = R_Mdl_User::getByIdentity( $this->getParam( "friend_openid" ) );
			if($friend) $user->friends[] = $friend;
			return $this->redirect();
		}

		if($this->getParam("remove")) {
			$friend = O_Dao_ActiveRecord::getById($this->getParam("remove"), "R_Mdl_User");
			if($friend) $user->friends->remove($friend);
		}

		$tpl = $this->getTemplate();
		$tpl->friends = $user->friends;
		return $tpl;

	}

	public function isAuthenticated()
	{
		return R_Mdl_Session::isLogged();
	}

}