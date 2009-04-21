<?php

class R_Fr_Site_Comment {
	static public function showComment( O_Dao_Renderer_Show_Params $params )
	{
		$comment = $params->record();
		if (!$comment instanceof R_Mdl_Site_Comment ) {
			echo "Error<br/>";
			return;
		}
		?>

<div style="margin-left:<?=$comment->level?>em"><span
	style="float: left; width: 100px; clear: left"><?=$comment->owner->link()?><br />
<img src="<?=$comment->owner->avatarUrl()?>" /></span>
<?=$params->value()?>
</div>

<?
		self::addForm( $comment[ "root" ], $comment->root->system->id, $comment->id );
	}

	static public function addForm( $rootId, $systemId, $parent = 0 )
	{
		?>

<div style="clear: left" >
<div align="right"><a href="javascript:void(0)"
	onclick="R.Comment.showForm($(this).getParent(),'<?=O_UrlBuilder::get( "comment" )?>',<?=$rootId?>,<?=$parent?>,<?=$systemId?>)"><?=($parent ? "Ответить" : "Оставить отзыв")?></a></div>
<br style="clear: left" />
</div>
<?
	}
}