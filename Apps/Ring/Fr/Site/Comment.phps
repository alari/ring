<?php

class R_Fr_Site_Comment {
	/**
	 * Shows comment itself with comment add form
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showLoopCallback( O_Dao_Renderer_Show_Params $params )
	{
		$comment = $params->record();
		if (!$comment instanceof R_Mdl_Site_Comment ) {
			echo "Error<br/>";
			return;
		}
		$isList = $params->params() == "list";
		if($isList && !$comment->root->isVisible()) return;
		?>

<div class="comm"<?if(!$isList){?> style="margin-left:<?=$comment->level?>em"<?}?>>
	<div class="comm-ava"><?=$comment->owner->link()?>
	<?=$comment->owner->avatar()?></div>
<a name="comment<?=$comment->id?>"></a>

<?if($isList){?>
<div class="comm-post" style="padding:10px">
Комментарий на: <?=$comment->root->link()?> - <i><?=$comment->root->owner->link()?></i>; <?=$comment->root->system->link()?>
</div>
<?}?>

	<div class="comment-time"><?=date("d.m.Y H:i:s", $comment->time)?></div>

<?=$comment->content?>
</div>

<?if(!$isList) self::addForm( $comment[ "root" ], $comment->root->system->id, $comment->id );?>
<?
	}

	/**
	 * Shows add comment form
	 *
	 * @param int $rootId
	 * @param int $systemId
	 * @param int $parent
	 */
	static public function addForm( $rootId, $systemId, $parent = 0 )
	{
		?>

<div class="comms<?=$parent?'':' lined'?>"><a href="javascript:void(0)"
	onclick="R.Comment.showForm($(this).getParent(),'<?=O_UrlBuilder::get( "comment" )?>',<?=$rootId?>,<?=$parent?>,<?=$systemId?>)"><?=($parent ? "Ответить" : "Оставить отзыв")?></a></div>
<?
	}

	/**
	 * Shows RSS comment
	 *
	 * @param O_Dao_Renderer_Show_Params $params
	 */
	static public function showRssCallback(O_Dao_Renderer_Show_Params $params) {
		$record = $params->record();
?>
<item>
  <guid isPermaLink='true'><?=$record->root->url()?>#comment<?=$record->id?></guid>
  <pubDate><?=gmdate("D, d M Y H:i:s", $record->time)?> GMT</pubDate>
  <title><?=htmlspecialchars($record->root->title)?></title>
  <link><?=$record->root->url()?></link>
  <description><?=htmlspecialchars($record->owner->link()." пишет:<br/><br/>".$record->content)?></description>
</item>
<?
	}


}