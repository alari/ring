<?php
abstract class R_Lf_Template extends R_Template {
	protected $layoutClass = "R_Lf_Layout";
	public $site = false;

	public function displayNav()
	{
		echo "Naaav";
	}

	protected function getSite()
	{
		if ($this->site === false) {
			$this->site = O_Registry::get( "app/current/site" );
			if (!$this->site)
				throw new O_Ex_Redirect( "http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		}
		return $this->site;
	}

}