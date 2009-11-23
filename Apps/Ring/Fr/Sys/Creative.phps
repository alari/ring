<?php

class R_Fr_Sys_Creative {

	static public function showRssContCallback( O_Dao_Renderer_Show_Params $params )
	{
		echo $params->record()->content;
	}

	static public function showAtomPostCallback( O_Dao_Renderer_Show_Params $params )
	{
		$params->record()->show( $params->layout(), "rss-cont" );
		echo "<hr/>";
		$url = $params->record()->url();
		$title = htmlspecialchars( $params->record()->anonce->description );
		echo "<p align=\"center\">[ <strong><a href=\"$url\" title=\"$title\">" . $params->record()->title .
			 "</a></strong> ]</p>";
	}

		static public function showCreativeBottom( R_Mdl_Site_Anonce $a, O_Html_Layout $layout )
		{
			self::showNextPrev( $a );
			self::showLinkedAnonces( $a, $layout );
		}

		static protected function showNextPrev( R_Mdl_Site_Anonce $a )
		{
			$next = $a->getNext();
			$prev = $a->getPrevious();
			if ($next || $prev) {
				?>
<div class="next-prev">
			<?
				if ($prev) {
					echo '&lt; ';
					self::showNextPrevAnonce( $prev, $a );
				}
				if( $prev && $next )
				{
					echo ' | ';
				}
				if ($next) {
					self::showNextPrevAnonce( $next, $a );
					echo ' &gt;';
				}
				?>
			</div>
<?
			}
		}
		
		static protected function showAvatarBlock(R_Mdl_Site_Anonce $a) {
			if($a->system->instance["show_avatar"] == "no") return;
			?>
<div class="prop-ava">
<?=$a->owner->link()?>
<?=$a->owner->avatar()?>
</div>
			<?
		}
		

		static protected function showNextPrevAnonce( R_Mdl_Site_Anonce $a, R_Mdl_Site_Anonce $curr )
		{
			echo "<a href=\"" . $a->url() . "\">" . $a->title . "</a>";
			if ($a[ "collection" ] && $a[ "collection" ] != $curr[ "collection" ]) {
				echo " &ndash; <i><a href=\"" . $a->collection->url() . "\">" . $a->collection->title .
					 "</a></i>";
				}
			}

			static protected function showLinkedAnonces( R_Mdl_Site_Anonce $a )
			{
				$linked = $a->linked;
				if (count( $linked )) {
					echo "<br clear=\"right\"/>";
					echo "<br/><i>Связанные:</i>";
					echo "<ul>";
					foreach ($linked as $l)
						if ($l->isVisible()) {
							?><li><?=$l->link() . ($l[ "owner" ] == $a[ "owner" ] ? "" : " &ndash; <i>" . $l->owner->link() . ", <small>" . $l->system->link() . "</small></i>")?></li><?
						}
					echo "</ul>";
				}
			}

		}