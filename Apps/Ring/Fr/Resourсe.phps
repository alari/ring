<?php

class R_Fr_Resource {

	static public function loopFullCallback(O_Dao_Renderer_Show_Params $params) {
		/* @var $q O_Dao_Query */
		$q = $params->value();
		if (!$q instanceof O_Dao_Query) {
			echo "Error<br>";
			return;
		}
		foreach ($q as $res){
			$anonce = $res->getContent();
			if(!$anonce instanceof R_Mdl_Site_Anonce) {
				echo "<pre>";
				print_r($res);
				print_r($anonce);
				echo "</pre><hr/>";
				continue;
			}
			if ($anonce->isVisible()) {
				$anonce->creative->show( $params->layout(), "full" );
			}
		}
	}

	/**
	 * Shows resource own page
	 * TODO: add access checks to queries
	 * ->show_def = type(:perpage)?
	 * ->is_unit = 1|0
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showCallback(O_Dao_Renderer_Show_Params $params) {
		/* @var $r R_Mdl_Resource */
		$r = $params->record();
		// Setting layout title
		$params->layout()->setTitle($r->getPageTitle());

?><h1><?=$r->title?></h1><div id="resource"><?
		if($r->getContent()) {
			// page has its own content -- show it
			$r->getContent()->show($params->layout(), "content");
		} else {
			// It is a post unit, show all childs
			if($r->is_unit == 1) {
				$r->addQueryAccessChecks($r->getChilds(1))->show($params->layout(), "content");

			// It is a folder with several units, show paginator
			} elseif($r->show_def == "last-units") {
				list($type, $perpage) = explode(":", $r->show_def_params);
				/* @var $p O_Dao_Paginator */
				$p = $r->addQueryAccessChecks($r->getChilds())->test("is_unit", 1)->getPaginator(array($r, "getPageUrl"), $perpage);
				$p->show($params->layout(), $type);
			// It is a folder with subfolders, boxes, contents. Show one childs level
			} else {
				$r->addQueryAccessChecks($r->getChilds(1))->show($params->layout(), "on-parent-page");
			}

		}
?></div><?
	}

	static public function showContentCallback(O_Dao_Renderer_Show_Params $params) {
		;
	}

	/**
	 * Shows resource on parent folder page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showOnParentPageCallback(O_Dao_Renderer_Show_Params $params) {
		/* @var $r R_Mdl_Resource */
		$r = $params->record();
		switch($r->show_on_parent_page){
			case "box": $r->show($params->layout(), "box"); break;
			case "announce": $r->show($params->layout(), "announce"); break;
			case "content": $r->show($params->layout(), "content"); break;
		}
	}

	static public function showBoxCallback(O_Dao_Renderer_Show_Params $params) {
		/* @var $r R_Mdl_Resource */
		$r = $params->record();

		// box on resource parent page
		// 1: show post titles (limited or not, order by time or by left key)
		// 2: show one level of childs with special flag, order by left key, with current resource as context
		//    so it could be shown like content fragment, box, title
		// 3: show all childs titles, order by left key

		// lookup fields: show_box_type, show_box_layout

		// case "posts":
		// layout = (vertical|horizontal|ul|ol),(time desc|left_key),(number of posts)
		// showing method: title $layout

		// case "childs_titles":
		// layout = (vertical|horizontal|ul|ol),(depth)
		// showing method: title $layout $depth

		// case "childs_flagged":
		// layout = (vertical|horizontal)
		// showing method: parent-box $layout

		if($r->show_block_type == "posts") {
			$r->getChilds()->test("is_post", 1)->show("in-parent-box");
		} elseif($r->show_block_type == "childs") {
			$r->getChilds(1)->test("show_in_parent_box", 1)->show("in-parent-box");
		}
	}

}