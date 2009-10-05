<?php

class R_Fr_Info_Topic extends R_Fr_Sys_Creative {

	/**
	 * Prints "add tag" block
	 *
	 * @param O_Dao_Renderer_Edit_Params $params
	 */
	static public function editList( O_Dao_Renderer_Edit_Params $params )
	{
		O_Dao_Renderer_Edit_Callbacks::selectRelationBox( $params );
		?>
<div class="oo-renderer-field">
<div class="oo-renderer-title">Добавить рубрику:</div>
<input class="text" type="text" name="topic_new" /></div>
<?
	}

}