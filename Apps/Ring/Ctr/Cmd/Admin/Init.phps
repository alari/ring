<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		error_reporting(E_ALL);
		ini_set("display_errors", true);

		echo "A...";
/**
		foreach(R_Mdl_Site::getQuery() as $s) {
			unlink("../fl.utils.mir.io/s/".$s["id"]."/style.css");
			echo filesize("./static/s/".$s->host."/style.css")."|".filesize("../fl.utils.mir.io/s/".$s["id"]."/style.css")."|";
			copy("./static/s/".$s->host."/style.css", "../fl.utils.mir.io/s/".$s["id"]."/style.css");
			echo filesize("./static/s/".$s->host."/style.css")."|".filesize("../fl.utils.mir.io/s/".$s["id"]."/style.css")."<br/>";
		}
*/
		echo "B...";

		$rename = Array();
		foreach(O_Db_Query::get("tmp_files")->select()->fetchAll(PDO::FETCH_ASSOC) as $f){
			$rename [ $f["old_url"] ] = $f["new_url"];
		}
echo count($rename);
		echo "C...";

		$replace = function($string) use ($rename) {
			$string = $string[0];
			if(strpos($string, "static/s")) {
				list(, $string) = explode("static/s", $string, 2);
				$string = "/static/s".$string;
				if(array_key_exists($string, $rename)) {
					echo "[ ".$string." => ".$rename[$string]." ]";
					return $rename[$string];
				}
			}
			return $string;
		};

		$do_replace = function($text) use ($replace) {
			preg_replace_callback("#(http://[^/]+)?/static/s/[^\\\"'\\s\\#\\)]+#im", $replace, $text);
		};

		$d = opendir("../fl.utils.mir.io/s");
		while($f = readdir($d)) if(is_numeric($f)) {
			if(is_file("../fl.utils.mir.io/s/$f/style.css")) {
				$style = file_get_contents("../fl.utils.mir.io/s/$f/style.css");
				$style = $do_replace($style);
				echo $style, "<hr/>";
				//file_put_contents("../fl.utils.mir.io/s/$f/style.css", $style);
			}
		}

		exit;


		$d = opendir("../fl.utils.mir.io/s");

		exit;

		foreach(R_Mdl_User::getQuery() as $u) if(!$u->login) {
			$u->login = null;
			if($u->identity) {
				$login = $u->identity;
				if(strpos($login, "://")) list(,$login) = explode("://", $login, 2);
				list($login,) = explode(".", $login, 2);
				if($login) {
					$u->login = $login;
				}
			} elseif($u->email) {
				list($login,) = explode("@", $u->email, 2);
				list($login,) = explode(".", $login, 2);
				if($login) {
					$u->login = $login;
				}
			}
			$u->save();
		}

		$res = function($obj) {
			$resc = R_Mdl_Resource::getQuery()->test("content", $obj->id)->test("content_class", get_class($obj))->getFunc();
			if($resc > 1) {
				O_Db_Query::get("site_resources")->test("content", $obj->id)->test("content_class", get_class($obj))->limit($resc-1)->delete();
			}
			$r = $obj->getResource();
			if(!$r) {
				$obj->createResource();
				$r = $obj->getResource();
			}
			return $r;
		};

		$adjust = function($resource, $obj, $left_key, $level) {

			$resource->left_key = $left_key;
			$resource->level = $level;
			if($obj instanceof R_Mdl_Site_Anonce) {
				$resource->right_key = $left_key + 1;
			} elseif($obj instanceof R_Mdl_Site_Collection) {
				$resource->right_key = $left_key + 2*count($obj->anonces)+1;
			} elseif($obj instanceof R_Mdl_Sys_Instance) {
				$resource->right_key = $left_key + 2*(count($obj->anonces)+count($obj->collections))+1;
			} elseif($obj instanceof R_Mdl_Site) {
				$collections = 0;
				foreach($obj->systems as $s) $collections += count($s->collections);
				$resource->right_key = $left_key + 2*(count($obj->anonces)+count($obj->systems)+$collections)+1;
			}
			$resource->save();
		};

		foreach (R_Mdl_Site::getQuery() as $site) {
			/* @var $site R_Mdl_Site */
			$siteResource = $res($site);
			$adjust($siteResource, $site, 1, 0);
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
							$adjust($aResource, $a, $left_key++, 3);
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