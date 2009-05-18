<?php

class R_Fr_Site_Anonce {

	/**
	 * Shows anonces with creatives attached with them -- on tag or friends page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showFullQueryCallback( O_Dao_Renderer_Show_Params $params )
	{
		/* @var $q O_Dao_Query */
		$q = $params->value();
		if (!$q instanceof O_Dao_Query) {
			echo "Error<br>";
			return;
		}

		$q->preload( O_Dao_TableInfo::get( $q->getClass() )->getFieldInfo( "creative" )->getParam( "one-of", 1 ) );
		foreach ($q as $anonce)
			if ($anonce->isVisible()) {
				$anonce->creative->show( $params->layout(), "full" );
			}
	}

	/**
	 * Shows anonces list in tiny mode, for ex. on frontpage
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showQueryCallback( O_Dao_Renderer_Show_Params $params )
	{
		$q = $params->value();
		if (!$q instanceof O_Dao_Query) {
			echo "Error<br>";
			return;
		}

		echo "<div>";
		foreach ($q as $anonce)
			if ($anonce->isVisible()) {
				$anonce->show();
			}
		echo "<br clear='left'/></div>";
	}

	/**
	 * Shows one anonce in tiny mode, e.g. on frontpage
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDef( O_Dao_Renderer_Show_Params $params )
	{
		$record = $params->record();
		switch (O_Dao_TableInfo::get( $record )->getFieldInfo( "creative" )->getRealField( $record )) {
			case "blog_post" :
				?>
<div class="anonce">
<div><strong><?=
				$record->link()?></strong>
<?=
				$record->description?>
</div>
</div>
<?
			break;
			case "im_picture" :
				?>
<div class="anonce">
<div><strong><?=
				$record->link()?></strong>
<div class="img"><a href="<?=
				$record->url()?>"><img src="<?=
				$record->creative->img_tiny?>"
	alt="<?=
				htmlspecialchars( $record->title . " - " . $record->description )?>" /></a></div>
</div>
</div>
<?
			break;
			case "libro_text" :
				?>
<div class="anonce">
<div><strong><?=
				$record->link()?></strong>
<div class="img"><i><?=
				$record->collection->link()?></i></div>
</div>
</div>
<?
			break;
			case "sound_track" :
				?>
<div class="anonce">
<div><strong><?=
				$record->link()?></strong>
<div class="img">Альбом: <i><?=
				$record->collection->link()?></i></div>
</div>
</div>
<?
			break;
			default :
				echo "<a href=\"" . $params->record()->url() . "\">" . $params->record()->title . "</a><br/>";
		}

	}

	/**
	 * Shows RSS anonce
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showRssCallback(O_Dao_Renderer_Show_Params $params) {
		$record = $params->record();
?>
<item>
  <guid isPermaLink='true'><?=$record->url()?></guid>
  <pubDate><?=gmdate("D, d M Y H:i:s", $record->time)?> GMT</pubDate>
  <title><?=htmlspecialchars($record->title)?></title>
  <link><?=$record->url()?></link>
  <description><?=htmlspecialchars($record->description)?></description>
  <comments><?=$record->url()?></comments>
</item>
<?
	}


}