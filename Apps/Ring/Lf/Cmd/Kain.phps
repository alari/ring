<?php
class R_Lf_Cmd_Kain extends R_Lf_Command {

	public function process()
	{/*
		try {
		
		$system = $this->getSite()->systems->test("urlbase", "prose")->getOne();
		//$system->collections->delete();
		//$system->anonces->delete();
		
		$categ_r = O_Db_Query::get("kain_categories")->test("branch", "prose")
			->orderBy("order_index")->select(PDO::FETCH_OBJ);
		$categs = Array();
		$collections = Array();
		foreach($categ_r as $c) {
			$categs[$c->title_en] = $c;
			$coll = null;
			if($c->coll_id) {
				$coll = $system->collections->test("id", $c->coll_id)->getOne();
			}
			if(!$coll) {
				$coll = new R_Mdl_Site_Collection($system);
				$coll->title = $c->title_ru;
				$coll->content = $this->prepareText($c->info);
				$coll->time = $c->date;
				$coll->save();
				O_Db_Query::get("kain_categories")->field("coll_id", $coll->id)->test("id", $c->id)->update();
			}
			$collections[$c->title_en] = $coll;
		}
		$empty_coll = $system->collections->test("title", "(без цикла)")->getOne();
		if(!$empty_coll) {
			$empty_coll = new R_Mdl_Site_Collection($system);
			$empty_coll->title = "(без цикла)";
			$empty_coll->save();
		}
		
		$poems_r = O_Db_Query::get("kain_prose")->select(PDO::FETCH_OBJ);
		
		foreach($poems_r as $p) {
			//poetry
			$poem = null;
			if($p->anonce_id) {
				$poem = O_Dao_ActiveRecord::getById($p->anonce_id, "R_Mdl_Site_Anonce")->creative; 
			}
			if(!$poem) {
				echo "no prose<br/>";
				$poem = new R_Mdl_Libro_Text($system->instance);
			} else echo $poem->id."\n";
			$poem->time = $p->date;
			$poem->anonce->time = $p->date;
			$poem->title = $p->title;
			if(isset($collections[$p->category]) && $collections[$p->category] instanceof R_Mdl_Site_Collection) {
			$poem->anonce->position = $collections[$p->category]->anonces->getFunc();
			$poem->collection = $collections[$p->category];
			}
			else {
				$poem->anonce->position = $empty_coll->anonces->getFunc();
				$poem->collection = $empty_coll;
			}
			$poem->anonce->owner = $this->getSite()->owner;
			$content = $this->prepareText($p->text, "prose");
			
			if($p->epigraph) {
				$content = $this->prepareText($p->epigraph, "prose")."<br/>".$content;
			}
			
			$poem->content = $content;
			$poem->save();
			$poem->anonce->save();
			
			O_Db_Query::get("kain_prose")->test("id", $p->id)->field("anonce_id", $poem["anonce"])->update();
		}
		
		} catch(Exception $e) {
			
			
			echo $e;
			exit;
		}
			
		echo "ok";*/
		
		$local = "./static/s/kain-l.ru/tmp";
		if(!is_dir($local)) mkdir($local);
		
		$base = "ftp://1gb_kain-gonelit:af008dde@kain-gonelit.1gb.ru/http/_files/mp3";
		$remote = opendir($base);
		while($d = readdir($remote)) if($d != "." && $d != ".." && $d != "acss" && $d != "sunoac" && $d != "temp" && $d != "tofly" && $d != "yourgo") {
			if(is_dir("$base/$d")) {
				mkdir($local."/".$d);
				$album = opendir($base."/".$d);
				while($mp3 = readdir($album)) if($mp3 && substr($mp3, -4) == ".mp3") {
					if(is_file($local."/".$d."/".$mp3)) continue;
					copy($base."/".$d."/".$mp3, $local."/".$d."/".$mp3);
				}
			}
		}
		
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