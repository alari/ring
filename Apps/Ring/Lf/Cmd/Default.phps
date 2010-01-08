<?php
class R_Lf_Cmd_Default extends R_Lf_Command {

	public function process()
	{
		$urlbase = O_Registry::get( "env/process_url" );
		$page = "";
		if (strpos( $urlbase, "/" ))
			list ($urlbase, $page) = explode( "/", $urlbase, 2 );
		if (!$page)
			$page = "Home";
		
		if (!$urlbase)
			return $this->redirect( "http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		
		$site = $this->getSite();
		
		$site->systems;
		$system = $site->systems->test( "urlbase", $urlbase )->getOne();
		if ($system) {
			$cmd = $system->instance->getCommand( $page );
			if ($cmd)
				return $cmd->run();
		}
		return $this->redirect( "/" );
	}

}