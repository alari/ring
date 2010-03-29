<?php

class R_Ctr_Cmd_User_Profile extends R_Command {

	public function process()
	{
		$user = O("*user");
		if(!$user) throw new O_Ex_PageNotFound("Пользователь не найден.");

		$tpl = $this->getTemplate();
		$tpl->user = $user;

		return $tpl;
	}
}