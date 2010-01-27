<?php
class R_Ctr_Cmd_Own_Friends_List extends R_Command {

	public function process()
	{
		$user = R_Mdl_Session::getUser();

		if ($this->isMethodPost() && $this->getParam( "friend_openid" )) {
			$friend = R_Mdl_User::getByIdentity( $this->getParam( "friend_openid" ) );
			if (!$friend)
				$friend = R_Mdl_Site::getByHost( $this->getParam( "friend_openid" ) );
			if ($friend)
				$user->addFriend( $friend );
			return $this->redirect();
		}

		if ($this->getParam( "remove" )) {
			$friend = O_Dao_ActiveRecord::getById( $this->getParam( "remove" ), "R_Mdl_User" );
			if ($friend)
				$user->removeFriend( $friend );
			return $this->redirect();
		}

		$tpl = $this->getTemplate();
		$tpl->friends = $user->friends;
		$tpl->follow = $user->{"groups.site"}->test("user_groups.type", R_Mdl_User_Group::TYPE_FOLLOWERS);
		return $tpl;

	}

	public function isAuthenticated()
	{
		return R_Mdl_Session::isLogged();
	}

}