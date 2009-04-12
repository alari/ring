<?php
abstract class R_Lf_Command extends R_Command {
	
	private $site = false;

	/**
	 * Returns current site instance
	 *
	 * @return R_Mdl_Site
	 */
	public function getSite()
	{
		if ($this->site === false) {
			$this->site = O_Dao_Query::get( "R_Mdl_Site" )->test( "host", O_Registry::get( "app/env/http_host" ) )->getOne();
			if (!$this->site)
				throw new O_Ex_Redirect( "http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		}
		return $this->site;
	}

	public function getTemplate( $tpl = null )
	{
		$tpl = parent::getTemplate( $tpl );
		$tpl->site = $this->getSite();
		return $tpl;
	}

}