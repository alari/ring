<?php
class R_Cmd_OpenId_Login extends O_OpenId_Consumer_Command {

	protected $regForm;

	public function process()
	{
		if($this->getParam("openid_action") == "register") {
			return $this->tryRegister();
		}

		if($_POST["openid_identifier"] == "OpenID") {
			return $this->redirect();
		}

		if (isset( $_POST[ "redirect" ] )) {
			$_SESSION[ "redirect" ] = $_POST[ "redirect" ];
		} elseif(!isset($_SESSION["redirect"])) {
			$_SESSION[ "redirect" ] = "/";
		}

		if (isset( $_POST[ 'openid_action' ] ) && $_POST[ 'openid_action' ] == "login" && !empty(
				$_POST[ 'openid_identifier' ] )) {

			// Process auth for our users
			$user = R_Mdl_User::getByStringId( $_POST[ "openid_identifier" ] );
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

	private function tryRegister() {
		$form = $this->getRegForm();
		if(strlen($this->getParam("pwd")) < 5) {
			$form->setFieldError("pwd", "Слишком короткий пароль");
		}
		if($this->getParam("pwd") != $this->getParam("pwd2")) {
			$form->setFieldError("pwd2", "Введённые пароли не совпадают");
		}
		if(!preg_match("#^[a-z0-9][-0-9a-z]{3,}[a-z0-9]$#i", $this->getParam("login"))) {
			$form->setFieldError("login", "Неправильный логин: должен быть не менее 5 символов, латинские буквы, цифры, знак дефиса (не первым и не последним символом)");
		} elseif(R_Mdl_User::getQuery()->test("login", $this->getParam("login"))->getFunc()) {
			$form->setFieldError("login", "Этот логин занят");
		}
		// TODO: set strong validity checker
		if(!$this->getParam("email") || !strpos($this->getParam("email"), "@")) {
			$form->setFieldError("email", "Введите правильный адрес электронной почты");
		} elseif(R_Mdl_User::getQuery()->test("email", $this->getParam("email"))->getFunc()) {
			$form->setFieldError("email", "Пользователь с таким адресом электронной почты уже существует");
		}

		if(count($form->getErrors())) {
			return $form->ajaxFailedResponse();
		}
		try {
			$user = new R_Mdl_User(null, O_Acl_Role::getByName( "Openid User" ));
			$user->login = $this->getParam("login");
			$user->email = $this->getParam("email");
			$user->setPwd($this->getParam("pwd"));
			$user->nickname = $this->getParam("login");
			$user->save();
			R_Mdl_Session::setUser($user);
		} catch(Exception $e) {
			$user->delete();
			$form->setFieldError("_", $e->getMessage());
			return $form->ajaxFailedResponse();
		}
		return $form->ajaxSucceedResponse(O_UrlBuilder::get());
	}

	/**
	 * @return O_Form_Builder
	 */
	private function getRegForm() {
		if(!$this->regForm) {
			$newForm = new O_Form_Builder(O_UrlBuilder::get(O_Registry::get("env/process_url")), "Создать новый аккаунт");
			$newForm->setInstanceId("openid-reg");
			$newForm->addHidden("openid_action", "register");
			$newForm->addRow(new O_Form_Row_String("email", "Email"));
			$newForm->addRow(new O_Form_Row_String("login", "Логин (>4 символов)"));
			$newForm->addRow(new O_Form_Row_Password("pwd", "Пароль"));
			$newForm->addRow(new O_Form_Row_Password("pwd2", "Повторите пароль"));
			$newForm->addSubmitButton("Создать аккаунт");
			$this->regForm = $newForm;
		}
		return $this->regForm;
	}

	public function getTemplate( $tpl_name = null )
	{
		$tpl = new R_Tpl_OpenId_Login( );
		$tpl->newForm = $this->getRegForm();
		return $tpl;
	}

	protected function authSuccess( Auth_OpenID_SuccessResponse $response )
	{
		$identity = $response->getDisplayIdentifier();

		$email = null;

		// Get email from AX
		include_once 'Auth/OpenID/AX.php';
		$ax = new Auth_OpenID_AX_FetchResponse();
        $obj = $ax->fromSuccessResponse($response);
        if(isset($obj->data["http://axschema.org/contact/email"])) {
        	$email = $obj->data["http://axschema.org/contact/email"];
        	if(count($email)) $email = $email[0];
        }

		$user = O_OpenId_Provider_UserPlugin::getByIdentity( $identity );
		if (!$user) {
			$user = new R_Mdl_User( $identity, O_Acl_Role::getByName( "Openid User" ) );
			if($email) {
				R_Mdl_User_EmailConfirm::ignore($user);
				$user->email_confirmed = 1;
				$user->email = $email;
				list($user->nickname,) = explode("@", $email, 2);
			}
		}

		$sreg = $this->getSRegResponse( $response );
		if (!$user->email && isset( $sreg[ 'email' ] ) && $sreg[ 'email' ]) {
			$user->email = $sreg[ 'email' ];
		}
		if (!$user->nickname && isset( $sreg[ 'nickname' ] ) && $sreg[ 'nickname' ]) {
			$user->nickname = $sreg[ 'nickname' ];
		}

		try {
			$user->save();
		} catch(PDOException $e) {
			$_SESSION["notice"] = "Этот email был использован другим пользователем.";
		}
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