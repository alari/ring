<?php

class R_Lf_Cmd_Feed_Friends extends R_Lf_Command {

	public function process()
	{
		$tpl = $this->getTemplate();

		$resources = R_Mdl_Resource::getUserFollowed($this->getSite()->owner)->clearOrders()->orderBy("id DESC");

		$tpl->paginator = $resources->getPaginator( array ($this, "url"), 15 );
		$tpl->title = "Лента друзей";

		return $tpl;
	}

	public function url( $page )
	{
		return O_UrlBuilder::get( "friends.feed" . ($page > 1 ? ".page-" . $page : "") );
	}

	public function isAuthenticated()
	{
		return $this->getSite()->owner;
	}
}