<?php

class R_Ctr_Cmd_User_Profile extends R_Command {

	public function process()
	{
		$user = O_Registry::get("app/current/user");
		if(!$user) throw new O_Ex_NotFound("Пользователь не найден.");

		$tpl = $this->getTemplate();
		$tpl->user = $user;

		return $tpl;
	}
}