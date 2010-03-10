<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		exit;
		error_reporting(E_ALL);
		ini_set("display_errors", true);
		try {
		O_Db_Manager::getConnection()->query("DROP TABLE site_resources");
		}catch(PDOException $e) {
			echo "No table";
		}
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
				if(count($sys->collections))
				foreach ($sys->collections as $coll) {
					echo "<dl><dt>Coll #{$coll['id']} ".$coll->title."</dt><dd>";
					/* @var $coll R_Mdl_Site_Collection */
					$coll->createResource();
					foreach($coll->anonces as $a) {
						echo "<br/>A #{$a['id']} ".$a->title;
						/* @var $a R_Mdl_Site_Anonce */
						$a->createResource();
					}
					echo "</dd>";
				} else
				foreach($sys->anonces as $a) {
					echo "<br/>A #{$a['id']} ".$a->title;
					/* @var $a R_Mdl_Site_Anonce */
					$a->createResource();
				}
				echo "</dd>";
			}
			echo "</dd>";
		}
	}
}