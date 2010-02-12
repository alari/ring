<?php
class R_Cmd_OpenId_Redirect extends R_Command {

	public function process()
	{
		$ref = $this->getParam("ref", $_SESSION["redirect"]);
		if(!$ref) $ref = "/no-ref";
		if (isset( $_GET[ session_name() ] )) {
			session_id( $_GET[ session_name() ] );
			setcookie( session_name(), session_id(), 0, "/" );
			return $this->redirect($ref);
		}
		return $this->redirect( $ref );
	}

}