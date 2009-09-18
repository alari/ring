<?php

class R_Fr_Site_Anonce {

	/**
	 * Shows anonces with creatives attached with them -- on tag or friends page
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function loopFullCallback( O_Dao_Renderer_Show_Params $params )
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
	static public function loopCallback( O_Dao_Renderer_Show_Params $params )
	{
		$q = $params->value();
		if (!$q instanceof O_Dao_Query) {
			echo "Error<br>";
			return;
		}

		echo "<div class=\"associations\">";
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
	static public function showDefCallback( O_Dao_Renderer_Show_Params $params )
	{
		$record = $params->record();
		switch (O_Dao_TableInfo::get( $record )->getFieldInfo( "creative" )->getRealField( $record )) {
			case "blog_post" :
				R_Fr_Blog_Post::showAnonce($params);
			break;
			case "im_picture" :
				R_Fr_Im_Picture::showAnonce($params);
			break;
			case "libro_text" :
				R_Fr_Libro_Text::showAnonce($params);
			break;
			case "sound_track" :
				R_Fr_Sound_Track::showAnonce($params);
			break;
			case "afisha_bill" :
				R_Fr_Afisha_Bill::showAnonce($params);
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
		if(!$record->isVisible()) return;
		ob_start();
		$record->creative->show(null, "rssCont");
		$descr = ob_get_clean();
?>
<item>
  <guid isPermaLink='true'><?=$record->url()?></guid>
  <pubDate><?=gmdate("D, d M Y H:i:s", $record->time)?> GMT</pubDate>
  <title><?=htmlspecialchars($record->title)?></title>
  <link><?=$record->url()?></link>
  <description><?=htmlspecialchars($descr)?></description>
  <comments><?=$record->url()?></comments>
</item>
<?
	}

	/**
	 * Shows Atom anonce
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showAtomCallback(O_Dao_Renderer_Show_Params $params) {
		$record = $params->record();
		if(!$record->isVisible()) return;
		ob_start();
		$record->creative->show(null, "rssCont");
		$descr = ob_get_clean();
		$date = date("Y-m-d", $record->time)."T".date("H:i:s", $record->time);
?>

<entry>
       <title><?=htmlspecialchars($record->title)?></title>
       <link rel="alternate" type="text/html"
        href="<?=$record->url()?>"/>
       <id><?=$record->url()?></id>
       <updated><?=$date?></updated>
       <published><?=$date?></published>
       <author>
         <name><?=$record->owner->nickname?></name>
         <uri><?=$record->owner->url()?></uri>
       </author>
       <content type="html">
       <?=htmlspecialchars($descr)?>
       </content>
     </entry>
<?
	}


}