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

		if(!O_Dao_TableInfo::get("R_Mdl_Site_Anonce")->tableExists()) O_Dao_TableInfo::get("R_Mdl_Site_Anonce")->createTable();

		$rootRole->allow( "manage roles" );
		$rootRole->allow( "log in" );
		foreach (O_Dao_Query::get( "O_Acl_Action" ) as $action) {
			$rootRole->allow( $action->name );
		}

		return $this->redirect( "/" );
	}
}