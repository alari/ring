<?php

class R_Fr_Info_Topic extends R_Fr_Sys_Creative {

	/**
	 * Prints "add tag" block
	 *
	 * @param O_Dao_Renderer_Edit_Params $params
	 */
	static public function editList( $params )
	{
		if($params instanceof O_Dao_Renderer_Edit_Params){
			O_Dao_Renderer_Edit_Callbacks::selectRelationBox( $params );
		} else {
			$bl = new O_Form_Row_BoxList($params->getFieldName());
			$bl->autoProduce($params);
			$bl->render();
		}
		?>
<div class="oo-renderer-field">
<div class="oo-renderer-title">Добавить рубрику:</div>
<input class="text" type="text" name="topic_new" /></div>
<?
	}

}