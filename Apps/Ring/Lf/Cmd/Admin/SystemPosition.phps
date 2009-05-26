<?php
class R_Lf_Cmd_Admin_SystemPosition extends R_Lf_Command {

	public function process()
	{
		echo "OK\n";
		echo $base = $this->getParam("base");
		echo $position = $this->getParam("pos");
		
		$sys = $this->getSite()->systems->test("urlbase", $base)->getOne();
		echo "TUT";
		if($sys && $position) {
			echo "\nwas ".$sys->position."\n";
			echo $sys->setPosition($position);
			echo "\nnow ".$sys->position;
		} else {
			if(!$sys) echo "\nsys NF";
			if(!$position) echo "\npos=0";
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}