<?php

class R_Fr_Sys_Creative {

	static public function showRssContCallback( O_Dao_Renderer_Show_Params $params )
	{
		echo $params->record()->content;
	}

	static public function showAtomPostCallback( O_Dao_Renderer_Show_Params $params )
	{
		echo $params->record()->content;
		echo "<hr/>";
		$url = $params->record()->url();
		echo "[ <a href=\"$url\">" . $params->record()->title . "</a> ]";
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
<div align="right">
			<?
			if ($prev) {
				?><p>Назад: <?
				$this->nextOrPrev( $prev )?></p><?
			}
			?>
			<?
			if ($next) {
				?><p>Вперёд: <?
				$this->nextOrPrev( $next )?></p><?
			}
			?>
			</div>
<?
		}
	}

	static protected function showNextPrevAnonce( R_Mdl_Site_Anonce $a )
	{
		echo "<a href=\"" . $a->url() . "\">" . $a->title . "</a>";
		if ($a[ "collection" ] && $a[ "collection" ] != $this->creative->anonce[ "collection" ]) {
			echo " &ndash; <i><a href=\"" . $a->collection->url() . "\">" . $a->collection->title .
				 "</a></i>";
			}
		}

		static protected function showLinkedAnonces( R_Mdl_Site_Anonce $a, O_Html_Layout $layout )
		{
			if (count( $a->linked )) {
				echo "<br clear=\"right\"/>";
				echo "<br/>";
				$a->linked->show( $layout );
			}
		}

	}