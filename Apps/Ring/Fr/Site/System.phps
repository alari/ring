<?php

class R_Fr_Site_System {
	/**
	 * Shows system with several anonces on frontpage
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showHome(O_Dao_Renderer_Show_Params $params) {
		$system = $params->record();
?>
<div class="system">
<h2><?=$system->link()?></h2>
<?$system->anonces->limit(5)->show($params->layout())?>
</div>
<?
	}

	static public function showOwn(O_Dao_Renderer_Show_Params $params) {
		$system = $params->record();

		switch(get_class($system->instance)) {
			case "R_Mdl_Libro":
				$cycles = $system->collections;
				foreach($cycles as $cycle) {
					$anonces = $cycle->anonces;
					R_Mdl_Session::setQueryAccesses($anonces, $system->site);
					if(!count($anonces)) continue;
					?>
					<div class="cycle libro">
					<h2><?=$cycle->link()?></h2>
					<ul>
						<?foreach($anonces as $a) echo "<li>", $a->link(), "</li> ";?>
					</ul>
					</div>
					<?
				}
				break;

				case "R_Mdl_Sound":
				$albums = $system->collections;
				foreach($albums as $album) {
					$anonces = $album->anonces;
					R_Mdl_Session::setQueryAccesses($anonces, $system->site);
					if(!count($anonces)) continue;
					?>
					<div class="cycle">
					<h2><?=$album->link()?></h2>
					<ol>
						<?foreach($anonces as $a) echo "<li>", $a->link(), "</li> ";?>
					</ol>
					</div>
					<?
				}
				break;

			default:
				$query = $system->anonces;
				R_Mdl_Session::setQueryAccesses($query, $system->site);
				/* @var $query O_Dao_Query */
				$query->getPaginator(array($system->instance, "url"))->show($params->layout(), "full");
		}
	}

}