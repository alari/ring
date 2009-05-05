<?php

class R_Ctr_Cmd_Own_Favorites extends R_Command {

	public function process()
	{
		$tpl = $this->getTemplate("Own_Friends");
		$anonces = R_Mdl_Session::getUser()->favorites->orderBy("time DESC");
		R_Mdl_Site_Anonce::setQueryAccesses($anonces);

		$tpl->paginator = $anonces->getPaginator( array ($this, "url") );
		$tpl->title = "Ваше избранное";

		return $tpl;
	}

	public function url( $page )
	{
		return O_UrlBuilder::get( "Own/Favorites" . ($page > 1 ? "-" . $page : "") );
	}

	public function isAuthenticated()
	{
		return R_Mdl_Session::isLogged();
	}
}