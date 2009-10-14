<?php

class R_Fr_Site {

	/**
	 * Shows sites in a loop mode
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showInLoop( O_Dao_Renderer_Show_Params $params )
	{
		$site = $params->record();
		?>
<div class="comm" style="padding: 20px; text-align: center">
<div class="comm-ava">
<?=$site->avatar()?>
</div>
<h2><?=$site->link()?>"></h2>
<?
		if ($site->type == R_Mdl_Site::TYPE_AUTH) {
			?>
	Автор: <b><?=$site->owner->link()?></b>
<?
		} elseif($site->type == R_Mdl_Site::TYPE_COMM) {
		?>
		<i>Сообщество</i>
		<?}?>
</div>
<?
	}
}