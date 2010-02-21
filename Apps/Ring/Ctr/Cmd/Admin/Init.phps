<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		O_Db_Manager::getConnection()->query("DROP TABLE site_resources");
		foreach(R_Mdl_Site::getQuery() as $site) {
			/* @var $site R_Mdl_Site */
			echo "<dl>";
			echo "<dt>", $site->link(), "</dt><dd>";
			$site->createResource();
			echo "Resource OK";
			foreach($site->systems as $sys) {
				echo "<dl><dt>", $sys->title, "</dt><dd>";
				/* @var $sys R_Mdl_Sys_Instance */
				$sys->createResource();
				echo "Resource OK";
				foreach ($sys->collections as $coll) {
					echo "<br/>Coll #{$coll['id']} ".$coll->title;
					/* @var $coll R_Mdl_Site_Collection */
					$coll->createResource();
				}
				foreach($sys->anonces as $a) {
					echo "<br/>A #{$a['id']} ".$a->title;
					/* @var $a R_Mdl_Site_Anonce */
					$a->createResource();
				}
			}
			echo "</dd>";
		}
	}
}