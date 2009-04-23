<?php

class R_Fr_Site_System {
	/**
	 * Shows system with several anonces on frontpage
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showSelf(O_Dao_Renderer_Show_Params $params) {
		$system = $params->record();
?>
<div class="system" style="border:1px solid red">
<h2 style="background:silver"><?=$system->link()?></h2>
<?$system->anonces->limit(5)->show($params->layout())?>
</div>
<?
	}


}