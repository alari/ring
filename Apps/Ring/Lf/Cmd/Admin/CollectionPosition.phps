<?php
class R_Lf_Cmd_Admin_CollectionPosition extends R_Lf_Command {

	public function process()
	{
		$coll = $this->getParam("coll");
		$position = $this->getParam("pos");
		
		$coll = O_Dao_ActiveRecord::getById($coll, "R_Mdl_Site_Collection");
		if($coll && $position && $coll->site == $this->getSite()) {
			$coll->setPosition($position);
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}