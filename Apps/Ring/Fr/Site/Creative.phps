<?php

class R_Fr_Site_Creative {
	static public function showRssContCallback(O_Dao_Renderer_Show_Params $params ) {
		echo $params->record()->content;
		echo "!ERROR";
	}
}