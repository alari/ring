<?php
class R_Lf_Sys_Tpl_Creative extends R_Lf_Sys_Template {

	public function displayContents()
	{
		$this->creative->show( $this->layout() );
		$this->creative->nodes->show( $this->layout() );
		$next = $this->creative->anonce->getNext();
		$prev = $this->creative->anonce->getPrevious();
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
		if (count( $this->creative->anonce->linked )) {
			echo "<br clear=\"right\"/>";
			echo "<br/>";
			$this->creative->anonce->linked->show( $this->layout() );
		}
		R_Fr_Site_Comment::addForm( $this->creative->anonce->id, $this->creative->system->id );
		$this->layout()->setTitle(
				$this->creative->anonce->title . " - " . $this->creative->system->instance->title );
	}

	private function nextOrPrev( $a )
	{
		echo "<a href=\"" . $a->url() . "\">" . $a->title . "</a>";
		if ($a[ "collection" ] && $a[ "collection" ] != $this->creative->anonce[ "collection" ]) {
			echo " &ndash; <i><a href=\"" . $a->collection->url() . "\">" . $a->collection->title .
				 "</a></i>";
			}
		}

	}