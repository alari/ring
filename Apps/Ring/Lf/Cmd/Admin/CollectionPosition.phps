<?php
class R_Lf_Cmd_Admin_CollectionPosition extends R_Lf_Command {

	public function process()
	{
		echo $coll = $this->getParam("coll");
		echo $position = $this->getParam("pos");
		
		$coll = $this->getSite()->collections->test("id", $coll)->getOne();
		echo "got";
		if($coll && $position) {echo "ok";
			$coll->setPosition($position);echo "tut";
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}