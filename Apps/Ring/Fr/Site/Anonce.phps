<?php

class R_Fr_Site_Anonce {
	static public function showFullQuery( O_Dao_Renderer_Show_Params $params )
	{
		$q = $params->value();
		if(!$q instanceof O_Dao_Query ) {
			echo "Error<br>";
			return;
		}

		$q->preload(O_Dao_TableInfo::get($q->getClass())->getFieldInfo("creative")->getParam("one-of", 1));
		foreach($q as $anonce) {
			$anonce->creative->show();
		}
	}

	static public function showQuery(O_Dao_Renderer_Show_Params $params) {
		$q = $params->value();
		if(!$q instanceof O_Dao_Query ) {
			echo "Error<br>";
			return;
		}

		foreach($q as $anonce) {
			$anonce->show();
		}
	}

	static public function showSelf(O_Dao_Renderer_Show_Params $params) {
		$record = $params->record();
		switch(get_class($record->creative)) {
			case "R_Mdl_Blog_Post":
				?>
<span style="float:left;width:20%;border:1px solid orange;height:120px">
<strong><?=$record->link()?></strong><br/>
<?=$record->description?>
</span>
				<?
				break;
default:
		echo "<a href=\"".$params->record()->url()."\">".$params->record()->title."</a><br/>";
		}

	}


}