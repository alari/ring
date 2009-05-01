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
		} else {
			$form->handle();
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