<?php

class R_Fr_Site_Comment {
	/**
	 * Shows comment itself with comment add form
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showComment( O_Dao_Renderer_Show_Params $params )
	{
		$comment = $params->record();
		if (!$comment instanceof R_Mdl_Site_Comment ) {
			echo "Error<br/>";
			return;
		}
		?>

<div class="comm" style="margin-left:<?=$comment->level?>em">
	<div class="comm-ava"><?=$comment->owner->link()?>
	<?=$comment->owner->avatar()?></div>
<?=$comment->content?>
</div>

<?self::addForm( $comment[ "root" ], $comment->root->system->id, $comment->id );?>
<?
	}

	static public function addForm( $rootId, $systemId, $parent = 0 )
	{
		?>

<div class="comms<?=$parent?'':' lined'?>"><a href="javascript:void(0)"
	onclick="R.Comment.showForm($(this).getParent(),'<?=O_UrlBuilder::get( "comment" )?>',<?=$rootId?>,<?=$parent?>,<?=$systemId?>)"><?=($parent ? "Ответить" : "Оставить отзыв")?></a></div>
<?
	}
}