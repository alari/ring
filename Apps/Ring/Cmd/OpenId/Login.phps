<?php
class R_Cmd_OpenId_Login extends R_Command {

	public function process()
	{
		if (isset( $_POST[ "redirect" ] )) {
			$_SESSION[ "redirect" ] = $_POST[ "redirect" ];
		} else {
			$_SESSION[ "redirect" ] = "/";
		}

		if (isset( $_POST[ 'openid_action' ] ) && $_POST[ 'openid_action' ] == "login" && !empty(
				$_POST[ 'openid_identifier' ] )) {

			// Process auth for our users
			$user = R_Mdl_User::getByIdentity( $_POST[ "openid_identifier" ] );
			if ($user && $user->isOurUser()) {
				if ((isset( $_POST[ "pwd" ] ) && $user->can("log in") && $user->login( $_POST[ "pwd" ] )) || $user->identity == R_Mdl_Session::getIdentity()) {
					$redirect = $_SESSION[ "redirect" ];
					$url = parse_url( $redirect );
					if (isset( $url[ "host" ] ) && ($url[ "host" ] == O_Registry::get( "app/hosts/project" ) || O_Dao_Query::get(
							"R_Mdl_Site" )->test( "host", $url[ "host" ] )->getFunc())) {
						$redirect = "http://" . $url["host"] . "/openid/redirect?" . session_name() . "=" . session_id();
					}
					return $this->redirect( $redirect );
				}

				$tpl = $this->getTemplate();
				$tpl->mode = "our";
				$tpl->identity = $_POST[ "openid_identifier" ];
				if (isset( $_POST[ "pwd" ] ))
					$tpl->error = "Неверный пароль.";
				return $tpl;
			}

			// Auth for others
			$consumer = new O_OpenId_Consumer( );
			if (!$consumer->login( $_POST[ 'openid_identifier' ], null,
					"http://" . O_Registry::get( "app/env/http_host" ) . "/" )) {

				$tpl = $this->getTemplate();
				$tpl->mode = "ex_failed";
				$tpl->error = "Авторизация не удалась.";
				return $tpl;
			}
		} elseif (isset( $_GET[ 'openid_mode' ] )) {
			if ($_GET[ 'openid_mode' ] == "id_res") {
				$consumer = new O_OpenId_Consumer( );
				if ($consumer->verify( $_GET, $id )) {
					$user = R_Mdl_User::getByIdentity( $id );
					if (!$user) {
						$user = new R_Mdl_User( $id, O_Acl_Role::getByName( "Openid User" ) );
					}
					R_Mdl_Session::setUser( $user );
					return $this->redirect( $_SESSION[ "redirect" ] );
				} else {
					$tpl = $this->getTemplate();
					$tpl->mode = "ex_invalid";
					$tpl->error = "Неверный идентификатор OpenId: " . htmlspecialchars( $id ) . ".";
					return $tpl;
				}
			} else if ($_GET[ 'openid_mode' ] == "cancel") {
				$tpl = $this->getTemplate();
				$tpl->mode = "ex_cancelled";
				$tpl->error = "Авторизация отменена.";
				return $tpl;
			}
		}

		$tpl = $this->getTemplate();
		$tpl->mode = "auth";
		return $tpl;
	}

	public function getTemplate( $tpl_name = null )
	{
		return new R_Tpl_OpenId_Login( );
	}

}