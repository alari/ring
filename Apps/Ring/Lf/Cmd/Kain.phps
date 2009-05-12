<?php
class R_Lf_Cmd_Kain extends R_Lf_Command {

	public function process()
	{
		try {
		
		$system = $this->getSite()->systems->test("urlbase", "music")->getOne();
		//$system->collections->delete();
		//$system->anonces->delete();
		
		$alb_r = O_Db_Query::get("kain_music")->select(PDO::FETCH_OBJ);
		foreach($alb_r as $a) {
			$coll = null;
			if($a->coll_id) {
				$coll = $system->collections->test("id", $a->coll_id)->getOne();
			}
			if(!$coll) {
				$coll = new R_Mdl_Site_Collection($system);
				$coll->title = $a->title_ru;
				$coll->content = $this->prepareText($a->info);
				$coll->year = $a->year;
				$coll->time = $a->date;
				$coll->save();
				O_Db_Query::get("kain_music")->field("coll_id", $coll->id)->test("id", $a->id)->update();
			}
			$cache = $a->cache;

			$tracks = explode("{sep}", $cache);
			foreach($tracks as $tr) {
				list(, , $tr) = explode(">", $tr, 3);
				list($position, $tr) = explode(".", $tr, 2);
				list(, $tr) = explode("{root}_files/mp3/", $tr, 2);
				list($file, $tr) = explode("&quot;", $tr, 2);
				list(, $tr) = explode(">", $tr, 2);
				list($title,) = explode("<", $tr, 2);
				
				$track = $coll->anonces->test("title", $title)->getOne();
				if(!$track) {
					$track = new R_Mdl_Sound_Track($system->instance);
				}
				
				$track->time = $a->date;
				$track->anonce->time = $a->date;
				$track->anonce->owner = $this->getSite()->owner;
				$track->anonce->access = "public";
				$track->title = $title;
				$track->anonce->position = $position;
				$track->collection = $coll;
				$track->save();
				
				if(is_file("./static/s/kain-l.ru/tmp/".$file)) copy("./static/s/kain-l.ru/tmp/".$file, $track->filePath());
				$track->save();
			}
		}
		
		} catch(Exception $e) {
			
			
			echo $e;
			exit;
		}
			
		echo "ok";
	}
	
	private function prepareText($text, $class="") {
		if(!$text) return $text;
		$lines = explode("\n", $text);
		$ret = "";
		foreach($lines as $l) {
			$l = trim($l);
			$ret .= $l ? "<p".($class ? ' class="'.$class.'"' : "").">".$l."</p>" : "<br/>";
		}
		return $ret;
	}
	
	
	public function isAuthenticated() {
		return $this->getSite()->host == "kain.mirari.name";
	}
	
}