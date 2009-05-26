<?php
class R_Lf_Cmd_Admin_CollectionPosition extends R_Lf_Command {

	public function process()
	{
		echo $coll = $this->getParam("coll");
		echo $position = $this->getParam("pos");
		
		$coll = O_Dao_ActiveRecord::getById($coll, "R_Mdl_Site_Collection");
		echo $coll ? "AGA" : "NEA";
		if($coll && $position && $coll->system->site == $this->getSite()) {
			echo "OK";
			$coll->setPosition($position);
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}