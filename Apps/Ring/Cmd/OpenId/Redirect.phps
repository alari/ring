<?php
class R_Cmd_OpenId_Redirect extends R_Command {

	public function process()
	{die($_SERVER["QUERY_STRING"]);
		if (isset( $_GET[ session_name() ] )) {
			session_write_close();
			session_id( $_GET[ session_name() ] );
			setcookie( session_name(), session_id(), 0, "/" );
			session_commit();
			return $this->redirect();
		}
		$redirect = isset( $_SESSION[ "redirect" ] ) ? $_SESSION[ "redirect" ] : "/?no-redir:".$_GET[session_name()].".".session_id();
		return $this->redirect( $redirect );
	}

}