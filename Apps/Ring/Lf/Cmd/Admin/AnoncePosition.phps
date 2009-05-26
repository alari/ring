<?php
class R_Lf_Cmd_Admin_AnoncePosition extends R_Lf_Command {

	public function process()
	{
		$anonce = $this->getParam("anonce");
		$position = $this->getParam("pos");
		
		$anonce = $this->getSite()->anonces->test("id", $anonce)->getOne();
		if($anonce && $position) {
			$anonce->setPosition($position);
		}
	}

	public function isAuthenticated()
	{
		return $this->can( "manage site", $this->getSite() );
	}

}