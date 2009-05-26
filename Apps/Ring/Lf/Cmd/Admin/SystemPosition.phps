<?php
class R_Lf_Cmd_Admin_SystemPosition extends R_Lf_Command {

	public function process()
	{
		echo "OK";
		echo $base = $this->getParam("base");
		echo $position = $this->getParam("pos");
		
		$sys = $this->getSite()->systems->test("urlbase", $base)->getOne();
		if($sys && $position) {
			echo "was ".$sys->position."\n";
			$sys->setPosition($position);
			echo "now ".$sys->position;
		} else {
			if(!$sys) echo "sys NF";
			if(!$position) echo "pos=0";
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}