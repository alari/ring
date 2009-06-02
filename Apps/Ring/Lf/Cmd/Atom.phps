<?php
class R_Lf_Cmd_Atom extends R_Lf_Command {

	public function process()
	{
		$query = $this->getSite()->anonces;
		R_Mdl_Session::setQueryAccesses($query, $this->getSite());
		$query->orderBy("id DESC")->limit(20);

		$feed = new O_Feed_Atom($query, $this->getSite()->url(), $this->getSite()->title, $this->getSite()->title);
		if(count($query)) $feed->setUpdatedDate($query->current()->time);
		$feed->show();
	}

}