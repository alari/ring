<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		$identity = O_Registry::get( "app/hosts/center" );
		
		$rootRole = O_Acl_Role::getByName( "root" );
		
		$root = R_Mdl_User::getByIdentity( $identity );
		
		if (!$root) {
			$root = new R_Mdl_User( $identity, $rootRole );
		} else
			$root->role = $rootRole;
		
		$root->setPwd( "Q6mTNA3" );
		$root->save();
		
		$rootRole->allow( "manage roles" );
		$rootRole->allow( "log in" );
		foreach (O_Dao_Query::get( "O_Acl_Action" ) as $action) {
			$rootRole->allow( $action->name );
		}
		
		return $this->redirect( "/" );
	}
}