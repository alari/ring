<?php
class R_Ctr_Cmd_Own_Msgs_Read extends R_Command {

	private $msg;

	public function process()
	{
		$this->msg->readen = 1;
		$this->msg->save();

		$tpl = $this->getTemplate();
		$tpl->msg = $this->msg;
		return $tpl;
	}

	public function isAuthenticated()
	{
		if(!R_Mdl_Session::isLogged()) return false;
		$this->msg = R_Mdl_Session::getUser()->msgs_own->test("id", O_Registry::get("app/current/msg"))->getOne();
		if(!$this->msg) return false;
		return true;
	}

}