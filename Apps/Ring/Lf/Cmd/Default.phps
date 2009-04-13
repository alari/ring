<?php
class R_Lf_Cmd_Default extends R_Lf_Command {

	public function process()
	{
		$urlbase = O_Registry::get( "app/env/process_url" );
		$page = "";
		if (strpos( $urlbase, "/" ))
			list ($urlbase, $page) = explode( "/", $urlbase, 2 );
		if (!$page)
			$page = "Home";
		
		if (!$urlbase)
			return $this->redirect( "http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		
		$site = $this->getSite();
		$system = $site->systems->test( "urlbase", $urlbase )->getOne();
		if ($system) {
			return $system->handleRequest( $page );
		}
		return $this->redirect( "/" );
	}

}