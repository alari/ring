<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		foreach (R_Mdl_Site::getQuery() as $site) {
			/* @var $site R_Mdl_Site */
			$siteResource = $site->getResource();
			foreach ($site->systems as $sys) {
				/* @var $sys R_Mdl_Sys_Instance */
				$sysResource = $sys->getResource();
				$siteResource->injectBottom( $sysResource );
				$siteResource->reload();
				$sysResource->reload();
				if (count( $sys->collections ))
					foreach ($sys->collections as $coll) {
						/* @var $coll R_Mdl_Site_Collection */
						$collResource = $coll->getResource();
						$sysResource->injectBottom( $collResource );
						$siteResource->reload();
						$sysResource->reload();
						$collResource->reload();
						foreach ($coll->anonces as $a) {
							/* @var $a R_Mdl_Site_Anonce */
							$aResource = $a->getResource();
							$collResource->injectBottom( $aResource );
							$siteResource->reload();
							$sysResource->reload();
							$collResource->reload();
						}
					}
				else {
					foreach($sys->anonces as $a) {
						/* @var $a R_Mdl_Site_Anonce */
						$aResource = $a->getResource();
						$sysResource->injectBottom( $aResource );
						$siteResource->reload();
						$sysResource->reload();
					}
				}
			}
		}
	}
}