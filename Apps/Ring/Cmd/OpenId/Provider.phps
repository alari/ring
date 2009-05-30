<?php
class R_Cmd_OpenId_Provider extends R_Command {

	public function process()
	{
		$identity = O_Registry::get( "app/env/http_host" );
		$user = R_Mdl_User::getByIdentity( $identity );
		if (!$user || !$user->isOurUser()) {
			return $this->redirect( "/" );
		}

		// User must log in at first
		if (isset( $_GET[ "openid_action" ] ) && $_GET[ "openid_action" ] == "login" && (!R_Mdl_Session::isLogged() ||
						 R_Mdl_Session::getUser()->id != $user->id)) {
							if ($_SERVER[ 'REQUEST_METHOD' ] == "POST") {
								$user = R_Mdl_User::getByIdentity( O_Registry::get( "app/env/http_host" ) );
				if ($user && $user->login( $_POST[ "pwd" ] )) {
					unset( $_GET[ 'openid_action' ] );
					Zend_OpenId::redirect( Zend_OpenId::selfUrl(), $_GET );
					return;
				}
			}
			$tpl = $this->getTemplate();
			$tpl->mode = "auth";
			$tpl->error = "Вы должны быть авторизованы, чтобы войти на другой сайт с помощью OpenId.";
			return $tpl;
		}

		$server = new O_OpenId_Provider( );

		if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset( $_POST[ 'openid_action' ] ) && $_POST[ 'openid_action' ] ===
			 'trust') {

				if (isset( $_POST[ 'allow' ] )) {
					if (isset( $_POST[ 'forever' ] )) {
						$server->allowSite( $server->getSiteRoot( $_GET ) );
				}
				$server->respondToConsumer( $_GET );
			} else if (isset( $_POST[ 'deny' ] )) {
				if (isset( $_POST[ 'forever' ] )) {
					$server->denySite( $server->getSiteRoot( $_GET ) );
				}
				Zend_OpenId::redirect( $_GET[ 'openid_return_to' ], array ('openid.mode' => 'cancel') );
			}
		} elseif ($_SERVER[ 'REQUEST_METHOD' ] == 'GET' && isset( $_GET[ 'openid_action' ] ) && $_GET[ 'openid_action' ] ===
					 'trust') {
						$tpl = $this->getTemplate();
			$tpl->mode = "trust";
			$tpl->site = $server->getSiteRoot( $_GET );
			return $tpl;
		} else {
			$ret = $server->handle();
			if (is_string( $ret )) {
				echo $ret;
			} else if ($ret !== true) {
				header( 'HTTP/1.0 403 Forbidden' );
				echo 'Forbidden';
				exit();
			}
		}
	}
}