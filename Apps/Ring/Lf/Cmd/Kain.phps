<?php
class R_Lf_Cmd_Kain extends R_Lf_Command {

	public function process()
	{
		try {
		
		$system = $this->getSite()->systems->test("urlbase", "poems")->getOne();
		//$system->collections->delete();
		//$system->anonces->delete();
		
		$categ_r = O_Db_Query::get("kain_categories")->test("branch", "poems")
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
		
		$poems_r = O_Db_Query::get("kain_poems")->test("anonce_id", 0)->select(PDO::FETCH_OBJ);
		
		foreach($poems_r as $p) {
			//poetry
			$poem = null;
			if($p->anonce_id) {
				$poem = O_Dao_ActiveRecord::getById($p->anonce_id, "R_Mdl_Site_Anonce")->creative; 
			}
			if(!$poem) {
				echo "no poem<br/>";
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
			$content = $this->prepareText($p->text, "poetry");
			
			if($p->epigraph) {
				$content = $this->prepareText($p->epigraph, "poetry")."<br/>".$content;
			}
			
			$poem->content = $content;
			$poem->save();
			$poem->anonce->save();
			
			O_Db_Query::get("kain_poems")->test("id", $p->id)->field("anonce_id", $poem["anonce"])->update();
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