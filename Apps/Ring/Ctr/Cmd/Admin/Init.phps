<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		foreach(R_Mdl_Site::getQuery() as $site) {
			/* @var $site R_Mdl_Site */
			$site->createResource();
			foreach($site->systems as $sys) {
				/* @var $sys R_Mdl_Sys_Instance */
				$sys->createResource();
				foreach ($sys->collections as $coll) {
					/* @var $coll R_Mdl_Site_Collection */
					$coll->createResource();
				}
				foreach($sys->anonces as $a) {
					/* @var $a R_Mdl_Site_Anonce */
					$a->createResource();
				}
			}
		}
	}
}