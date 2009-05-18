<?php

class R_Fr_Site_System {
	/**
	 * Shows system with several anonces on frontpage
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDef(O_Dao_Renderer_Show_Params $params) {
		$system = $params->record();
?>
<div class="system">
<h2><?=$system->link()?></h2>
<?$system->anonces->limit(5)->show($params->layout())?>
</div>
<?
	}

	static public function showSys(O_Dao_Renderer_Show_Params $params) {
		$system = $params->record();

		//switch()
	}

}