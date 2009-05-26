<?php
class R_Lf_Cmd_Admin_SystemPosition extends R_Lf_Command {

	public function process()
	{
		$base = $this->getParam("base");
		$position = $this->getParam("pos");
		
		$sys = $this->getSite()->systems->test("urlbase", $base)->getOne();
		if($sys && $position) $sys->setPosition($position);
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}