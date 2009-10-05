<?php
class R_Lf_Sys_Cmd_Comments extends R_Lf_Sys_Command {

	public function process()
	{
		$tpl = $this->getTemplate();
		
		$query = $this->instance->system->{"anonces.nodes"}->orderBy( "time DESC" );
		$tpl->title = $this->instance->title;
		
		R_Mdl_Session::setQueryAccesses( $query, $this->getSite() );
		$tpl->paginator = $query->getPaginator( array ($this, "url") );
		
		$tpl->site = $this->getSite();
		return $tpl;
	}

	public function url( $page )
	{
		return $this->instance->system->url( "comments" . ($page > 1 ? "-" . $page : "") );
	}

	public function isAuthenticated()
	{
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], 
				$this->getSite() );
	}

}