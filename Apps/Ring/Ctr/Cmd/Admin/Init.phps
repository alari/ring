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

		$adjust = function($resource, $obj, $left_key = 1, $level = 0) {
			$resource->left_key = $left_key;
			$resource->level = $level;
			if($obj instanceof R_Mdl_Site_Anonce) {
				$resource->right_key = $left_key + 1;
			} elseif($obj instanceof R_Mdl_Site_Collection) {
				$resource->right_key = $left_key + 2*count($obj->anonces)+1;
			} elseif($obj instanceof R_Mdl_Sys_Instance) {
				$resource->right_key = $left_key + 2*(count($obj->anonces)+count($obj->collections));
			} elseif($obj instanceof R_Mdl_Site) {
				$collections = 0;
				foreach($obj->systems as $s) $collections += count($s->collections);
				$resource->right_key = $left_key + 2*(count($obj->anonces)+count($obj->systems)+$collections);
			}
			$resource->save();
		};

		foreach (R_Mdl_Site::getQuery() as $site) {
			/* @var $site R_Mdl_Site */
			$siteResource = $res($site);
			$adjust($siteResource, $site);
			$left_key = 2;
			foreach ($site->systems as $sys) {
				/* @var $sys R_Mdl_Sys_Instance */
				$sysResource = $res($sys);
				$adjust($sysResource, $sys, $left_key++, 1);
				if (count( $sys->collections )) {
					foreach ($sys->collections as $coll) {
						/* @var $coll R_Mdl_Site_Collection */
						$collResource = $res($coll);
						$adjust($collResource, $coll, $left_key++, 2);
						foreach ($coll->anonces as $a) {
							/* @var $a R_Mdl_Site_Anonce */
							$aResource = $res($a);
							$adjust($res, $aResource, $left_key++, 3);
							$left_key++;
						}
						$left_key++;
					}
				} else {
					foreach($sys->anonces as $a) {
						/* @var $a R_Mdl_Site_Anonce */
						$aResource = $res($a);
						$adjust($aResource, $a, $left_key++, 2);
						$left_key++;
					}
				}
				// End system
				$left_key++;
			}
		}
		echo "<hr/>";
	}
}