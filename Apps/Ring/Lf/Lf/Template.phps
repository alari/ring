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
			$this->site = O_Dao_Query::get( "R_Mdl_Site" )->test( "host", O_Registry::get( "app/env/http_host" ) )->getOne();
			if (!$this->site)
				throw new O_Ex_Redirect( "http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		}
		return $this->site;
	}

}