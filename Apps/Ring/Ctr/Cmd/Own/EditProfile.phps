<?php
class R_Ctr_Cmd_Own_EditProfile extends R_Command {

	public function process()
	{
		/* @var $user R_Mdl_User */
		$user = R_Mdl_Session::getUser();
		$form = $user->form();

		if ($this->getParam( "action" ) == "upload-ava") {
			$user->ava_full = null;
			$user->save();

			return $this->redirect();
		} elseif ($this->getParam( "action" ) == "ch-pwd") {
			if ($user->isOurUser() && $this->getParam( "pwd" ) == $this->getParam( "pwd_reply" ) &&
						 preg_match( "#^[-_a-zA-Z0-9]{5,}$#i", $this->getParam( "pwd" ) )) {
							$user->setPwd( $this->getParam( "pwd" ) );
				$this->setNotice( "Пароль был успешно изменён." );
				return $this->redirect();
			} else
				$this->setNotice( "Ошибка при смене пароля." );
		} else {
			if($form->handle()) {
				return $this->redirect();
			}
		}

		$tpl = $this->getTemplate();
		$tpl->form = $form;
		return $tpl;

	}

	public function isAuthenticated()
	{
		return R_Mdl_Session::isLogged();
	}

}