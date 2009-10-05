<?php
class R_Ctr_Cmd_JsLogin extends R_Command {

	public function process()
	{
		if (!R_Mdl_Session::isLogged()) {
			return "//not logged";
		}
		$ref = $this->getParam( "ref" );
		if (!$ref) {
			return "//no ref";
		}
		$ref = parse_url( $ref );
		$ref = $ref[ "host" ];
		if (!$ref) {
			return "//no url";
		}
		if (!R_Mdl_Site::getByHost( $ref ) && $ref != O_Registry::get( "app/hosts/project" )) {
			return "//no site";
		}
		$_SESSION[ "redirect" ] = $this->getParam( "ref" );
		return "window.location.href='http://" . $ref . "/openid/redirect?" . session_name() . "=" .
				 session_id() . "';";
	}

}