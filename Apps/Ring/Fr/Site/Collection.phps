<?php

class R_Fr_Site_Collection {

	/**
	 * Shows page of collection -- album, cycle, gallery
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showDefCallback( O_Dao_Renderer_Show_Params $params )
	{
		$collection = $params->record();
		$system = $collection->system;
		
		switch (get_class( $system->instance )) {
			case "R_Mdl_Sys_Sound_System" :
				$files = array ();
				$titles = array ();
				foreach ($collection->anonces as $anonce)
					if ($anonce->isVisible()) {
						$files[] = htmlspecialchars( $anonce->creative->file );
						$titles[] = htmlspecialchars( 
								str_replace( "&", " and ", $anonce->title ) );
					}
				?>

<h1><?=
				$collection->title?></h1>

<div class="audioalbum">

<?
				if ($collection->year) {
					?><div class="year">Год: <?=$collection->year?></div><?
				}
				?>

<div class="audioplayer"><object type="application/x-shockwave-flash"
	data="<?=
				$params->layout()->staticUrl( "swf/player_mp3_multi.swf" )?>"
	width="400" height="300">
	<param name="movie"
		value="<?=
				$params->layout()->staticUrl( "swf/player_mp3_multi.swf" )?>" />
	<param name="bgcolor" value="#ffffff" />
	<param name="FlashVars"
		value="mp3=<?=
				join( "|", $files )?>&amp;title=<?=
				join( "|", $titles )?>&amp;width=400&amp;height=300&amp;showvolume=1&amp;volumewidth=40" />
</object></div>
<div class="tracklist">
<ol>
<?
				foreach ($collection->anonces as $anonce)
					if ($anonce->isVisible())
						echo "<li id=\"anonceid-" . $anonce->id . "\" title=\"", htmlspecialchars( 
								$anonce->description ), "\">", $anonce->link(), "</li> ";
				?>
</ol>
</div>
<?
				if (R_Mdl_Session::can( "manage site", $collection->system->site )) {
					?>
<script type="text/javascript">
R.Anonce.setSortable(".tracklist ol", null, '<?=$system->site->host?>');
$$('.tracklist ol li').addClass('move-me');
</script>
<?
				}
				?>

<div class="content">
<?=
				$collection->content?>
</div>
</div>
<?
			break;
			
			case "R_Mdl_Sys_Libro_System" :
				?>
<h1><?=
				$collection->title?></h1>
<div class="content">
<?=
				$collection->content?>
</div>
<div id="anonce-sort">
<ul class="anonces">
<?
				foreach ($collection->anonces as $anonce)
					if ($anonce->isVisible())
						echo "<li id=\"anonceid-" . $anonce->id . "\" title=\"", htmlspecialchars( 
								$anonce->description ), "\">", $anonce->link(), "</li>";
				?>
</ul>
</div>
<?
				if (R_Mdl_Session::can( "manage site", $collection->system->site )) {
					?>
<script type="text/javascript">
R.Anonce.setSortable("#anonce-sort ul", null, '<?=$system->site->host?>');
$$('#anonce-sort li').addClass('move-me');
</script>
<?
				}
				?>
				<?
			break;
			
			case "R_Mdl_Sys_Im_System" :
				?>
<h1><?=
				$collection->title?></h1>
<div class="content">
<?=
				$collection->content?>
</div>
<?
				$collection->anonces->show( $params->layout() );
			break;
		}
	}

}