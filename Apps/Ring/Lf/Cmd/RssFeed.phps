<?php
class R_Lf_Cmd_RssFeed extends R_Lf_Command {

	public function process()
	{
		$query = $this->getSite()->anonces;
		R_Mdl_Session::setQueryAccesses($query, $this->getSite());
		$query->orderBy("time DESC")->limit(20);

		$feed = new O_Feed_Rss($query, $this->getSite()->url(), $this->getSite()->title);
		if(count($query)) $feed->setLastBuildDate($query->current()->time);
		$feed->show();
	}

}