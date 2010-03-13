<?php
class R_Ctr_Cmd_ConfirmEmail extends R_Command {

	public function process()
	{
		$tpl = $this->getTemplate();
		$tpl->isSucceed = R_Mdl_User_EmailConfirm::checkConfirm($this->getParam("hash"));
		return $tpl;
	}
}