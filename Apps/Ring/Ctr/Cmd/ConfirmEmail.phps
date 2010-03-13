<?php
class R_Ctr_Cmd_ConfirmEmail extends R_Command {

	public function process()
	{
		if(R_Mdl_User_EmailConfirm::checkConfirm($this->getParam("hash"))) {
			$this->setNotice("Ваш адрес электронной почты успешно подтверждён!");
			return $this->redirect("/?confirmation:success");
		} else {
			$this->setNotice("Ошибка: неизвестный адрес e-mail.");
			return $this->redirect("/?confirmation:failure");
		}
	}
}