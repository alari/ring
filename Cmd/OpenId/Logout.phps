<?php
class R_Cmd_OpenId_Logout extends R_Command {

	public function process()
	{
		R_Mdl_Session::delUser();
		return $this->redirect( "/" );
	}
}