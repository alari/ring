<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		$identity = O_Registry::get( "app/hosts/center" );

		$rootRole = O_Acl_Role::getByName( "root" );

		O_Acl_Role::getByName( "OpenId User" );
		O_Acl_Role::getByName( "Visitor" )->setAsVisitorRole();

		$root = R_Mdl_User::getByIdentity( $identity );

		if (!$root) {
			$root = new R_Mdl_User( $identity, $rootRole );
		} else
			$root->role = $rootRole;

		$root->setPwd( O_Registry::get("app/mode") == "production" ? "XKSLzapa" :"12345" );
		$root->save();
$r = O_Db_Query::get("user_friends_to_user_friend_of")->select();
foreach($r as $f) {
	$usr = O_Dao_ActiveRecord::get($f["user_friend_of"], "R_Mdl_User");
	$obj = O_Dao_ActiveRecord::get($f["user_friends"], "R_Mdl_User");
	$usr->addFriend($obj);
}
		if(!O_Dao_TableInfo::get("R_Mdl_Site_Anonce")->tableExists()) O_Dao_TableInfo::get("R_Mdl_Site_Anonce")->createTable();
		if(!O_Dao_TableInfo::get("R_Mdl_User_Relation")->tableExists()) O_Dao_TableInfo::get("R_Mdl_User_Relation")->createTable();

		$rootRole->allow( "manage roles" );
		$rootRole->allow( "log in" );

		return $this->redirect( "/" );
	}
}