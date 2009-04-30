<?php
class R_Ctr_Cmd_Own_Friends extends R_Command {

	public function process()
	{
		$user = R_Mdl_Session::getUser();

		if ($this->isMethodPost() && $this->getParam( "friend_openid" )) {
			$friend = R_Mdl_User::getByIdentity( $this->getParam( "friend_openid" ) );
			if (!$friend)
				$friend = new R_Mdl_User( $this->getParam( "friend_openid" ) );
			$user->friends[] = $friend;
			return $this->redirect();
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