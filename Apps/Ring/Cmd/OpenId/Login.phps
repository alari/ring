<?php
class R_Cmd_OpenId_Login extends O_OpenId_Consumer_Command {

	public function process()
	{
		if (isset( $_POST[ "redirect" ] )) {
			$_SESSION[ "redirect" ] = $_POST[ "redirect" ];
		} elseif(!isset($_SESSION["redirect"])) {
			$_SESSION[ "redirect" ] = "/";
		}

		if (isset( $_POST[ 'openid_action' ] ) && $_POST[ 'openid_action' ] == "login" && !empty(
				$_POST[ 'openid_identifier' ] )) {

			// Process auth for our users
			$user = R_Mdl_User::getByIdentity( $_POST[ "openid_identifier" ] );
			if ($user && $user->isOurUser()) {
				if ((isset( $_POST[ "pwd" ] ) && $user->can( "log in" ) && $user->login(
						$_POST[ "pwd" ] )) || $user->identity == R_Mdl_Session::getIdentity()) {
					return $this->successRedirect();
				}

				$tpl = $this->getTemplate();
				$tpl->mode = "our";
				$tpl->identity = $_POST[ "openid_identifier" ];
				if (isset( $_POST[ "pwd" ] ))
					$tpl->error = "Неверный пароль.";
				return $tpl;
			}

			return parent::tryAuth();
		} elseif (isset( $_GET[ 'openid_mode' ] )) {
			return parent::finishAuth();
		}

		return parent::startAuth();
	}

	public function getTemplate( $tpl_name = null )
	{
		return new R_Tpl_OpenId_Login( );
	}

	protected function authSuccess( Auth_OpenID_SuccessResponse $response )
	{
		$identity = $response->getDisplayIdentifier();
		$email = null;

		// Get email from AX
		include_once 'Auth/OpenID/AX.php';
		$ax = new Auth_OpenID_AX_FetchResponse();
        $obj = $ax->fromSuccessResponse($response);
        if(array_key_exists("http://axschema.org/contact/email", $obj->data)) {
        	$email = $obj->data["http://axschema.org/contact/email"];
        	if(count($email)) $email = $email[0];
        }

        if(strpos($identity, "://www.google.com")) {
        	if(!$email) return parent::startAuth();
        	$identity = $email;
        }

		$user = O_OpenId_Provider_UserPlugin::getByIdentity( $identity );
		if (!$user) {
			$user = new R_Mdl_User( $identity, O_Acl_Role::getByName( "Openid User" ) );
		}
		if($email) {
			$user->email = $email;
			$user->email_confirmed = 1;
			list($user->nickname,) = explode("@", $email, 2);
		}
		$sreg = $this->getSRegResponse( $response );
		if (!$user->email && isset( $sreg[ 'email' ] ) && $sreg[ 'email' ]) {
			$user->email = $sreg[ 'email' ];
		}
		if (!$user->nickname && isset( $sreg[ 'nickname' ] ) && $sreg[ 'nickname' ]) {
			$user->nickname = $sreg[ 'nickname' ];
		}
		$user->save();
		R_Mdl_Session::setUser( $user );
		return $this->successRedirect();
	}

	private function successRedirect()
	{
		$redirect = $_SESSION[ "redirect" ];
		$url = parse_url( $redirect );
		if (isset( $url[ "host" ] ) && ($url[ "host" ] == O_Registry::get( "app/hosts/project" ) ||
			 O_Dao_Query::get( "R_Mdl_Site" )->test( "host", $url[ "host" ] )->getFunc())) {
				$redirect = "http://" . $url[ "host" ] . "/openid/redirect?" . session_name() . "=" .
				 session_id()."&ref=".urlencode($redirect);
		}
		return $this->redirect( $redirect );
	}

}