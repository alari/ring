<?php
/**
 * @table site_comments -show:callback R_Mdl_Site_Comment::showComment
 *
 * @field owner -has one R_Mdl_User -inverse comments
 *
 * @field time INT -show date
 * @field content TEXT -edit area -show -required Нужно обязательно что-то ввести -title Комментарий
 */
abstract class R_Mdl_Site_Comment extends O_Dao_NestedSet_Node {
	const ROOT_CLASS = "R_Mdl_Site_Anonce";

	public function __construct( O_Dao_ActiveRecord $root )
	{
		if (!R_Mdl_Session::isLogged())
			throw new O_Ex_Logic( "Cannot create comment for not logged user." );
		$this->time = time();
		$this->owner = R_Mdl_Session::getUser();
		parent::__construct( $root );
	}

	static public function showComment( O_Dao_Renderer_Show_Params $params )
	{
		$comment = $params->record();
		if (!$comment instanceof self) {
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
<br style="clear: left" />
<div>
<div align="right"><a href="javascript:void(0)"
	onclick="R.Comment.showForm($(this).getParent(),'<?=O_UrlBuilder::get( "comment" )?>',<?=$rootId?>,<?=$parent?>,<?=$systemId?>)"><?=($parent ? "Ответить" : "Оставить отзыв")?></a></div>
<br style="clear: left" />
</div>
<br style="clear: left" />
<?
	}

}