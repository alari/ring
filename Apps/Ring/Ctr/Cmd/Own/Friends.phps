<?php

class R_Ctr_Cmd_Own_Friends extends R_Command {

	public function process()
	{
		$tpl = $this->getTemplate();
		
		$anonces = R_Mdl_Site_Anonce::getByUserRelations( R_Mdl_Session::getUser() );
		$anonces->orderBy( "id DESC" );
		
		$tpl->paginator = $anonces->getPaginator( array ($this, "url") );
		$tpl->title = "Лента друзей";
		
		return $tpl;
	}

	public function url( $page )
	{
		return O_UrlBuilder::get( "own/friends" . ($page > 1 ? "-" . $page : "") );
	}

	public function isAuthenticated()
	{
		return R_Mdl_Session::isLogged();
	}
}