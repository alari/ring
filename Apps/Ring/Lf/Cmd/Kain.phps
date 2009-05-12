<?php
class R_Lf_Cmd_Kain extends R_Lf_Command {

	public function process()
	{
		O_Db_Manager::getConnection()->beginTransaction();
		
		try {
		
		$system = $this->getSite()->systems->test("urlbase", "poems")->getOne();
		
		$categ_r = O_Db_Query::get("kain_categories")->test("branch", "poems")
			->orderBy("order_index")->select(PDO::FETCH_OBJ);
		$categs = Array();
		$collections = Array();
		foreach($categ_r as $c) {
			$categs[$c->title_en] = $c;
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
		
		$poems_r = O_Db_Query::get("kain_poems")->test("anonce_id", 0)->select(PDO::FETCH_OBJ);
		
		foreach($poems_r as $p) {
			//poetry
			$poem = new R_Mdl_Libro_Text($system->instance);
			$poem->time = $p->date;
			$poem->anonce->time = $p->date;
			$poem->title = $p->title;
			$poem->anonce->position = $collections[$p->category]->anonces->getFunc();
			$poem->collection = $collections[$p->category];
			$content = $this->prepareText($p->text, "poetry");
			
			if($p->epigraph) {
				$content = $this->prepareText($p->epigraph, "poetry")."<br/>".$content;
			}
			
			$poem->content = $content;
			$poem->save();
			
			O_Db_Query::get("kain_poems")->test("id", $p->id)->field("anonce_id", $poem["anonce"])->update();
		}
		
		} catch(Exception $e) {
			O_Db_Manager::getConnection()->rollBack();
			
			echo $e;
			exit;
		}
			
		O_Db_Manager::getConnection()->commit();
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