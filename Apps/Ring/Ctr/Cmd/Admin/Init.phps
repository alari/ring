<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", true);

		$res = function($obj) {
			$r = $obj->getResource();
			if(!$r) {
				$obj->createResource();
				$r = $obj->getResource();
			}
			return $r;
		};

		foreach (R_Mdl_Site::getQuery() as $site) {
			/* @var $site R_Mdl_Site */
			$siteResource = $res($site);
			foreach ($site->systems as $sys) {
				/* @var $sys R_Mdl_Sys_Instance */
				$sysResource = $res($sys);
				$siteResource->injectBottom( $sysResource );
				$siteResource->reload();
				$sysResource->reload();
				if (count( $sys->collections ))
					foreach ($sys->collections as $coll) {
						/* @var $coll R_Mdl_Site_Collection */
						$collResource = $res($coll);
						$sysResource->injectBottom( $collResource );
						$siteResource->reload();
						$sysResource->reload();
						$collResource->reload();
						foreach ($coll->anonces as $a) {
							/* @var $a R_Mdl_Site_Anonce */
							$aResource = $res($a);
							$collResource->injectBottom( $aResource );
							$siteResource->reload();
							$sysResource->reload();
							$collResource->reload();
						}
					}
				else {
					foreach($sys->anonces as $a) {
						/* @var $a R_Mdl_Site_Anonce */
						$aResource = $res($a);
						$sysResource->injectBottom( $aResource );
						$siteResource->reload();
						$sysResource->reload();
					}
				}
			}
		}
		echo "<hr/>";
	}
}