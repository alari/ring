<?php
class R_Lf_Sys_Cmd_RssFeed extends R_Lf_Sys_Command {

	public function process()
	{
		$query = $this->instance->system->anonces;
		R_Mdl_Session::setQueryAccesses( $query, $this->getSite() );
		$query->orderBy( "time DESC" )->limit( 15 );
		
		$feed = new O_Feed_Rss( $query, $this->instance->url(), $this->instance->system->title );
		if (count( $query ))
			$feed->setLastBuildDate( $query->current()->time );
		$feed->show();
	}

	public function isAuthenticated()
	{
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], 
				$this->getSite() );
	}

}