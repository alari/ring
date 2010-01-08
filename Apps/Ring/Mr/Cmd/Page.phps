<?php
class R_Mr_Cmd_Page extends R_Command {

	public function process()
	{
		$page = O_Registry::get( "app/current/page" );
		
		if (!$page)
			return $this->redirect( "edit:" . O_Registry::get( "env/process_url" ) );
		
		$tpl = $this->getTemplate();
		$tpl->page = $page;
		return $tpl;
	}

}