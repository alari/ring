<?php
class R_Cmd_OpenId_Redirect extends R_Command {

	public function process()
	{
		if (isset( $_GET[ session_name() ] )) {
			session_id( $_GET[ session_name() ] );
			setcookie( session_name(), session_id(), 0, "/" );
			return $this->redirect("/openid/redirect");
		}
		$redirect = isset( $_SESSION[ "redirect" ] ) ? $_SESSION[ "redirect" ] : "/?no-redir";
		return $this->redirect( $redirect );
	}

}