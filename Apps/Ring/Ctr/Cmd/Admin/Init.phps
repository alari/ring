<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		$user = R_Mdl_User::getByIdentity("agle.mirari.name");
		$site = $user->site;
		$system = $site->systems->test("urlbase", "photos");

		$root_url = "http://aglemusic.ru/photos/";
		$root_content = file_get_contents($root_url);
		$albums = explode("<div class='phalbum_outer2'>", $root_content);
		array_shift($albums);
		foreach($albums as $album) {
			list(, $album) = explode('<h2><a href="', $album, 2);
			list($url, $album) = explode('">', $album, 2);
			list($title, $album) = explode("</a></h2>", $album, 2);
			list(, $album) = explode("<!--", $album, 2);
			list($album,) = explode("-->", $album, 2);
			$collection = new R_Mdl_Site_Collection($system);
			$collection->title = $title;
			$collection->content = $album;
			$collection->description = strip_tags($album);
			$collection->save();
			$photos_content = file_get_contents($url);
			$photos = explode("<div class='photoouter'>", $photos_content);
			foreach($photos as $photo) {
				list(, $photo) = explode("<p>", $photo, 2);
				list($title, $photo) = explode("</p>", $photo, 2);
				list(, $photo) = explode("src='", $photo, 2);
				list($photo, ) = explode("'", $photo, 2);
				$photo = file_get_contents($photo);
				$file = tempnam(realpath(sys_get_temp_dir()), 'agle');
				$f = fopen($file, "w+");
				fwrite($f, file_get_contents($photo));
				fclose($f);
				$pic = new R_Mdl_Sys_Im_Picture($system);
				$anonce = $pic->anonce;
				$anonce->collection = $collection;
				$anonce->owner = $user;
				$anonce->title = $title;
				$anonce->save();
				$pic->img_full = new O_Image_Resizer($file);
				$pic->save();
			}
		}

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