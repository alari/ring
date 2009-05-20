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
<div style="padding: 20px; text-align: center">
<?
		if ($site->owner) {
			?>
<div class="comm-ava">
	<?=$site->owner->avatar()?></div><?
		}
		?>
<h2><a href="<?=$site->url()?>"><?=$site->title?></a></h2>
<?
		if ($site->owner) {
			?>
	Автор: <b><?=$site->owner->link()?></b>
<?
		}
		?>
</div>
<?
	}
}