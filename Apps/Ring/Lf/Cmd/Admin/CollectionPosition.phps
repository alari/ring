<?php
class R_Lf_Cmd_Admin_CollectionPosition extends R_Lf_Command {

	public function process()
	{
		$coll = $this->getParam("coll");
		$position = $this->getParam("pos");
		
		$coll = $this->getSite()->{"systems.collections"}->test("id", $coll)->getOne();
		if($coll && $position) {
			$coll->setPosition($position);
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}