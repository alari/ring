<?php
class R_Lf_Cmd_Home extends R_Lf_Command {

	public function process()
	{
		$tpl = $this->getTemplate();
		$tpl->site = $this->getSite();
		if (!$tpl->site) {
			return $this->redirect( "http://" . O_Registry::get( "app/hosts/center" ) . "/" );
		}
		return $tpl;
	}

}