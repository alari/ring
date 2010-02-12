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
			$this->site = O_Registry::get( "app/current/site" );
			if (!$this->site)
				throw new O_Ex_Redirect(
						"http://" . O_Registry::get( "app/hosts/project" ) . "/" );
		}
		return $this->site;
	}

	public function catchEx($e) {
		if ($e instanceof O_Ex_AccessDenied) {
			return parent::catchEx($e);
		}

		$tpl = new R_Lf_ErrorTpl();
		$tpl->ex = $e;
		return $tpl;
	}
}