<?php

class R_Fr_Site_Collection {
	/**
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDef(O_Dao_Renderer_Show_Params $params) {
		$collection = $params->record();
		$system = $collection->system;

		switch(get_class($system->instance)) {
			case "R_Mdl_Sound":
				$files = array();
				$titles = array();
				foreach($collection->anonces as $anonce) {
					$files[] = htmlspecialchars($anonce->creative->file);
					$titles[] = htmlspecialchars($anonce->title);
				}
?>

<h1>Альбом &laquo;<?=$collection->title?>&raquo;</h1>
<div class="img">
<object type="application/x-shockwave-flash" data="<?=$params->layout()->staticUrl("swf/player_mp3_multi.swf")?>" width="400" height="300">
    <param name="movie" value="<?=$params->layout()->staticUrl("swf/player_mp3_multi.swf")?>" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=<?=join("|", $files)?>&amp;title=<?=join("|", $titles)?>&amp;width=400&amp;height=300" />
</object>

</div>
<div class="content">
<?=$collection->content?>
</div>

<?
			break;
		}

$collection->anonces->show($params->layout());
	}


}