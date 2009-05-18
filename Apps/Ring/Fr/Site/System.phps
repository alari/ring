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
			default:
				$query = $system->anonces;
				R_Mdl_Session::setQueryAccesses($query, $system->site);
				/* @var $query O_Dao_Query */
				$query->getPaginator(array($system->instance, "url"))->show($params->layout(), "full");
		}
	}

}