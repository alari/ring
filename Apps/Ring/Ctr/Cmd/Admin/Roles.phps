<?php
class R_Ctr_Cmd_Admin_Roles extends R_Command {

	public function process()
	{
		return O_Acl_Admin_Cmd::process( $this );
	}

	public function isAuthenticated()
	{
		return $this->can( "manage roles" );
	}

}